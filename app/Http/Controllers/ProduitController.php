<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Stock;
use App\Models\Categorie;
use App\Models\Unite;
use App\Http\Requests\ProduitRequest;
use Illuminate\Http\Requests;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with(['categorie', 'unite'])->get();
        $categories = Categorie::all();
        $unites = Unite::all();

        return view('produits.index', compact('produits', 'categories', 'unites'));
    }

    public function create()
    {
        $categories = Categorie::all();
        $unites = Unite::all();
        return view('produits.create', compact('categories', 'unites'));
    }

    public function store(ProduitRequest $request)
    {
        try {
            $validated = $request->validated();
            $quantiteInitiale = $validated['quantite_initiale'] ?? 0;
            unset($validated['quantite_initiale']);

            // Force un stock minimum d'au moins 1
            $validated['stock_min'] = max(1, $validated['stock_min']);

            $produit = Produit::create($validated);

            // Création du stock initial
            Stock::create([
                'produit_id'  => $produit->id,
                'emplacement' => 'principal',
                'quantite'    => $quantiteInitiale,
            ]);

            return redirect()->route('produits.index')
                ->with('success', 'Produit cree avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la creation du produit.');
        }
    }

    public function edit(Produit $produit)
    {
        $categories = Categorie::all();
        $unites = Unite::all();
        return view('produits.edit', compact('produit', 'categories', 'unites'));
    }

    public function update(ProduitRequest $request, Produit $produit)
    {
        try {
            $validated = $request->validated();
            $quantiteInitiale = $validated['quantite_initiale'] ?? null;
            unset($validated['quantite_initiale']);

            // Stock min au moins 1
            $validated['stock_min'] = max(1, $validated['stock_min'] ?? $produit->stock_min);

            $produit->update($validated);

            // Ajustement de stock si fourni
            if ($quantiteInitiale !== null) {
                $stock = Stock::firstOrCreate(
                    ['produit_id' => $produit->id],
                    ['quantite' => 0, 'emplacement' => 'principal']
                );
                $stock->update(['quantite' => $quantiteInitiale]);
            }

            return redirect()->route('produits.index')
                ->with('success', 'Produit mis a jour avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise a jour du produit.');
        }
    }

    public function destroy(Produit $produit)
    {
        try {
            $produit->delete();

            return redirect()->route('produits.index')
                ->with('success', 'Produit supprime avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du produit.');
        }
    }

    /**
     * Liste des produits sous stock minimum
     */
    public function alerteStock()
    {
        $produits = Produit::whereColumn('quantite', '<=', 'stock_min')->get();
        return view('produits.alerte_stock', compact('produits'));
    }
}
