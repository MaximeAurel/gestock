<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Achat;
use App\Models\MouvementStock;
use App\Models\Client;


class RapportController extends Controller
{
    /**
     * Affiche le tableau de bord des rapports
     */
    public function index()
    {
        // Exemple : récupérer quelques statistiques
        $totalClients = Client::count();
        $totalAchats = Achat::count();
        $totalFactures = Facture::count();
        $totalMouvements = MouvementStock::count();

        return view('rapports.index', compact(
            'totalClients',
            'totalAchats',
            'totalFactures',
            'totalMouvements'
        ));
    }
}