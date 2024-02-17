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
            'musicSheetId'  => ['required'],
            'title'         => ['required'],
            'authorId'      => ['required'],
            'genderId'      => ['required'],
            'locationId'    => ['required'],
            'drawerId'      => ['required'],
            'cabinetId'     => ['required'],
            'available'     => ['required'],
            'cuantity'      => ['required', 'numeric', 'lte:' . $this->available],
            'borrowerId'    => ['required'],
            'deliveryDate'  => ['required', 'date', 'after:today'],
        ];
    }

    public function messages()
    {
        return [
            'title.required'            => 'El campo título es obligatorio',
            'authorId.required'         => 'El campo autor es obligatorio',
            'genderId.required'         => 'El campo género es obligatorio',
            'locationId.required'       => 'El campo ubicación es obligatorio',
            'drawerId.required'         => 'El campo gaveta es obligatorio',
            'cabinetId.required'        => 'El campo estante es obligatorio',
            'available.required'        => 'El campo disponible es obligatorio',
            'cuantity.required'         => 'El campo cantidad es obligatorio',
            'cuantity.numeric'          => 'El campo cantidad debe ser un valor numérico',
            'cuantity.lte'              => 'El campo cantidad debe ser menor o igual al la cantidad de partituras disponibles',
            'borrowerId.required'       => 'El campo prestatario es obligatorio',
            'deliveryDate.required'     => 'El campo fecha de entrega es obligatorio',
            'deliveryDate.after'        => 'La fecha de entrega debe ser posterior a la fecha actual',
        ];
    }
}
