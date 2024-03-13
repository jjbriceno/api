<?php

namespace App\Http\Requests\Loan;

use App\Models\MusicSheet;
use Illuminate\Foundation\Http\FormRequest;

class updateCartItemRequest extends FormRequest
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
            'quantity' => ['required', 'numeric', 'gt:0', 'lte:' . MusicSheet::lockForUpdate()->find($this->id)->available + $this->session()->get('cart', [])[$this->id]['quantity']],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required'     => 'La cantidad es requerida',
            'quantity.numeric'      => 'La cantidad debe ser un número',
            'quantity.gt'           => 'La cantidad debe ser igual o mayor a 1',
            'quantity.lte'          => 'La cantidad no puede ser mayor que la cantidad disponible. Disponibles: ' . MusicSheet::lockForUpdate()->find($this->id)->available + $this->session()->get('cart', [])[$this->id]['quantity'],
        ];
    }
}
