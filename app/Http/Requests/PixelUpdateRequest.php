<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PixelUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'grid_id' => [
                'required','string','max:100',
                Rule::unique('pixels','grid_id')->ignore($this->route('pixel'))
            ],
            'region'  => ['required','string','max:150'],
        ];
    }
}
