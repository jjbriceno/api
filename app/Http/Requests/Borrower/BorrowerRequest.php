<?php

namespace App\Http\Requests\Borrower;

use Illuminate\Foundation\Http\FormRequest;

class BorrowerRequest extends FormRequest
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
            'id'        => ['required'],
            'firstName' => ['required'],
            'lastName'  => ['required'],
            'email'     => ['required', $this->id ? 'unique:user,email,' . $this->id : 'unique:user,email,'],
            'phone'     => ['required', $this->id ? 'unique:profile,phone,' . $this->id : 'unique:profile,phone,'],
            'address'   => ['nullable']
        ];
    }

    public function messages(): array
    {
        return [
            'firstName.required' => 'El nombre es requerido',
            'lastName.required'  => 'El apellido es requerido',
            'email.required'     => 'El correo electrónico es requerido',
            'email.unique'       => 'Este correo electrónico ya se encuentra registrado',
            'phone.required'     => 'El teléfono es requerido',
            'phone.unique'       => 'Este númeto de teléfono ya se encuentra registrado',
        ];
    }
}
