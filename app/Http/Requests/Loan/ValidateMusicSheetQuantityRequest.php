<?php

namespace App\Http\Requests\Loan;

use App\Models\MusicSheet;
use Illuminate\Foundation\Http\FormRequest;

class ValidateMusicSheetQuantityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'numeric', 'gt:0', 'lte:' . MusicSheet::lockForUpdate()->find($this->id)->available],
        ];
    }

    
}
