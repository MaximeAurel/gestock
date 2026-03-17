<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MouvementStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            // Le type est déduit par l'action (entrée/sortie) côté contrôleur
            'type' => 'sometimes|in:entree,sortie',
            'motif' => 'nullable|string|max:255'
        ];
    }
}
