<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veterinaria_id')->constrained('veterinarias')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('nombre');
            $table->enum('categoria', ['salud', 'estetica', 'cirugia', 'bienestar']);
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('duracion_estimada')->nullable();
            $table->decimal('precio', 10, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['veterinaria_id', 'tenant_id']);
            $table->index(['categoria', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
