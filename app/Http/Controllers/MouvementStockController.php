<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use Illuminate\Http\Request;

class MouvementStockController extends Controller
{

    /**
     * Afficher la liste des mouvements de stock
     */
    public function index()
    {
        $mouvements = MouvementStock::latest()->paginate(15);

        return view('mouvement-stocks.index', compact('mouvements'));
    }



    /**
     * Afficher le détail d'un mouvement
     */
    public function show(MouvementStock $mouvementStock)
    {
        return view('mouvement-stocks.show', compact('mouvementStock'));
    }



    /**
     * Ces méthodes sont volontairement désactivées
     * car les mouvements sont générés automatiquement
     * par les achats et les ventes
     */

    public function create()
    {
        abort(403);
    }

    public function store(Request $request)
    {
        abort(403);
    }

    public function edit(MouvementStock $mouvementStock)
    {
        abort(403);
    }

    public function update(Request $request, MouvementStock $mouvementStock)
    {
        abort(403);
    }

    public function destroy(MouvementStock $mouvementStock)
    {
        abort(403);
    }
}