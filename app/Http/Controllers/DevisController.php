<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Parametre;
use App\Http\Requests\DevisRequest;
use App\Services\DevisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $clients = Client::all();
        $produits = Produit::select('id','designation','prix_vente')->orderBy('designation')->get();
        return view('devis.index', compact('devis','clients','produits'));
    }

    public function create()
    {
        $clients = Client::all();
        $produits = Produit::select('id','designation','prix_vente')->orderBy('designation')->get();
        return view('devis.create', compact('clients','produits'));
    }

    public function store(DevisRequest $request)
    {
        try {
            $this->devisService->creer($request->validated());
            return redirect()->route('devis.index')
                ->with('success', 'Devis créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du devis : ' . $e->getMessage());
        }
    }

    public function edit(Devis $devi)
    {
        $devi->load('lignes.produit');
        $clients = Client::all();
        $produits = Produit::select('id','designation','prix_vente')->orderBy('designation')->get();
        $lignes = $devi->lignes->map(function ($l) {
            return [
                'produit_id' => $l->produit_id,
                'quantite' => $l->quantite,
                'prix_unitaire' => $l->prix_unitaire,
                'tva' => $l->tva,
            ];
        });
        return view('devis.edit', compact('devi', 'clients', 'produits', 'lignes'));
    }

    public function update(DevisRequest $request, Devis $devi)
    {
        try {
            $this->devisService->mettreAJour($devi, $request->validated());
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

    public function show(Devis $devi)
    {
        $devi->load('lignes.produit', 'client');
        return view('devis.show', compact('devi'));
    }

    public function detail(Devis $devi)
    {
        $devi->load('lignes.produit', 'client');
        return view('devis.detail', compact('devi'));
    }

    public function exportPdf(Devis $devi)
    {
        $devi->load('client', 'lignes.produit');
        $settings = $this->settings();
        $html = view('devis.export', compact('devi','settings'))->render();
        if (class_exists(Pdf::class)) {
            return Pdf::loadHTML($html)->download('devis-'.$devi->id.'.pdf');
        }
        return Response::make($html, 200, ['Content-Type' => 'text/html']);
    }

    public function exportExcel(Devis $devi)
    {
        $devi->load('lignes.produit');
        $settings = $this->settings();
        $html = view('devis.export_excel', compact('devi','settings'))->render();
        return Response::make($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="devis-'.$devi->id.'.xls"'
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
