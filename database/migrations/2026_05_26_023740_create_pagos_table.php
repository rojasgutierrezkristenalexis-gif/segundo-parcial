<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('pagos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('postulante_id'); // De quién es el pago
        $table->decimal('monto', 8, 2);
        $table->string('nro_transaccion', 30)->unique();
        $table->string('estado', 20)->default('confirmado');
        $table->timestamps();

        // Relación física: Si se borra el postulante, se borra su pago
        $table->foreign('postulante_id')->references('id')->on('postulantes')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
