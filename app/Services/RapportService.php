<?php

namespace App\Services;

use App\Models\Facture;
use App\Models\Achat;
use App\Models\MouvementStock;
use App\Models\Client;

class RapportService
{
    public function stats()
    {
        return [
            'totalClients' => Client::count(),
            'totalAchats' => Achat::count(),
            'totalFactures' => Facture::count(),
            'totalMouvements' => MouvementStock::count(),
        ];
    }
}