<?php

use App\Http\Controllers\AchatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvoirController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\MouvementStockController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PAGE D'ACCUEIL (LOGIN INTELLIGENT)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');

Route::post('/logout', [AuthController::class,'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD SECURISE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');



    /*
|--------------------------------------------------------------------------
| Produits et Stock
|--------------------------------------------------------------------------
*/

Route::resource('categories', CategoriesController::class);
Route::resource('unites', UniteController::class);
Route::resource('produits', ProduitController::class);
// Routes spécifiques déplacées plus bas (voir section Stocks)
Route::get('rapports', [RapportController::class, 'index'])->name('rapports.index');


/*
|--------------------------------------------------------------------------
| Clients et Fournisseurs
|--------------------------------------------------------------------------
*/

Route::resource('clients', ClientController::class);
Route::resource('fournisseurs', FournisseurController::class);


/*
|--------------------------------------------------------------------------
| Achats
|--------------------------------------------------------------------------
*/

Route::prefix('achats')->group(function () {
    Route::get('/', [AchatController::class, 'index'])->name('achats.index');
    Route::post('/', [AchatController::class, 'store'])->name('achats.store');
    Route::get('/create', [AchatController::class, 'create'])->name('achats.create');
    Route::get('/{achat}/edit', [AchatController::class, 'edit'])->name('achats.edit');
    Route::match(['put','patch'],'/{achat}', [AchatController::class, 'update'])->name('achats.update');
    Route::patch('/{achat}/annuler', [AchatController::class, 'annuler'])->name('achats.annuler'); // ← ici
    Route::delete('/{achat}', [AchatController::class, 'destroy'])->name('achats.destroy');
});


/*
|--------------------------------------------------------------------------
| Ventes
|--------------------------------------------------------------------------
*/

Route::resource('factures', FactureController::class);
Route::resource('devis', DevisController::class);
Route::resource('avoirs', AvoirController::class);
Route::resource('paiements', PaiementController::class);



/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/

Route::resource('users', UserController::class);

/*
|--------------------------------------------------------------------------
| Stocks
|--------------------------------------------------------------------------
*/
Route::post('stocks/entree', [StockController::class, 'entree'])->name('stocks.entree');
Route::post('stocks/sortie', [StockController::class, 'sortie'])->name('stocks.sortie');
Route::delete('stocks/mouvements/{mouvement}', [StockController::class, 'annulerMouvement'])->name('stocks.annuler');
Route::resource('stocks', StockController::class)->only(['index','show']);
Route::resource('mouvement-stocks', MouvementStockController::class)->only(['index','show']);


Route::get('parametres', [ParametreController::class, 'index'])->name('parametres.index');
Route::post('parametres', [ParametreController::class, 'update'])->name('parametres.update');

});
