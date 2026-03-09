<?php

namespace App\Services;

use App\Models\Achat;
use App\Models\LigneAchat;
use Illuminate\Support\Facades\DB;
use Exception;

class AchatService
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Création d'un achat avec ses lignes
     */
    public function creer(array $data): Achat
    {
        return DB::transaction(function () use ($data) {

            $achat = Achat::create([
                'fournisseur_id' => $data['fournisseur_id'],
                'date_achat'     => $data['date_achat'],
                'reference'      => $data['reference'] ?? null,
                'total_ht'       => 0,
                'total_tva'      => 0,
                'total_ttc'      => 0,
                'statut'         => 'valide'
            ]);

            $totalHT  = 0;
            $totalTVA = 0;

            foreach ($data['lignes'] as $ligne)
            {
                $montantHT = $ligne['quantite'] * $ligne['prix_unitaire'];
                $montantTVA = $montantHT * ($ligne['tva'] / 100);

                LigneAchat::create([
                    'achat_id'       => $achat->id,
                    'produit_id'     => $ligne['produit_id'],
                    'quantite'       => $ligne['quantite'],
                    'prix_unitaire'  => $ligne['prix_unitaire'],
                    'tva'            => $ligne['tva'],
                    'total'          => $montantHT + $montantTVA
                ]);

                // 🔼 Entrée en stock
                $this->stockService->entree(
                    $ligne['produit_id'],
                    $ligne['quantite'],
                    "Achat #{$achat->id}"
                );

                $totalHT  += $montantHT;
                $totalTVA += $montantTVA;
            }

            $achat->update([
                'total_ht'  => $totalHT,
                'total_tva' => $totalTVA,
                'total_ttc' => $totalHT + $totalTVA
            ]);

            return $achat;
        });
    }

    /**
     * Mise à jour d'un achat
     */
    public function modifier(Achat $achat, array $data): Achat
    {
        return DB::transaction(function () use ($achat, $data) {

            // 🔽 Annule le stock précédent
            foreach ($achat->lignes as $ligne)
            {
                $this->stockService->sortie(
                    $ligne->produit_id,
                    $ligne->quantite,
                    "Correction Achat #{$achat->id}"
                );
            }

            // Supprime les anciennes lignes
            $achat->lignes()->delete();

            // Recréation
            return $this->creer([
                ...$data,
                'fournisseur_id' => $achat->fournisseur_id,
                'date_achat'     => $achat->date_achat,
            ]);
        });
    }

    /**
     * Annulation d'un achat
     */
    public function annuler(Achat $achat): void
    {
        DB::transaction(function () use ($achat) {

            if ($achat->statut === 'annule')
                throw new Exception("Achat déjà annulé");

            foreach ($achat->lignes as $ligne)
            {
                $this->stockService->sortie(
                    $ligne->produit_id,
                    $ligne->quantite,
                    "Annulation Achat #{$achat->id}"
                );
            }

            $achat->update(['statut' => 'annule']);
        });
    }

    /**
     * Suppression définitive
     */
    public function supprimer(Achat $achat): void
    {
        DB::transaction(function () use ($achat) {

            $this->annuler($achat);

            $achat->lignes()->delete();
            $achat->delete();
        });
    }

    /**
     * Mettre à jour un achat
     */
    public function mettreAJour($achatId, array $data)
    {
        $achat = Achat::findOrFail($achatId);
        
        $achat->update([
            'date_achat' => $data['date_achat'] ?? $achat->date_achat,
            'fournisseur_id' => $data['fournisseur_id'] ?? $achat->fournisseur_id,
            'statut' => $data['statut'] ?? $achat->statut,
            // Ajoutez d'autres champs selon votre besoin
        ]);
        
        // Mettez à jour les lignes d'achat si nécessaire
        if (isset($data['lignes'])) {
            // Logique de mise à jour des lignes
        }
        
        return $achat;
    }
}
