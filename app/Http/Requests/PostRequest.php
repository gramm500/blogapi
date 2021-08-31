<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'content' => ['required', 'string', 'min:2'],
            'tags' => ['array', 'max:10'],
            'tags.*' => [
                'integer',
                Rule::exists('tags', 'id'),
            ],
        ];
    }
}
