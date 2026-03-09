<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FournisseurRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $fournisseurId = $this->route('fournisseur') ?? null;

        return [
            'nom' => 'required|string|max:100',
            'email' => 'nullable|email|unique:fournisseurs,email,' . $fournisseurId,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:50',
            'pays' => 'nullable|string|max:50'
        ];
    }
}
