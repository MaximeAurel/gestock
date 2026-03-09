<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Fournisseur;
use App\Http\Requests\AchatRequest;
use App\Services\AchatService;
use Illuminate\Http\Requests;

class AchatController extends Controller
{
    protected AchatService $achatService;

    public function __construct(AchatService $achatService)
    {
        $this->achatService = $achatService;
    }

    public function index()
    {
        $achats = Achat::with('fournisseur', 'lignes.produit')->latest()->get();
        return view('achats.index', compact('achats'));
    }

    public function create()
    {
        $fournisseurs = Fournisseur::all();
        return view('achats.create', compact('fournisseurs'));
    }

    public function store(AchatRequest $request)
    {
        try {
            $achat = $this->achatService->creer($request->validated());

            return redirect()->route('achats.index')
                ->with('success', 'Achat créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l’achat : ' . $e->getMessage());
        }
    }

    public function edit(Achat $achat)
    {
        $fournisseurs = Fournisseur::all();
        return view('achats.edit', compact('achat', 'fournisseurs'));
    }

    public function update(AchatRequest $request, Achat $achat)
    {
        try {
            $this->achatService->mettreAJour($achat->id, $request->validated());

            return redirect()->route('achats.index')
                ->with('success', 'Achat mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de l’achat.');
        }
    }

    public function annuler(Achat $achat)
    {
        try {
            $this->achatService->annuler($achat->id);

            return redirect()->route('achats.index')
                ->with('success', 'Achat annulé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l’annulation de l’achat.');
        }
    }

    public function destroy(Achat $achat)
    {
        try {
            $this->achatService->supprimer($achat->id);

            return redirect()->route('achats.index')
                ->with('success', 'Achat supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l’achat.');
        }
    }
}

