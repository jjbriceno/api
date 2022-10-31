<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_sheets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('author_id')->nullable()->constrained()->onUpdate('cascade');
            $table->foreignId('gender_id')->nullable()->constrained()->onUpdate('cascade');
            $table->foreignId('location_id')->nullable()->constrained()->onUpdate('cascade');
            $table->string('title')->nullable()->comment('Título de la partitura');
            $table->integer('cuantity')->comment('Cantidad de partituras existentes');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_sheets');
    }
}
