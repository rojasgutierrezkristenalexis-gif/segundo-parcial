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
        Schema::create('privilegios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('rol_privilegio', function (Blueprint $table) {
            $table->foreignId('rol_id')->constrained('rols')->cascadeOnDelete();
            $table->foreignId('privilegio_id')->constrained('privilegios')->cascadeOnDelete();
            $table->primary(['rol_id', 'privilegio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_privilegio');
        Schema::dropIfExists('privilegios');
    }
};
