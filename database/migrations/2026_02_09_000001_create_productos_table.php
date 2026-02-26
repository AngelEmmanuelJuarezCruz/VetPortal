<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('veterinaria_id')->constrained('veterinarias')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('nombre');
            $table->enum('tipo', ['medicamento', 'articulo', 'alimento']);
            $table->string('categoria')->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('stock_actual')->default(0);
            $table->unsignedInteger('stock_minimo')->default(0);
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->string('proveedor')->nullable();
            $table->date('fecha_caducidad')->nullable();
            $table->timestamps();

            $table->index(['veterinaria_id', 'tenant_id']);
            $table->index(['tipo', 'categoria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
