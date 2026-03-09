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
            'type' => 'required|in:entree,sortie',
            'motif' => 'nullable|string|max:255'
        ];
    }
}
