<?php

namespace App\Providers;

use App\Models\Achat;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Stock;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.header', function ($view) {
            $user = Auth::user();

            if (!$user) {
                $view->with([
                    'headerNotifications' => [],
                    'headerRoleLabel' => 'Utilisateur',
                ]);
                return;
            }

            $role = strtolower(trim($user->role->nom ?? ''));
            $isAdmin = in_array($role, ['admin', 'administrateur'], true);
            $isComptable = in_array($role, ['comptable', 'gestionnaire stock', 'gestionnaire de stock'], true);
            $isVendeur = in_array($role, ['vendeur', 'commercial'], true);

            $notifications = [];
            $today = now()->toDateString();
            $expSoon = now()->addDays(3)->toDateString();

            $addNotification = function (string $title, string $message, string $route, string $icon, string $iconClass) use (&$notifications): void {
                $notifications[] = [
                    'title' => $title,
                    'message' => $message,
                    'route' => $route,
                    'icon' => $icon,
                    'iconClass' => $iconClass,
                    'time' => 'Aujourd\'hui',
                ];
            };

            try {
                if (Schema::hasTable('stocks') && ($isAdmin || $isComptable)) {
                    $outOfStockCount = Stock::where('quantite', '<=', 0)->count();
                    if ($outOfStockCount > 0) {
                        $addNotification(
                            'Rupture de stock',
                            $outOfStockCount . ' produit(s) en rupture',
                            'stocks.index',
                            'bi-exclamation-octagon',
                            'text-danger'
                        );
                    }

                    $lowStockCount = Stock::where('quantite', '>', 0)->where('quantite', '<=', 5)->count();
                    if ($lowStockCount > 0) {
                        $addNotification(
                            'Stock faible',
                            $lowStockCount . ' produit(s) sous le seuil de 5',
                            'stocks.index',
                            'bi-exclamation-circle',
                            'text-warning'
                        );
                    }
                }

                if (Schema::hasTable('devis') && ($isAdmin || $isComptable || $isVendeur)) {
                    $expiringQuotesCount = Devis::whereNotNull('date_expiration')
                        ->whereDate('date_expiration', '>=', $today)
                        ->whereDate('date_expiration', '<=', $expSoon)
                        ->count();

                    if ($expiringQuotesCount > 0) {
                        $addNotification(
                            'Devis a relancer',
                            $expiringQuotesCount . ' devis expirent sous 3 jours',
                            'devis.index',
                            'bi-clock-history',
                            'text-warning'
                        );
                    }
                }

                if (Schema::hasTable('factures') && ($isAdmin || $isComptable || $isVendeur)) {
                    $todayInvoicesCount = Facture::whereDate('date_facture', $today)->count();
                    if ($todayInvoicesCount > 0) {
                        $addNotification(
                            'Facturation du jour',
                            $todayInvoicesCount . ' facture(s) creees aujourd\'hui',
                            'factures.index',
                            'bi-receipt',
                            'text-primary'
                        );
                    }
                }

                if (Schema::hasTable('paiements') && ($isAdmin || $isComptable)) {
                    $todayPaymentsCount = Paiement::whereDate('date_paiement', $today)->count();
                    if ($todayPaymentsCount > 0) {
                        $addNotification(
                            'Paiements du jour',
                            $todayPaymentsCount . ' paiement(s) enregistres',
                            'paiements.index',
                            'bi-cash-coin',
                            'text-success'
                        );
                    }
                }

                if (Schema::hasTable('achats') && ($isAdmin || $isComptable)) {
                    $todayPurchasesCount = Achat::whereDate('date_achat', $today)->count();
                    if ($todayPurchasesCount > 0) {
                        $addNotification(
                            'Achats du jour',
                            $todayPurchasesCount . ' achat(s) valides aujourd\'hui',
                            'achats.index',
                            'bi-bag-check',
                            'text-info'
                        );
                    }
                }
            } catch (QueryException) {
                $notifications = [];
            }

            $view->with([
                'headerNotifications' => $notifications,
                'headerRoleLabel' => $user->role->nom ?? 'Utilisateur',
            ]);
        });
    }
}
