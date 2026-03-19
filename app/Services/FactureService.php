<?php

namespace App\Services;

use App\Models\Facture;
use App\Models\LigneFacture;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use Exception;

class FactureService
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Génère un numéro unique de facture (format FAC-00001).
     */
    protected function generateNumero(): string
    {
        $next = (Facture::max('id') ?? 0) + 1;
        return 'FAC-' . str_pad((string)$next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Création d'une facture
     */
    public function creer(array $data): Facture
    {
        return DB::transaction(function () use ($data) {
            $facture = Facture::create([
                'numero' => $data['numero'] ?? $this->generateNumero(),
                'client_id' => $data['client_id'],
                'date_facture' => $data['date_facture'],
                'reference' => $data['reference'] ?? null,
                'total_ht' => 0,
                'total_tva' => 0,
                'total_ttc' => 0,
                'montant_paye' => 0,
                'reste_a_payer' => 0,
                'statut' => 'brouillon'
            ]);

            $totalHT = 0;
            $totalTVA = 0;

            foreach ($data['lignes'] as $ligne) {
                // Vérification stock
                $this->stockService->verifierDisponible(
                    $ligne['produit_id'],
                    $ligne['quantite']
                );

                $montantHT = $ligne['quantite'] * $ligne['prix_unitaire'];
                $tva = $ligne['tva'] ?? 0;
                $montantTVA = $montantHT * ($tva / 100);

                LigneFacture::create([
                    'facture_id' => $facture->id,
                    'produit_id' => $ligne['produit_id'],
                    'quantite' => $ligne['quantite'],
                    // La colonne s'appelle 'prix' dans la table ligne_factures
                    'prix' => $ligne['prix_unitaire'],
                    'tva' => $tva,
                    'total' => $montantHT + $montantTVA
                ]);

                // Sortie de stock
                $this->stockService->sortie(
                    $ligne['produit_id'],
                    $ligne['quantite'],
                    "Facture #{$facture->id}"
                );

                $totalHT += $montantHT;
                $totalTVA += $montantTVA;
            }

            $facture->update([
                'total_ht' => $totalHT,
                'total_tva' => $totalTVA,
                'total_ttc' => $totalHT + $totalTVA,
                'reste_a_payer' => $totalHT + $totalTVA,
                'statut' => 'valide'
            ]);

            return $facture;
        });
    }

    /**
     * Mettre à jour une facture
     */
    public function mettreAJour(Facture $facture, array $data): Facture
    {
        return DB::transaction(function () use ($facture, $data) {
            $totalHT = 0;
            $totalTVA = 0;

            $facture->update([
                'client_id' => $data['client_id'],
                'date_facture' => $data['date_facture'],
                'reference' => $data['reference'] ?? null,
            ]);

            $facture->lignes()->delete();

            foreach ($data['lignes'] as $ligne) {
                $montantHT = $ligne['quantite'] * $ligne['prix_unitaire'];
                $tva = $ligne['tva'] ?? 0;
                $montantTVA = $montantHT * ($tva / 100);

                LigneFacture::create([
                    'facture_id' => $facture->id,
                    'produit_id' => $ligne['produit_id'],
                    'quantite' => $ligne['quantite'],
                    'prix' => $ligne['prix_unitaire'],
                    'tva' => $tva,
                    'total' => $montantHT + $montantTVA
                ]);

                $totalHT += $montantHT;
                $totalTVA += $montantTVA;
            }

            $facture->update([
                'total_ht' => $totalHT,
                'total_tva' => $totalTVA,
                'total_ttc' => $totalHT + $totalTVA,
            ]);

            return $facture->fresh('lignes.produit', 'client');
        });
    }

    /**
     * Enregistrer un paiement
     */
    public function payer(Facture $facture, float $montant, string $mode): Paiement
    {
        return DB::transaction(function () use ($facture, $montant, $mode) {
            if ($facture->statut === 'annule') {
                throw new Exception("Facture annulée");
            }

            $paiement = Paiement::create([
                'facture_id' => $facture->id,
                'montant' => $montant,
                'mode_paiement' => $mode,
                'date_paiement' => now()
            ]);

            $facture->increment('montant_paye', $montant);
            $facture->decrement('reste_a_payer', $montant);

            // Mise à jour statut automatique
            if ($facture->reste_a_payer <= 0) {
                $facture->update(['statut' => 'payee']);
            } else {
                $facture->update(['statut' => 'partiellement_payee']);
            }

            return $paiement;
        });
    }

    /**
     * Annuler une facture
     */
    public function annuler(Facture $facture): void
    {
        DB::transaction(function () use ($facture) {
            if ($facture->statut === 'annule') {
                throw new Exception("Facture déjà annulée");
            }

            // Retour stock
            foreach ($facture->lignes as $ligne) {
                $this->stockService->entree(
                    $ligne->produit_id,
                    $ligne->quantite,
                    "Annulation Facture #{$facture->id}"
                );
            }

            $facture->update(['statut' => 'annule']);
        });
    }

    /**
     * Suppression définitive
     */
    public function supprimer(Facture $facture): void
    {
        DB::transaction(function () use ($facture) {
            $this->annuler($facture);

            $facture->paiements()->delete();
            $facture->lignes()->delete();
            $facture->delete();
        });
    }
}
