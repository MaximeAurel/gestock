<?php

namespace App\Services;

use App\Models\Achat;
use Illuminate\Support\Facades\DB;

class AchatService
{
    // =============================
    // Création d'un achat avec lignes
    // =============================
    public function creer(array $data): Achat
    {
        return DB::transaction(function () use ($data) {
            $numero = $data['numero'] ?? 'ACH-' . now()->format('Ymd-His');

            // Création de l'achat
            $achat = Achat::create([
                'numero'         => $numero,
                'fournisseur_id' => $data['fournisseur_id'],
                'date_achat'     => $data['date_achat'],
                'total_ht'       => 0,  // sera recalculé après création des lignes
                'total_ttc'      => 0,
                'statut'         => $data['statut'] ?? 'valide',
            ]);

            $total_ht = 0;
            $total_ttc = 0;

            // Création des lignes
            if (isset($data['lignes']) && is_array($data['lignes'])) {
                foreach ($data['lignes'] as $ligne) {
                    $ligneAchat = $achat->lignes()->create([
                        'produit_id'    => $ligne['produit_id'],
                        'quantite'      => $ligne['quantite'],
                        // On mappe vers les colonnes existantes prix/total
                        'prix'          => $ligne['prix_unitaire'],
                        'total'         => ($ligne['quantite'] * $ligne['prix_unitaire']) * (1 + ($ligne['tva'] ?? 0) / 100),
                    ]);

                    $total_ht  += $ligneAchat->quantite * $ligneAchat->prix;
                    $total_ttc += $ligneAchat->total;
                }
            }

            // Mise à jour des totaux
            $achat->update([
                'total_ht'  => $total_ht,
                'total_ttc' => $total_ttc,
            ]);

            return $achat;
        });
    }

    // =============================
    // Mise à jour d'un achat
    // =============================
    public function mettreAJour(int $achatId, array $data): Achat
    {
        return DB::transaction(function () use ($achatId, $data) {
            $achat = Achat::findOrFail($achatId);

            // Mise à jour de l'achat
            $achat->update([
                'numero'         => $data['numero'] ?? $achat->numero,
                'fournisseur_id' => $data['fournisseur_id'] ?? $achat->fournisseur_id,
                'date_achat'     => $data['date_achat'] ?? $achat->date_achat,
                'statut'         => $data['statut'] ?? $achat->statut,
            ]);

            // Si des lignes sont fournies, on remplace et on recalcule les totaux
            if (isset($data['lignes']) && is_array($data['lignes'])) {
                $achat->lignes()->delete();

                $total_ht = 0;
                $total_ttc = 0;

                foreach ($data['lignes'] as $ligne) {
                    $ligneAchat = $achat->lignes()->create([
                        'produit_id'    => $ligne['produit_id'],
                        'quantite'      => $ligne['quantite'],
                        'prix'          => $ligne['prix_unitaire'],
                        'total'         => ($ligne['quantite'] * $ligne['prix_unitaire']) * (1 + ($ligne['tva'] ?? 0) / 100),
                    ]);

                    $total_ht  += $ligneAchat->quantite * $ligneAchat->prix;
                    $total_ttc += $ligneAchat->total;
                }

                $achat->update([
                    'total_ht'  => $total_ht,
                    'total_ttc' => $total_ttc,
                ]);
            }

            return $achat;
        });
    }

    // =============================
    // Annulation d'un achat
    // =============================
    public function annuler(int $achatId): void
    {
        $achat = Achat::findOrFail($achatId);
        $achat->update(['statut' => 'annule']);
    }

    // =============================
    // Suppression d'un achat
    // =============================
    public function supprimer(int $achatId): void
    {
        DB::transaction(function () use ($achatId) {
            $achat = Achat::findOrFail($achatId);
            $achat->lignes()->delete(); // Supprime les lignes
            $achat->delete();            // Supprime l'achat
        });
    }
}
