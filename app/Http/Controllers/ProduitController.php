<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Unite;
use App\Http\Requests\ProduitRequest;
use Illuminate\Http\Requests;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with(['categorie', 'unite'])->get();
        return view('produits.index', compact('produits'));
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
            Produit::create($request->validated());

            return redirect()->route('produits.index')
                ->with('success', 'Produit créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du produit.');
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
            $produit->update($request->validated());

            return redirect()->route('produits.index')
                ->with('success', 'Produit mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du produit.');
        }
    }

    public function destroy(Produit $produit)
    {
        try {
            $produit->delete();

            return redirect()->route('produits.index')
                ->with('success', 'Produit supprimé avec succès !');
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
