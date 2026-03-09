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
Route::resource('stocks', StockController::class);
Route::resource('mouvement-stocks', MouvementStockController::class);
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

Route::resource('achats', AchatController::class);


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


Route::get('parametres', [ParametreController::class, 'index'])->name('parametres.index');
Route::post('parametres', [ParametreController::class, 'update'])->name('parametres.update');

});



