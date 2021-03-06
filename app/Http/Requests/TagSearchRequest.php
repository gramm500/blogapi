<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagSearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'q' => ['required', 'string', 'min:1'],
        ];
    }
}
