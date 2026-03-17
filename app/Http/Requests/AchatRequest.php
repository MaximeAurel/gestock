<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AchatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // On autorise toutes les requêtes pour le moment
    }

    public function rules(): array
    {
        $achat = $this->route('achat');
        $achatId = $achat ? $achat->id : null;

        $isCreate = $this->isMethod('post');

        $rules = [
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'date_achat'     => 'required|date',
            'numero'         => 'nullable|string|max:50|unique:achats,numero,' . $achatId,
        ];

        // Lignes obligatoires à la création, optionnelles en mise à jour
        $rules['lignes'] = $isCreate ? 'required|array|min:1' : 'nullable|array|min:1';
        $rules['lignes.*.produit_id']    = $isCreate ? 'required|exists:produits,id' : 'sometimes|exists:produits,id';
        $rules['lignes.*.quantite']      = $isCreate ? 'required|integer|min:1' : 'sometimes|integer|min:1';
        $rules['lignes.*.prix_unitaire'] = $isCreate ? 'required|numeric|min:0' : 'sometimes|numeric|min:0';
        $rules['lignes.*.tva']           = 'nullable|numeric|min:0';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'fournisseur_id.required' => 'Veuillez sélectionner un fournisseur.',
            'fournisseur_id.exists'   => 'Le fournisseur sélectionné est invalide.',
            'date_achat.required'     => 'La date d\'achat est obligatoire.',
            'date_achat.date'         => 'La date d\'achat n\'est pas valide.',
            'lignes.required'         => 'Vous devez ajouter au moins une ligne.',
            'lignes.array'            => 'Les lignes doivent être un tableau valide.',
            'lignes.*.produit_id.required' => 'Veuillez sélectionner un produit pour chaque ligne.',
            'lignes.*.produit_id.exists'   => 'Le produit sélectionné n\'existe pas.',
            'lignes.*.quantite.required'   => 'La quantité est obligatoire pour chaque ligne.',
            'lignes.*.quantite.integer'    => 'La quantité doit être un nombre entier.',
            'lignes.*.quantite.min'        => 'La quantité doit être au moins 1.',
            'lignes.*.prix_unitaire.required' => 'Le prix unitaire est obligatoire pour chaque ligne.',
            'lignes.*.prix_unitaire.numeric'  => 'Le prix unitaire doit être un nombre.',
            'lignes.*.prix_unitaire.min'      => 'Le prix unitaire doit être supérieur ou égal à 0.',
            'lignes.*.tva.numeric'            => 'La TVA doit être un nombre.',
            'lignes.*.tva.min'                => 'La TVA doit être supérieure ou égale à 0.',
        ];
    }
}
