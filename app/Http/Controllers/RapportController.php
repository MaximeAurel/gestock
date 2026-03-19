<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Achat;
use App\Models\MouvementStock;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Paiement;
use App\Models\Avoir;
use App\Models\Stock;
use App\Models\LigneFacture;
use App\Models\LigneAchat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Throwable;
// PDF facultatif si le package est installé
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    public function index()
    {
        return view('rapports.index', $this->data());
    }

    public function exportExcel()
    {
        $data = $this->data();

        $rows = [];
        $rows[] = ['Section','Libellé','Valeur'];
        $rows[] = ['Ventes','Factures',$data['ventes']['factures']];
        $rows[] = ['Ventes','CA HT',$data['ventes']['ht']];
        $rows[] = ['Ventes','TVA',$data['ventes']['tva']];
        $rows[] = ['Ventes','CA TTC',$data['ventes']['ttc']];
        $rows[] = ['Achats','Total',$data['achats']['total']];
        $rows[] = ['Financier','Encaissé',$data['financier']['encaisse']];
        $rows[] = ['Financier','Avoirs',$data['financier']['avoirs']];
        $rows[] = ['Financier','Balance',$data['financier']['balance']];
        $rows[] = ['Stock','Valeur',$data['stockValue']];
        $rows[] = ['Clients','Total',$data['clientsCount']];
        $rows[] = ['Fournisseurs','Total',$data['fournisseursCount']];

        $callback = function() use ($rows) {
            $FH = fopen('php://output','w');
            foreach ($rows as $r) {
                fputcsv($FH, $r, ';');
            }
            fclose($FH);
        };

        return Response::streamDownload($callback, 'rapports.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8'
        ]);
    }

    public function exportPdf()
    {
        $data = $this->data();
        try {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = Pdf::loadView('rapports.pdf', $data);
                return $pdf->download('rapports.pdf');
            }
        } catch (Throwable $e) {
            // fallback below
        }
        // Fallback : HTML à imprimer/exporter en PDF via navigateur
        return view('rapports.pdf', $data);
    }

    private function data(): array
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $yearStart = $today->copy()->startOfYear();

        $ventes = [
            'factures' => Facture::count(),
            'ht' => Facture::sum('total_ht'),
            'tva' => Facture::sum('total_tva'),
            'ttc' => Facture::sum('total_ttc'),
            'jour' => Facture::whereDate('date_facture', $today)->sum('total_ttc'),
            'mois' => Facture::whereBetween('date_facture', [$monthStart, $today])->sum('total_ttc'),
            'annee' => Facture::whereBetween('date_facture', [$yearStart, $today])->sum('total_ttc'),
        ];

        $topProduitsVendus = LigneFacture::select('produit_id', DB::raw('SUM(quantite) as qte'), DB::raw('SUM(total) as total'))
            ->with('produit')
            ->groupBy('produit_id')
            ->orderByDesc('qte')
            ->limit(5)->get();
        $topClients = Facture::select('client_id', DB::raw('SUM(total_ttc) as total'), DB::raw('COUNT(*) as factures'))
            ->with('client')
            ->groupBy('client_id')
            ->orderByDesc('total')
            ->limit(5)->get();

        $achats = [
            'achats' => Achat::count(),
            'total' => Achat::sum('total_ttc'),
            'mois' => Achat::whereBetween('date_achat', [$monthStart, $today])->sum('total_ttc'),
            'annee' => Achat::whereBetween('date_achat', [$yearStart, $today])->sum('total_ttc'),
        ];
        $topFournisseurs = Achat::select('fournisseur_id', DB::raw('SUM(total_ttc) as total'), DB::raw('COUNT(*) as achats'))
            ->with('fournisseur')
            ->groupBy('fournisseur_id')
            ->orderByDesc('total')
            ->limit(5)->get();
        $topProduitsAchetes = LigneAchat::select('produit_id', DB::raw('SUM(quantite) as qte'))
            ->with('produit')
            ->groupBy('produit_id')
            ->orderByDesc('qte')
            ->limit(5)->get();

        $stocks = Stock::with('produit')->get();
        $stockValue = $stocks->sum(fn($s) => ($s->produit->prix_vente ?? 0) * $s->quantite);
        $ruptures = $stocks->filter(fn($s) => $s->quantite <= 0);
        $sousSeuil = $stocks->filter(fn($s) => $s->quantite > 0 && $s->quantite <= 5);
        $topStock = $stocks->sortByDesc('quantite')->take(5);

        $financier = [
            'encaisse' => Paiement::sum('montant'),
            'avoirs' => Avoir::sum('montant'),
            'payees' => Facture::where('reste_a_payer','<=',0)->count(),
            'impayees' => Facture::where('reste_a_payer','>',0)->count(),
            'balance' => Paiement::sum('montant') - Achat::sum('total_ttc'),
        ];

        $clientsCount = Client::count();
        $fournisseursCount = Fournisseur::count();

        return compact(
            'ventes',
            'achats',
            'topProduitsVendus',
            'topClients',
            'topFournisseurs',
            'topProduitsAchetes',
            'stocks',
            'stockValue',
            'ruptures',
            'sousSeuil',
            'topStock',
            'financier',
            'clientsCount',
            'fournisseursCount'
        );
    }
}
