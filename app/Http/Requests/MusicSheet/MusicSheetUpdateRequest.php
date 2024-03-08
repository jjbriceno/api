<?php

namespace App\Http\Requests\MusicSheet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\MusicSheet;

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
            'title'             => ['required', Rule::unique('music_sheets', 'title')
                                                ->where('author_id', $this->authorId)->ignore($this->id)],
            'authorId'          => ['required'],
            'genderId'          => ['required'],
            'drawerId'          => ['required'],
            'cabinetId'         => ['required'],
            'quantity'          => ['required', 'min:1', 'gte:' . $this->getMinQuantityLoanedMusicSheets()],
            'file'              => ['sometimes', 'required', 'mimes:jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'title.required'            => "El 'Título' es obligatorio",
            'title.unique'              => "Este Título ya ha sido registrado con este autor",
            'authorId.required'         => "El 'Autor' es obligatorio",
            'genderId.required'         => "El 'Género musical' es obligatorio",
            'drawerId.required'         => "La 'Gaveta' es obligatorio",
            'cabinetId.required'        => "El 'Estante' es obligatoria",
            'quantity.required'         => "La 'Cantidad de partiruras' debe ser de al menos uno",
            'quantity.min'              => "La 'Cantidad de partiruras' debe ser de al menos uno",
            'quantity.gte'               => "La 'Cantidad de partiruras' debe ser mayor o igual a " . $this->getMinQuantityLoanedMusicSheets(),
            'file.required'             => "El Archivo es obligatorio",
            'file.mimes'                => "Sólo se aceptan los formatos de archivo jpeg, png o pdf",
            'file.max'                  => "El tamaño maximo del archivo es de 2 MB",
        ];
    }

    /**
     * Retrieve the minimum quantity of loaned music sheets.
     *
     * @return int
     */
    private function getMinQuantityLoanedMusicSheets(): int
    {
        $musicSheet = MusicSheet::lockForUpdate()->find($this->id);
        return $musicSheet->quantity - $musicSheet->available;
    }
}
