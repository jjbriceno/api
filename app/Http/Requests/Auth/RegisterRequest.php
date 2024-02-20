<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users', 'email:rfc,dns'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'         => 'El nombre es obligatorio',
            'name.max'              => 'El nombre no debe ser mayor a 255 caracteres',
            'last_name.required'    => 'El apellido es obligatorio',
            'last_name.max'         => 'El apellido no debe ser mayor a 255 caracteres',
            'email.required'        => 'El correo es obligatorio',
            'email.unique'          => 'El correo ya existe',
            'email.email'           => 'El correo no es válido',
            'password.required'     => 'La contraseña es obligatoria',
            'password.confirmed'    => 'La contraseña no coincide',
        ];
    }
}
