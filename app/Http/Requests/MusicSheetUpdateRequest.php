<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicSheetUpdateRequest extends FormRequest
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
            'title'             => ['required', 'unique_with:music_sheets, authorId = author_id,' . $this->id],
            'authorId'          => ['required'],
            'genderId'          => ['required'],
            'drawerId'          => ['required'],
            'cabinetId'         => ['required'],
            'cuantity'          => ['required'],
            'file'              => ['sometimes', 'required', 'mimes:jpeg,png,pdf', 'max:5148'],
        ];
    }

    public function messages()
    {
        return [
            'title.required'            => "El 'Título' es obligatorio",
            'title.unique_with'         => "Este Título ya ha sido registrado con este autor",
            'authorId.required'         => "El 'Autor' es obligatorio",
            'genderId.required'         => "El 'Género musical' es obligatorio",
            'drawerId.required'         => "El 'Estante' es obligatorio",
            'cabinetId.required'        => "La 'Gaveta' es obligatoria",
            'cuantity.required'         => "La 'Cantidad de partiruras' debe ser de al menos uno",
            'file.required'             => "El Archivo es obligatorio",
            'file.mimes'                => "Sólo se aceptan los formatos de archivo jpeg, png o pdf",
            'file.required'             => "El tamaño maximo del archivo es de 2 MB",
        ];
    }
}
