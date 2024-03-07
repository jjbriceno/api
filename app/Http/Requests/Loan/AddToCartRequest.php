<?php

namespace App\Http\Requests\Loan;

use App\Models\MusicSheet;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return MusicSheet::lockForUpdate()->find($this->musicSheetId)->available ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'musicSheetId'  => ['required'],
            'cuantity'      => ['required', 'numeric', 'lte:' . MusicSheet::lockForUpdate()->find($this->musicSheetId)->available],
        ];
    }

    public function messages()
    {
        return [
            'musicSheetId.required'     => 'La pista es obligatoria.',
            'cuantity.required'         => 'La cantidad es obligatoria.',
            'cuantity.numeric'          => 'La cantidad debe ser un número.',
            'cuantity.lte'              => 'La cantidad no puede ser mayor que la cantidad disponible. Disponibles: ' . MusicSheet::lockForUpdate()->find($this->musicSheetId)->available,
        ];
    }
}
