<?php

namespace App\Http\Controllers;

use App\Models\Avoir;
use App\Models\Facture;
use App\Http\Requests\AvoirRequest;
use Illuminate\Support\Facades\Auth;

class AvoirController extends Controller
{
    public function index()
    {
        $avoirs = Avoir::with('facture.client')->latest()->get();
        $factures = Facture::with('client')->orderByDesc('created_at')->get();
        return view('avoirs.index', compact('avoirs', 'factures'));
    }

    public function create()
    {
        $factures = Facture::with('client')->get();
        return view('avoirs.create', compact('factures'));
    }

    public function store(AvoirRequest $request)
    {
        try {
            $validated = $request->validated();

            if (empty($validated['numero'])) {
                $lastId = Avoir::max('id') ?? 0;
                $validated['numero'] = 'AV-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
            }

            $facture = Facture::findOrFail($validated['facture_id']);
            $isAdmin = in_array(strtolower(Auth::user()->role->nom ?? ''), ['admin', 'administrateur'], true);
            if (!$isAdmin) {
                $validated['montant'] = $facture->total_ttc ?? 0;
            }

            $avoir = Avoir::create($validated);

            // Met à jour le solde de la facture
            $facture->solde -= $validated['montant'];
            $facture->save();

            return redirect()->route('avoirs.index')
                ->with('success', 'Avoir créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l’avoir : ' . $e->getMessage());
        }
    }

    public function edit(Avoir $avoir)
    {
        $factures = Facture::with('client')->get();
        return view('avoirs.edit', compact('avoir', 'factures'));
    }

    public function update(AvoirRequest $request, Avoir $avoir)
    {
        try {
            // Restaure le solde de l’ancienne facture
            $ancienneFacture = Facture::find($avoir->facture_id);
            $ancienneFacture->solde += $avoir->montant;
            $ancienneFacture->save();

            $validated = $request->validated();
            if (empty($validated['numero'])) {
                $validated['numero'] = $avoir->numero;
            }

            $facture = Facture::findOrFail($validated['facture_id']);
            $isAdmin = in_array(strtolower(Auth::user()->role->nom ?? ''), ['admin', 'administrateur'], true);
            if (!$isAdmin) {
                $validated['montant'] = $facture->total_ttc ?? 0;
            }

            $avoir->update($validated);

            // Met à jour le solde de la nouvelle facture
            $facture->solde -= $validated['montant'];
            $facture->save();

            return redirect()->route('avoirs.index')
                ->with('success', 'Avoir mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de l’avoir.');
        }
    }

    public function destroy(Avoir $avoir)
    {
        try {
            // Restaure le solde de la facture
            $facture = Facture::find($avoir->facture_id);
            $facture->solde += $avoir->montant;
            $facture->save();

            $avoir->delete();

            return redirect()->route('avoirs.index')
                ->with('success', 'Avoir supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l’avoir.');
        }
    }
}
