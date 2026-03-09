<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user') ?? null;

        return [
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $userId,
            'role_id' => 'required|exists:roles,id',
            'mot_de_passe' => $this->isMethod('post') ? 'required|string|min:6' : 'nullable|string|min:6'
        ];
    }
}
