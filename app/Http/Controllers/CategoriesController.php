<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Http\Requests\CategorieRequest;
use Illuminate\Http\Requests;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categorie::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategorieRequest $request)
    {
        try {
            Categorie::create($request->validated());

            return redirect()->route('categories.index')
                ->with('success', 'Catégorie créée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de la catégorie.');
        }
    }

    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }

    public function show(Categorie $categorie)
    {
        $categorie->loadCount('produits');
        return view('categories.show', compact('categorie'));
    }

    public function update(CategorieRequest $request, Categorie $categorie)
    {
        try {
            $categorie->update($request->validated());

            return redirect()->route('categories.index')
                ->with('success', 'Catégorie mise à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de la catégorie.');
        }
    }

    public function destroy(Categorie $categorie)
    {
        try {
            $categorie->delete();

            return redirect()->route('categories.index')
                ->with('success', 'Catégorie supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de la catégorie.');
        }
    }
}
