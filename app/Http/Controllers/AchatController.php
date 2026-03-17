<?php

namespace App\Http\Controllers;

use App\Http\Requests\AchatRequest;
use App\Models\Achat;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Services\AchatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AchatController extends Controller
{
    protected AchatService $achatService;

    public function __construct(AchatService $achatService)
    {
        $this->achatService = $achatService;
    }

    // =============================
    // Liste des achats
    // =============================
    public function index(): View
    {
        $achats = Achat::with('fournisseur', 'lignes.produit')->latest()->get();
        $fournisseurs = Fournisseur::all();
        $produits = Produit::select('id','designation','prix_vente')->orderBy('designation')->get();

        return view('achats.index', compact('achats','fournisseurs','produits'));
    }

    // =============================
    // Formulaire de création (optionnel, on utilise le pop-up)
    // =============================
    public function create(): View
    {
        $fournisseurs = Fournisseur::all();
        return view('achats.create', compact('fournisseurs'));
    }

    // =============================
    // Enregistrement d'un achat
    // =============================
    public function store(AchatRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            // Génération automatique du numéro si non fourni
            if (empty($validated['numero'])) {
                $lastId = Achat::max('id') ?? 0;
                $validated['numero'] = 'ACH-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
            }

            $this->achatService->creer($validated);

            return redirect()->route('achats.index')
                ->with('success', 'Achat créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l\'achat : ' . $e->getMessage());
        }
    }

    // =============================
    // Formulaire édition
    // =============================
    public function edit(Achat $achat): View
    {
        $fournisseurs = Fournisseur::all();
        return view('achats.edit', compact('achat', 'fournisseurs'));
    }

    // =============================
    // Mise à jour d'un achat
    // =============================
    public function update(AchatRequest $request, Achat $achat): RedirectResponse
    {
        try {
            $validated = $request->validated();

            // Si le numéro est vide, on garde l'existant
            if (empty($validated['numero'])) {
                $validated['numero'] = $achat->numero;
            }

            $this->achatService->mettreAJour($achat->id, $validated);

            return redirect()->route('achats.index')
                ->with('success', 'Achat mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de l\'achat : ' . $e->getMessage());
        }
    }

    // =============================
    // Annulation d'un achat
    // =============================
    public function annuler(Achat $achat): RedirectResponse
    {
        try {
            $this->achatService->annuler($achat->id);

            return redirect()->route('achats.index')
                ->with('success', 'Achat annulé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'annulation de l\'achat : ' . $e->getMessage());
        }
    }

    // =============================
    // Suppression d'un achat
    // =============================
    public function destroy(Achat $achat): RedirectResponse
    {
        try {
            $this->achatService->supprimer($achat->id);

            return redirect()->route('achats.index')
                ->with('success', 'Achat supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l\'achat : ' . $e->getMessage());
        }
    }
}
