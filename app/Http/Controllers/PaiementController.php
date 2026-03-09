<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Facture;
use App\Http\Requests\PaiementRequest;
use Illuminate\Http\Requests;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::with('facture.client')->latest()->get();
        return view('paiements.index', compact('paiements'));
    }

    public function create()
    {
        $factures = Facture::with('client')->get();
        return view('paiements.create', compact('factures'));
    }

    public function store(PaiementRequest $request)
    {
        try {
            $paiement = Paiement::create($request->validated());

            // Met à jour le solde de la facture
            $facture = Facture::find($request->facture_id);
            $facture->solde = $facture->solde - $request->montant;
            $facture->save();

            return redirect()->route('paiements.index')
                ->with('success', 'Paiement enregistré avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l’enregistrement du paiement : ' . $e->getMessage());
        }
    }

    public function edit(Paiement $paiement)
    {
        $factures = Facture::with('client')->get();
        return view('paiements.edit', compact('paiement', 'factures'));
    }

    public function update(PaiementRequest $request, Paiement $paiement)
    {
        try {
            // Restaure le solde de l'ancienne facture
            $ancienneFacture = Facture::find($paiement->facture_id);
            $ancienneFacture->solde += $paiement->montant;
            $ancienneFacture->save();

            $paiement->update($request->validated());

            // Déduit le montant de la nouvelle facture
            $facture = Facture::find($request->facture_id);
            $facture->solde -= $request->montant;
            $facture->save();

            return redirect()->route('paiements.index')
                ->with('success', 'Paiement mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du paiement.');
        }
    }

    public function destroy(Paiement $paiement)
    {
        try {
            // Restaure le solde de la facture
            $facture = Facture::find($paiement->facture_id);
            $facture->solde += $paiement->montant;
            $facture->save();

            $paiement->delete();

            return redirect()->route('paiements.index')
                ->with('success', 'Paiement supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du paiement.');
        }
    }
}
