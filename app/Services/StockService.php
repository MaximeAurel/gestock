<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\Produit;
use App\Models\MouvementStock;
use Illuminate\Support\Facades\DB;
use Exception;

class StockService
{

    /**
     * Entrée de stock
     */
    public function entree(int $produitId, int $quantite, string $motif = null): void
    {
        DB::transaction(function () use ($produitId, $quantite, $motif) {

            $stock = Stock::firstOrCreate(
                ['produit_id' => $produitId],
                ['quantite' => 0, 'emplacement' => 'principal']
            );

            $stock->increment('quantite', $quantite);

            MouvementStock::create([
                'produit_id' => $produitId,
                'type' => 'entree',
                'quantite' => $quantite,
                'motif' => $motif
            ]);

        });
    }

    /**
     * Sortie de stock
     */
    public function sortie(int $produitId, int $quantite, string $motif = null): void
    {
        DB::transaction(function () use ($produitId, $quantite, $motif) {

            $stock = Stock::where('produit_id', $produitId)->lockForUpdate()->first();

            if (!$stock || $stock->quantite < $quantite) {
                throw new Exception("Stock insuffisant pour ce produit");
            }

            $stock->decrement('quantite', $quantite);

            MouvementStock::create([
                'produit_id' => $produitId,
                'type' => 'sortie',
                'quantite' => $quantite,
                'motif' => $motif
            ]);

        });
    }

    /**
     * Retour stock (avoir ou annulation)
     */
    public function retour(int $produitId, int $quantite, string $motif = "Retour"): void
    {
        $this->entree($produitId, $quantite, $motif);
    }

    /**
     * Vérifier disponibilité
     */
    public function verifierDisponible(int $produitId, int $quantite): void
    {
        $stock = \App\Models\Stock::where('produit_id', $produitId)->first();
        if (!$stock || $stock->quantite < $quantite) {
            throw new \Exception("Stock insuffisant pour le produit ID {$produitId}");
        }
    }

    /**
     * Obtenir stock actuel
     */
    public function stockDisponible(int $produitId): int
    {
        return Stock::where('produit_id', $produitId)->value('quantite') ?? 0;
    }

    /**
     * Alerte stock minimum
     */
    public function estSousStock(int $produitId): bool
    {
        $produit = Produit::find($produitId);

        if (!$produit) return false;

        $stock = $this->stockDisponible($produitId);

        return $stock <= $produit->stock_min;
    }

    /**
     * Annuler un mouvement (rollback métier)
     */
    public function annulerMouvement(MouvementStock $mouvement): void
    {
        DB::transaction(function () use ($mouvement) {

            if ($mouvement->type === 'entree') {
                $this->sortie($mouvement->produit_id, $mouvement->quantite, 'Annulation mouvement');
            } else {
                $this->entree($mouvement->produit_id, $mouvement->quantite, 'Annulation mouvement');
            }

            $mouvement->delete();
        });
    }
}
