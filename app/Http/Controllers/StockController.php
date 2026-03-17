<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\Produit;
use App\Http\Requests\MouvementStockRequest;
use App\Services\StockService;
use Illuminate\Http\Requests;

class StockController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $produits = Produit::with('stocks')
            ->withSum('stocks as quantite', 'quantite')
            ->orderBy('designation')
            ->get();

        $produitsCount = $produits->count();

        return view('stocks.index', compact('produits', 'produitsCount'));
    }

    public function show(Produit $produit)
    {
        $mouvements = $produit->mouvements()->latest()->get();
        return view('stocks.show', compact('produit', 'mouvements'));
    }

    public function entree(MouvementStockRequest $request)
    {
        try {
            $this->stockService->entree(
                $request->produit_id,
                $request->quantite,
                $request->motif
            );

            return redirect()->route('stocks.index')
                ->with('success', 'Stock ajouté avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l’entrée en stock.');
        }
    }

    public function sortie(MouvementStockRequest $request)
    {
        try {
            $this->stockService->sortie(
                $request->produit_id,
                $request->quantite,
                $request->motif
            );

            return redirect()->route('stocks.index')
                ->with('success', 'Stock retiré avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la sortie de stock.');
        }
    }

    public function annulerMouvement(MouvementStock $mouvement)
    {
        try {
            $this->stockService->annulerMouvement($mouvement->id);

            return redirect()->route('stocks.index')
                ->with('success', 'Mouvement annulé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l’annulation du mouvement.');
        }
    }
}
