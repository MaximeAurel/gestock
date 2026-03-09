<?php

namespace App\Http\Controllers;

use App\Models\Unite;
use App\Http\Requests\UniteRequest;
use Illuminate\Http\Requests;

class UniteController extends Controller
{
    public function index()
    {
        $unites = Unite::all();
        return view('unites.index', compact('unites'));
    }

    public function create()
    {
        return view('unites.create');
    }

    public function store(UniteRequest $request)
    {
        try {
            Unite::create($request->validated());

            return redirect()->route('unites.index')
                ->with('success', 'Unité créée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l’unité.');
        }
    }

    public function edit(Unite $unite)
    {
        return view('unites.edit', compact('unite'));
    }

    public function update(UniteRequest $request, Unite $unite)
    {
        try {
            $unite->update($request->validated());

            return redirect()->route('unites.index')
                ->with('success', 'Unité mise à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de l’unité.');
        }
    }

    public function destroy(Unite $unite)
    {
        try {
            $unite->delete();

            return redirect()->route('unites.index')
                ->with('success', 'Unité supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l’unité.');
        }
    }
}
