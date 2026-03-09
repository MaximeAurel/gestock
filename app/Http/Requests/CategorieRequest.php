<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategorieRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $categorieId = $this->route('categorie') ?? null;

        return [
            'nom' => 'required|string|max:100|unique:categories,nom,' . $categorieId,
            'description' => 'nullable|string|max:255'
        ];
    }
}
