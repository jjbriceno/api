<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userId'            => ['required'],
            'delivery_date'     => ['required'],
            'items'             => ['required'],   
        ];
    }

    public function messages()
    {
        return [
            'userId'            => 'El prestatario es obligatorio.',
            'delivery_date'     => 'La fecha de entrega es obligatoria.',
            'items'             => 'Las partirturas son obligatorias.',
        ];
    }
}
