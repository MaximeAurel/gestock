<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProduitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $produitId = $this->route('produit') ?? null;

        return [
            'nom' => 'required|string|max:100|unique:produits,nom,' . $produitId,
            'categorie_id' => 'required|exists:categories,id',
            'unite_id' => 'required|exists:unites,id',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'stock_min' => 'required|integer|min:0'
        ];
    }
}
