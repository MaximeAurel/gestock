<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $clientId = $this->route('client') ?? null;

        return [
            'nom' => 'required|string|max:100',
            'email' => 'nullable|email|unique:clients,email,' . $clientId,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:50',
            'pays' => 'nullable|string|max:50'
        ];
    }
}
