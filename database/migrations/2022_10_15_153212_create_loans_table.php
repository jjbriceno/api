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
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->enum('status', ['requested', 'open', 'closed'])->default('open')->nullable()->comment('Estado de la entrega');
            $table->date('loan_date')->nullable()->comment('Fecha de préstamo');
            $table->date('delivery_date')->nullable()->comment('Fecha de entrega');
            $table->integer('quantity')->nullable()->comment('Cantidad de partituras prestadas');
            $table->enum('type', ['digital', 'physical'])->nullable()->comment('Tipo de préstamo [digital, físico]');
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
