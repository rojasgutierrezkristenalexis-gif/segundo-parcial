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
    Schema::create('notas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('postulante_id');
        $table->unsignedBigInteger('materia_id');
        $table->decimal('nota', 5, 2);
        $table->timestamps();

        // Relaciones
        $table->foreign('postulante_id')->references('id')->on('postulantes')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
