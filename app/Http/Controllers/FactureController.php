<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Parametre;
use App\Http\Requests\FactureRequest;
use App\Services\FactureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $clients = Client::all();
        $produits = Produit::select('id','designation','prix_vente')->orderBy('designation')->get();
        return view('factures.index', compact('factures','clients','produits'));
    }

    public function create()
    {
        $clients = Client::all();
        $produits = Produit::select('id','designation','prix_vente')->orderBy('designation')->get();
        return view('factures.create', compact('clients','produits'));
    }

    public function store(FactureRequest $request)
    {
        try {
            $this->factureService->creer($request->validated());

            return redirect()->route('factures.index')
                ->with('success', 'Facture créée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de la facture : ' . $e->getMessage());
        }
    }

    public function edit(Facture $facture)
    {
        $facture->load('lignes.produit');
        $clients = Client::all();
        $produits = Produit::select('id','designation','prix_vente')->orderBy('designation')->get();
        $lignes = $facture->lignes->map(function ($l) {
            return [
                'produit_id' => $l->produit_id,
                'quantite' => $l->quantite,
                'prix_unitaire' => $l->prix,
                'tva' => $l->tva,
            ];
        });
        return view('factures.edit', compact('facture', 'clients', 'produits', 'lignes'));
    }

    public function update(FactureRequest $request, Facture $facture)
    {
        try {
            $this->factureService->mettreAJour($facture, $request->validated());

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

    public function show(Facture $facture)
    {
        $facture->load('client', 'lignes.produit', 'paiements');
        return view('factures.show', compact('facture'));
    }

    public function detail(Facture $facture)
    {
        $facture->load('lignes.produit', 'client');
        return view('factures.detail', compact('facture'));
    }

    public function exportPdf(Facture $facture)
    {
        $facture->load('client', 'lignes.produit');
        $settings = $this->settings();
        $html = view('factures.export', compact('facture','settings'))->render();
        if (class_exists(Pdf::class)) {
            return Pdf::loadHTML($html)->download('facture-'.$facture->id.'.pdf');
        }
        return Response::make($html, 200, ['Content-Type' => 'text/html']);
    }

    public function exportExcel(Facture $facture)
    {
        $facture->load('lignes.produit');
        $settings = $this->settings();
        $html = view('factures.export_excel', compact('facture','settings'))->render();
        return Response::make($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="facture-'.$facture->id.'.xls"'
        ]);
    }

    private function settings(): array
    {
        $defaults = [
            'nom_entreprise' => config('app.name', 'Gestock'),
            'devise' => 'FCFA',
            'tva' => 18,
            'logo' => null,
        ];
        $stored = Parametre::whereIn('cle', array_keys($defaults))
            ->pluck('valeur', 'cle')
            ->toArray();
        return array_merge($defaults, $stored);
    }
}
