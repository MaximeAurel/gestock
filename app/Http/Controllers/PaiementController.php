<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Facture;
use App\Http\Requests\PaiementRequest;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::with('facture.client')->latest()->get();
        $factures = Facture::with('client')->orderByDesc('created_at')->get();
        return view('paiements.index', compact('paiements', 'factures'));
    }

    public function create()
    {
        $factures = Facture::with('client')->get();
        return view('paiements.create', compact('factures'));
    }

    public function store(PaiementRequest $request)
    {
        try {
            $data = $request->validated();
            $paiement = Paiement::create($data);

            // Mise à jour facture
            $facture = Facture::findOrFail($data['facture_id']);
            $facture->montant_paye = ($facture->montant_paye ?? 0) + $data['montant'];
            $facture->reste_a_payer = max(0, ($facture->reste_a_payer ?? $facture->total_ttc ?? 0) - $data['montant']);
            $facture->solde = $facture->reste_a_payer;
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
            $data = $request->validated();

            // Restaurer ancienne facture
            $oldFacture = Facture::find($paiement->facture_id);
            if ($oldFacture) {
                $oldFacture->montant_paye = max(0, ($oldFacture->montant_paye ?? 0) - $paiement->montant);
                $oldFacture->reste_a_payer = ($oldFacture->reste_a_payer ?? $oldFacture->total_ttc ?? 0) + $paiement->montant;
                $oldFacture->solde = $oldFacture->reste_a_payer;
                $oldFacture->save();
            }

            $paiement->update($data);

            // Appliquer sur la nouvelle facture
            $facture = Facture::findOrFail($data['facture_id']);
            $facture->montant_paye = ($facture->montant_paye ?? 0) + $data['montant'];
            $facture->reste_a_payer = max(0, ($facture->reste_a_payer ?? $facture->total_ttc ?? 0) - $data['montant']);
            $facture->solde = $facture->reste_a_payer;
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
            $facture = Facture::find($paiement->facture_id);
            if ($facture) {
                $facture->montant_paye = max(0, ($facture->montant_paye ?? 0) - $paiement->montant);
                $facture->reste_a_payer = ($facture->reste_a_payer ?? $facture->total_ttc ?? 0) + $paiement->montant;
                $facture->solde = $facture->reste_a_payer;
                $facture->save();
            }

            $paiement->delete();

            return redirect()->route('paiements.index')
                ->with('success', 'Paiement supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du paiement.');
        }
    }
}
