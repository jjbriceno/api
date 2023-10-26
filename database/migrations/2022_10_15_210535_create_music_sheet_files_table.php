<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicSheetFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_sheet_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->nullable()->comment('Nombre del archivo');
            $table->string('file_format')->nullable('Formato del arhivo');
            $table->binary('binary_file')->nullable();
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
        Schema::dropIfExists('music_sheet_files');
    }
}
