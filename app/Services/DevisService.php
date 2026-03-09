<?php

namespace App\Services;

use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use Exception;

class DevisService
{
    protected FactureService $factureService;

    public function __construct(FactureService $factureService)
    {
        $this->factureService = $factureService;
    }

    /**
     * Création d'un devis
     */
    public function creer(array $data): Devis
    {
        return DB::transaction(function () use ($data)
        {
            $totalHT = 0;
            $totalTVA = 0;

            $devis = Devis::create([
                'client_id' => $data['client_id'],
                'date_devis' => $data['date_devis'],
                'date_expiration' => $data['date_expiration'],
                'statut' => 'brouillon',
                'total_ht' => 0,
                'total_tva' => 0,
                'total_ttc' => 0
            ]);

            foreach ($data['lignes'] as $ligne)
            {
                $montantHT = $ligne['quantite'] * $ligne['prix_unitaire'];
                $montantTVA = $montantHT * ($ligne['tva'] / 100);

                $devis->lignes()->create([
                    'produit_id' => $ligne['produit_id'],
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'tva' => $ligne['tva'],
                    'total' => $montantHT + $montantTVA
                ]);

                $totalHT += $montantHT;
                $totalTVA += $montantTVA;
            }

            $devis->update([
                'total_ht' => $totalHT,
                'total_tva' => $totalTVA,
                'total_ttc' => $totalHT + $totalTVA
            ]);

            return $devis;
        });
    }

    /**
     * Valider un devis (accepté par client)
     */
    public function valider(Devis $devis): void
    {
        if ($devis->statut !== 'brouillon')
            throw new Exception("Devis déjà traité");

        $devis->update(['statut' => 'valide']);
    }

    /**
     * Expirer automatiquement
     */
    public function expirer(Devis $devis): void
    {
        if (now()->gt($devis->date_expiration))
            $devis->update(['statut' => 'expire']);
    }

    /**
     * Conversion devis → facture
     */
    public function convertirEnFacture(Devis $devis): Facture
    {
        if ($devis->statut !== 'valide')
            throw new Exception("Seul un devis validé peut être facturé");

        $factureData = [
            'client_id' => $devis->client_id,
            'date_facture' => now(),
            'reference' => "DEV-{$devis->id}",
            'lignes' => []
        ];

        foreach ($devis->lignes as $ligne)
        {
            $factureData['lignes'][] = [
                'produit_id' => $ligne->produit_id,
                'quantite' => $ligne->quantite,
                'prix_unitaire' => $ligne->prix_unitaire,
                'tva' => $ligne->tva
            ];
        }

        $facture = $this->factureService->creer($factureData);

        $devis->update(['statut' => 'converti']);

        return $facture;
    }
}
