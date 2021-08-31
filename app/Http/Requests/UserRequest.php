<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => [
                'string',
                'min:2',
            ],
            'name' => [
                'string',
                'min:1',
            ],
            'avatar' => [
                'image',
                'mimes:jpg,png,jpeg',
            ],
        ];
    }
}
