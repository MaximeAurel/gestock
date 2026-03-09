<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UniteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $uniteId = $this->route('unite') ?? null;

        return [
            'nom' => 'required|string|max:50|unique:unites,nom,' . $uniteId,
            'abbreviation' => 'required|string|max:10|unique:unites,abbreviation,' . $uniteId
        ];
    }
}
