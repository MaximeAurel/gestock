<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
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
            'mode_paiement' => 'required|string|max:50',
            'date_paiement' => 'required|date',
            'reference' => 'required_if:mode_paiement,Cheque,Airtel Money,Moov Money|nullable|string|max:100',
        ];
    }
}
