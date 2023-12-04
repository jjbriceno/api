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
            $table->string('title')->nullable()->comment('Título de la partitura');
            $table->integer('cuantity')->default(1)->comment('Cantidad de partituras existentes');
            $table->integer('available')->default(1)->comment('Cantidad de partituras disponibles');
            $table->foreignId('author_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('gender_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('location_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('music_sheet_file_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
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
