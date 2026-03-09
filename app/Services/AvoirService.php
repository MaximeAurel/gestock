<?php

namespace App\Services;

use App\Models\Avoir;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use Exception;

class AvoirService
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Créer un avoir depuis une facture
     */
    public function creerDepuisFacture(Facture $facture, array $lignes): Avoir
    {
        return DB::transaction(function () use ($facture, $lignes)
        {
            if ($facture->statut === 'annule')
                throw new Exception("Impossible : facture annulée");

            $montantTotal = 0;

            foreach ($lignes as $ligne)
            {
                $montantTotal += $ligne['quantite'] * $ligne['prix_unitaire'];
            }

            // 🚨 Empêcher dépassement
            if ($montantTotal > $facture->total_ttc)
                throw new Exception("Montant avoir supérieur à la facture");

            $avoir = Avoir::create([
                'facture_id' => $facture->id,
                'date_avoir' => now(),
                'montant' => $montantTotal,
                'statut' => 'valide'
            ]);

            // 🔼 Retour en stock
            foreach ($lignes as $ligne)
            {
                $this->stockService->entree(
                    $ligne['produit_id'],
                    $ligne['quantite'],
                    "Avoir #{$avoir->id} Facture #{$facture->id}"
                );
            }

            // 💰 Mise à jour facture
            $facture->decrement('reste_a_payer', $montantTotal);

            if ($facture->reste_a_payer <= 0)
                $facture->update(['statut' => 'remboursee']);
            else
                $facture->update(['statut' => 'corrigee']);

            return $avoir;
        });
    }

    /**
     * Annuler un avoir
     */
    public function annuler(Avoir $avoir): void
    {
        DB::transaction(function () use ($avoir)
        {
            if ($avoir->statut === 'annule')
                throw new Exception("Avoir déjà annulé");

            $facture = $avoir->facture;

            // 🔻 Re-retirer stock
            foreach ($facture->lignes as $ligne)
            {
                $this->stockService->sortie(
                    $ligne->produit_id,
                    $ligne->quantite,
                    "Annulation Avoir #{$avoir->id}"
                );
            }

            $facture->increment('reste_a_payer', $avoir->montant);
            $facture->update(['statut' => 'valide']);

            $avoir->update(['statut' => 'annule']);
        });
    }
}
