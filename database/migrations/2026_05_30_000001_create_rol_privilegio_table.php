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
        Schema::create('rol_privilegio', function (Blueprint $table) {
            $table->foreignId('rol_id')->constrained('rols')->onDelete('cascade');
            $table->foreignId('privilegio_id')->constrained('privilegios')->onDelete('cascade');
            $table->primary(['rol_id', 'privilegio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_privilegio');
    }
};
