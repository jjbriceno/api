<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\MusicSheet;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Testing\Fakes\BusFake;
use Tests\TestCase;

class MusicSheetTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    private function getuser()
    {
        return User::factory()->create();
    }

    private function getAuthenticated()
    {
        $user = $this->getuser();
        return $this->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);
    }

    /** @test 
     * Los campos requeridos no pueden ser nulos
    */
    public function required_fields_cannot_be_null()
    {
        $responseUser = $this->getAuthenticated();
        $this->assertAuthenticated();
        
        //Dat 
        $response = $this->postJson('/api/music-sheets', [
            'title' => null,
            'authorId' => null,
            'genderId' => null,
            'cabinetId' => null,
            'drawerId' => null,
            'cuantity' => null,
        ]);

        // Assert
        $response->assertStatus(422); // El código 422 es para validaciones fallidas

        $response->assertJsonValidationErrors([
            'title', 'authorId', 'genderId', 'cabinetId', 'drawerId', 'cuantity'
        ]);
    }

    /** @test 
     * Un título perteneciente a un autor
     * no debe estar repetido
    */
    public function title_for_same_author_cannot_be_repeated()
    {
        $responseUser = $this->getAuthenticated();
        $this->assertAuthenticated();

        /**
         * Preparación: Crear un autor y una partitura 
         * para ese con un título
         */
        $author = Author::find(1);
        MusicSheet::create([
            'title' => 'Partitura Existente',
            'author_id' => $author->id,
        ]);

        /** Act: Intentar crear una nueva partitura 
         * con el mismo título y autor*/
        $response = $this->postJson('/api/music-sheets', [
            'title' => 'Partitura Existente',
            'authorId' => $author->id,
            'genderId' => 1,
            'cabinetId' => 1,
            'drawerId' => 1,
            'cuantity' => 10,
        ]);

        /** Assert: Verificar que la respuesta indique
         * una falla de validación */
        $response->assertStatus(422);

        /**Verificar que el error de validación 
         * específico está presente en la respuesta JSON */ 
        $response->assertJsonValidationErrors(['title']);
    }

    /** @test 
     * En el sistema tienen que existir registros almacenados 
     * de género musical y autor de la partitura. 
    */
    public function system_must_have_records_of_music_gender_and_author()
    {
        $responseUser = $this->getAuthenticated();
        $this->assertAuthenticated();
        // Act: Intentar crear una nueva partitura sin género musical y autor existentes
        $response = $this->postJson('/api/music-sheets', [
            'title' => 'Nueva Partitura',
            // Supongamos que el ID dado para para género y autor 
            //no existe en la base de datos
            'authorId' => 500,
            'genderId' => 200,
            // Otros campos necesarios para pasar la validación
            'cabinetId' => 1,
            'drawerId' => 1,
            'cuantity' => 10,
        ]);

        // Assert: Verificar que la respuesta indique una falla en la busqueda
        $response->assertStatus(500);
    }

    // public function test_store_new_music_sheet()
    // {
    //     $responseUser = $this->getAuthenticated();
    //     $this->assertAuthenticated();

    //     $response = $this->post('api/music-sheets', [
    //         'title' => 'Test Title',
    //         'authorId' => 1,
    //         'genderId' => 1,
    //         'cuantity' => 10,
    //         'cabinetId' => 1,
    //         'drawerId' => 1,
    //     ]);

    //     $response->assertStatus(200);
    //     $response->assertJsonStructure(['item', 'message']);
    // }
}
