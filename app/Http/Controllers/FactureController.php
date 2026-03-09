<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Client;
use App\Http\Requests\FactureRequest;
use App\Services\FactureService;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    protected FactureService $factureService;

    public function __construct(FactureService $factureService)
    {
        $this->factureService = $factureService;
    }

    public function index()
    {
        $factures = Facture::with(['client', 'lignes.produit'])->latest()->get();
        return view('factures.index', compact('factures'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('factures.create', compact('clients'));
    }

    public function store(FactureRequest $request)
    {
        try {
            $facture = $this->factureService->creer($request->validated());

            return redirect()->route('factures.index')
                ->with('success', 'Facture créée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de la facture : ' . $e->getMessage());
        }
    }

    public function edit(Facture $facture)
    {
        $clients = Client::all();
        return view('factures.edit', compact('facture', 'clients'));
    }

    public function update(FactureRequest $request, Facture $facture)
    {
        try {
            $this->factureService->update($facture->id, $request->validated());

            return redirect()->route('factures.index')
                ->with('success', 'Facture mise à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de la facture.');
        }
    }

    public function payer(Request $request, Facture $facture)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0'
        ]);

        try {
            $this->factureService->payer($facture->id, $request->montant, 'manual');

            return redirect()->route('factures.index')
                ->with('success', 'Paiement enregistré avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l’enregistrement du paiement.');
        }
    }

    public function annuler(Facture $facture)
    {
        try {
            $this->factureService->annuler($facture->id);

            return redirect()->route('factures.index')
                ->with('success', 'Facture annulée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l’annulation de la facture.');
        }
    }

    public function destroy(Facture $facture)
    {
        try {
            $this->factureService->supprimer($facture->id);

            return redirect()->route('factures.index')
                ->with('success', 'Facture supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de la facture.');
        }
    }

    public function detail(Facture $facture)
    {
        $facture->load('lignes.produit', 'client');
        return view('factures.detail', compact('facture'));
    }
}
