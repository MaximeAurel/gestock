<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Requests;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(ClientRequest $request)
    {
        try {
            Client::create($request->validated());

            return redirect()->route('clients.index')
                ->with('success', 'Client créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du client.');
        }
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        try {
            $client->update($request->validated());

            return redirect()->route('clients.index')
                ->with('success', 'Client mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du client.');
        }
    }

    public function destroy(Client $client)
    {
        try {
            $client->delete();

            return redirect()->route('clients.index')
                ->with('success', 'Client supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du client.');
        }
    }

    /**
     * Affichage des détails d’un client : factures, paiements, devis
     */
    public function detail(Client $client)
    {
        $factures = $client->factures()->with('lignes')->get();
        $paiements = $client->paiements()->get();
        $devis = $client->devis()->with('lignes')->get();

        return view('clients.detail', compact('client', 'factures', 'paiements', 'devis'));
    }
}
