<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PixelStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'grid_id' => ['required','string','max:100','unique:pixels,grid_id'],
            'region'  => ['required','string','max:150'],
        ];
    }
}
