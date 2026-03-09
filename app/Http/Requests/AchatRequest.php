<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AchatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'date_achat' => 'required|date',
            'lignes' => 'required|array|min:1',
            'lignes.*.produit_id' => 'required|exists:produits,id',
            'lignes.*.quantite' => 'required|integer|min:1',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0'
        ];
    }
}
