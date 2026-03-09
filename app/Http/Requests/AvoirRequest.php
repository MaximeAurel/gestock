<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvoirRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'facture_id' => 'required|exists:factures,id',
            'montant' => 'required|numeric|min:0.01',
            'motif' => 'nullable|string|max:255',
            'date_avoir' => 'required|date'
        ];
    }
}
