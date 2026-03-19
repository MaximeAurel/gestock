<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Client;
use App\Models\Paiement;
use App\Models\Achat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $yearStart = $today->copy()->startOfYear();

        $salesToday = Facture::whereDate('date_facture', $today)->count();
        $salesChange = $this->percentChange(
            Facture::whereDate('date_facture', $today->copy()->subDay())->count(),
            $salesToday
        );

        $revenueMonth = Facture::whereBetween('date_facture', [$monthStart, $today])->sum('total_ttc');
        $revenueChange = $this->percentChange(
            Facture::whereBetween('date_facture', [$monthStart->copy()->subMonth(), $monthStart->copy()->subDay()])->sum('total_ttc'),
            $revenueMonth
        );

        $customersYear = Client::count();
        $customersChange = $this->percentChange(
            Client::whereBetween('created_at', [$yearStart->copy()->subYear(), $yearStart->copy()->subDay()])->count(),
            $customersYear
        );

        // Chart data 7 derniers jours
        $dates = collect(range(6,0))->map(fn($d) => $today->copy()->subDays($d));
        $chartCategories = $dates->map->toDateString();
        $salesSeries = $dates->map(function($d){
            return Facture::whereDate('date_facture', $d)->count();
        });
        $revenueSeries = $dates->map(function($d){
            return Facture::whereDate('date_facture', $d)->sum('total_ttc');
        });
        $customersSeries = $dates->map(function($d){
            return Client::whereDate('created_at', $d)->count();
        });

        // Recent activity = derniers paiements
        $activities = Paiement::with('facture.client')->latest()->limit(6)->get();

        // Budget radar data (ventes vs achats)
        $allocated = $revenueMonth;
        $actual = Achat::whereBetween('date_achat', [$monthStart, $today])->sum('total_ttc');

        return view('dashboard', compact(
            'salesToday',
            'salesChange',
            'revenueMonth',
            'revenueChange',
            'customersYear',
            'customersChange',
            'chartCategories',
            'salesSeries',
            'revenueSeries',
            'customersSeries',
            'activities',
            'allocated',
            'actual'
        ));
    }

    private function percentChange($old, $current): float
    {
        if ($old == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $old) / $old) * 100;
    }
}
