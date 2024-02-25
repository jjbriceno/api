<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'id'                        => ['required'],
            'userId'                    => ['required'],
            'firstName'                 => ['required'],
            'lastName'                  => ['required'],
            'email'                     => ['required' , $this->user_id ? 'unique:users,email,' . $this->user_id : 'unique:users,email'],
            'profilePicture'          => ['sometimes', 'required', 'mimes:jpeg,png', 'max:2048'],
        ];
    }

    public function messages()  {
        return [
            'userId.required'           => "El 'Usuario' es obligatorio",
            'firstName.required'        => "El 'Nombre' es obligatorio",
            'lastName.required'         => "El 'Apellido' es obligatorio",
            'email.required'            => "El 'Correo' es obligatorio",
            'email.unique'              => "Este 'Correo' ya ha sido registrado",
            'profilePicture.required'   => "La 'Imagen de perfil' es obligatoria",
            'profilePicture.mimes'      => "Sólo se aceptan los formatos de archivo jpeg o png",
            'profilePicture.max'        => "El tamaño maximo del archivo es de 2 MB",
        ];
    }
}
