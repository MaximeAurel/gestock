<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Http\Requests\FournisseurRequest;
use Illuminate\Http\Requests;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(FournisseurRequest $request)
    {
        try {
            Fournisseur::create($request->validated());

            return redirect()->route('fournisseurs.index')
                ->with('success', 'Fournisseur créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du fournisseur.');
        }
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(FournisseurRequest $request, Fournisseur $fournisseur)
    {
        try {
            $fournisseur->update($request->validated());

            return redirect()->route('fournisseurs.index')
                ->with('success', 'Fournisseur mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du fournisseur.');
        }
    }

    public function destroy(Fournisseur $fournisseur)
    {
        try {
            $fournisseur->delete();

            return redirect()->route('fournisseurs.index')
                ->with('success', 'Fournisseur supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du fournisseur.');
        }
    }

    /**
     * Détails du fournisseur : achats liés
     */
    public function detail(Fournisseur $fournisseur)
    {
        $achats = $fournisseur->achats()->with('lignes')->get();

        return view('fournisseurs.detail', compact('fournisseur', 'achats'));
    }
}
