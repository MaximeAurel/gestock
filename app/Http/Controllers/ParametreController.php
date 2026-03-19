<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parametre;
use Illuminate\Support\Facades\Storage;

class ParametreController extends Controller
{
    /**
     * Affiche la page principale des paramètres
     */
    public function index()
    {
        $defaults = [
            'nom_entreprise' => config('app.name', 'Gestock'),
            'devise' => 'XAF',
            'tva' => 18,
            'logo' => null,
        ];

        $stored = Parametre::whereIn('cle', array_keys($defaults))
            ->pluck('valeur', 'cle')
            ->toArray();

        $parametres = array_merge($defaults, $stored);

        return view('parametres.index', compact('parametres'));
    }

    /**
     * Met à jour les paramètres généraux
     */
    public function update(Request $request)
    {
        $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'devise' => 'required|string|max:10',
            'tva' => 'required|numeric|min:0|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['nom_entreprise', 'devise', 'tva']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = Storage::url($path);
        }

        foreach ($data as $key => $value) {
            Parametre::updateOrCreate(['cle' => $key], ['valeur' => $value]);
        }

        return redirect()->route('parametres.index')
            ->with('success', 'Paramètres mis à jour avec succès !');
    }
}
