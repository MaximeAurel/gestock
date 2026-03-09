<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParametreController extends Controller
{
    /**
     * Affiche la page principale des paramètres
     * 
     * Les paramètres sont généralement stockés dans la base ou un fichier config.
     */
    public function index()
    {
        // Exemple : récupérer des paramètres depuis un service ou config
        $parametres = [
            'nom_entreprise' => config('app.name', 'Gestock'),
            'devise' => 'XAF',
            'tva' => 18,
            'logo' => '/images/logo.png'
        ];

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

        // Exemple de mise à jour : ici tu peux soit sauvegarder en base, soit dans un fichier config
        // Si tu as une table 'parametres', tu pourrais faire :
        // foreach ($request->only(['nom_entreprise','devise','tva','logo']) as $key => $value) {
        //     Parametre::updateOrCreate(['key' => $key], ['value' => $value]);
        // }

        return redirect()->route('parametres.index')
            ->with('success', 'Paramètres mis à jour avec succès !');
    }
}