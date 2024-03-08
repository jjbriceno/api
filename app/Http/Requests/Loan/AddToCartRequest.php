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
            'quantity'      => ['required', 'numeric', 'lte:' . MusicSheet::lockForUpdate()->find($this->musicSheetId)->available],
        ];
    }

    public function messages()
    {
        return [
            'musicSheetId.required'     => 'La partitura es obligatoria.',
            'quantity.required'         => 'La cantidad es obligatoria.',
            'quantity.numeric'          => 'La cantidad debe ser un número.',
            'quantity.lte'              => 'La cantidad no puede ser mayor que la cantidad disponible. Disponibles: ' . MusicSheet::lockForUpdate()->find($this->musicSheetId)->available,
        ];
    }
}
