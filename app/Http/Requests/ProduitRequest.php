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
        $produit = $this->route('produit');
        $produitId = is_object($produit) ? $produit->id : $produit;

        return [
            'designation'   => 'required|string|max:150|unique:produits,designation,' . $produitId,
            'code_barre'    => 'nullable|string|max:100|unique:produits,code_barre,' . $produitId,
            'categorie_id'  => 'required|exists:categories,id',
            'unite_id'      => 'nullable|exists:unites,id',
            'prix_vente'    => 'required|numeric|min:0',
            'stock_min'     => 'required|integer|min:1',
            'quantite_initiale' => 'nullable|integer|min:0',
            'description'   => 'nullable|string',
        ];
    }
}
