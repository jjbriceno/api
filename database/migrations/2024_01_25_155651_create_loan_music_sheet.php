<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanMusicSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_music_sheet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('music_sheet_id')->unsigned();
            $table->unsignedBiginteger('loan_id')->unsigned();
            
            $table->foreign('music_sheet_id')->references('id')
                ->on('music_sheets')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')
                ->on('loans')->onDelete('cascade');

            $table->integer('cuantity')->nullable()
                ->comment('Cantidad de partituras');
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
        Schema::dropIfExists('loan_music_sheet');
    }
}
