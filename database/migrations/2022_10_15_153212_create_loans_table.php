<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->enum('status', ['abierto', 'cerrado'])->nullable()->comment('Estado de la entrega');
            $table->date('loan_date')->nullable()->comment('Fecha de préstamo');
            $table->date('delivery_date')->nullable()->comment('Fecha de entrega');
            $table->jsonb('music_sheets_borrowed_amount')->comment('Objeto de pares de {id, cantidad} de partituras prestadas');
            $table->integer('cuantity')->comment('Cantidad de partituras prestadas');
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
        Schema::dropIfExists('loans');
    }
}
