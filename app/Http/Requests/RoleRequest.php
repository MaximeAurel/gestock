<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $roleId = $this->route('role') ?? null; // pour update

        return [
            'nom' => 'required|string|max:50|unique:roles,nom,' . $roleId,
            'description' => 'nullable|string|max:255'
        ];
    }
}
