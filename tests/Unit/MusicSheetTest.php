<?php

namespace Tests\Unit;

use App\Http\Controllers\MusicSheetController;
use App\Http\Requests\MusicSheet\MusicSheetRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Validator as ValidationValidator;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator as RespectValidator;
use Respect\Validation\Rules\All;
class MusicSheetTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();
    }

    private function createAttributes()
    {
        // $faker = \Faker\Factory::create();

        $attributes = [
            'title' => 'Test Title',
            'authorId' => 1,
            'genderId' => 1,
            'cuantity' => 10,
            'cabinetId' => 1,
            'drawerId' => 1,
        ];

        return $attributes;
    }

    // Ejemplo de prueba unitaria para el almacenamiento exitoso
    public function test_store_method_creates_new_music_sheet_instance()
    {
        $attributes = $this->createAttributes();
        $musicSheetRequest = new MusicSheetRequest();
        $rules = $musicSheetRequest->rules();
        $messeges = $musicSheetRequest->messages();

        dd(\Illuminate\Support\Facades\Validator::make($attributes, $rules));
        // $validator = \Illuminate\Support\Facades\Validator::make($attributes, $rules);
        //$passes = $validator->passes();
        // $this->assertTrue($passes);
        // $request = new Request($attributes);

        // $validator = $request->validate($rules);
        // dd($validator);
        // $passes = $validator->validate($attributes);
        // $this->assertEquals(true, $passes);
    }
}
