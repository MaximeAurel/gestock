<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Client;
use App\Http\Requests\DevisRequest;
use App\Services\DevisService;
use Illuminate\Http\Requests;

class DevisController extends Controller
{
    protected DevisService $devisService;

    public function __construct(DevisService $devisService)
    {
        $this->devisService = $devisService;
    }

    public function index()
    {
        $devis = Devis::with(['client', 'lignes.produit'])->latest()->get();
        return view('devis.index', compact('devis'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('devis.create', compact('clients'));
    }

    public function store(DevisRequest $request)
    {
        try {
            $devis = $this->devisService->creer($request->validated());
            return redirect()->route('devis.index')
                ->with('success', 'Devis créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du devis : ' . $e->getMessage());
        }
    }

    public function edit(Devis $devi)
    {
        $clients = Client::all();
        return view('devis.edit', compact('devi', 'clients'));
    }

    public function update(DevisRequest $request, Devis $devi)
    {
        try {
            // On ne modifie que les lignes et totaux
            $this->devisService->creer($request->validated()); // ou adapter avec une fonction update si nécessaire
            return redirect()->route('devis.index')
                ->with('success', 'Devis mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du devis : ' . $e->getMessage());
        }
    }

    public function valider(Devis $devi)
    {
        try {
            $this->devisService->valider($devi);
            return redirect()->route('devis.index')
                ->with('success', 'Devis validé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function convertirEnFacture(Devis $devi)
    {
        try {
            $facture = $this->devisService->convertirEnFacture($devi);
            return redirect()->route('factures.detail', $facture->id)
                ->with('success', 'Devis converti en facture avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function annuler(Devis $devi)
    {
        try {
            $devi->update(['statut' => 'annule']);
            return redirect()->route('devis.index')
                ->with('success', 'Devis annulé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l’annulation du devis.');
        }
    }

    public function destroy(Devis $devi)
    {
        try {
            $devi->lignes()->delete();
            $devi->delete();
            return redirect()->route('devis.index')
                ->with('success', 'Devis supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du devis.');
        }
    }

    public function detail(Devis $devi)
    {
        $devi->load('lignes.produit', 'client');
        return view('devis.detail', compact('devi'));
    }
}
