<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('veterinario_id');
            $table->timestamp('confirmed_at')->nullable()->after('estado');
            $table->timestamp('canceled_at')->nullable()->after('confirmed_at');
            $table->timestamp('completed_at')->nullable()->after('canceled_at');
            $table->timestamp('reminder_sent_at')->nullable()->after('completed_at');
        });

        DB::statement("UPDATE citas SET estado = 'finalizada' WHERE estado = 'atendida'");
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE citas MODIFY estado ENUM('pendiente','confirmada','cancelada','finalizada') DEFAULT 'pendiente'");
            DB::statement("ALTER TABLE citas MODIFY mascota_id BIGINT UNSIGNED NULL");
        }
    }

    public function down(): void
    {
        DB::statement("UPDATE citas SET estado = 'atendida' WHERE estado = 'finalizada'");
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE citas MODIFY estado ENUM('pendiente','atendida','cancelada') DEFAULT 'pendiente'");
            DB::statement("ALTER TABLE citas MODIFY mascota_id BIGINT UNSIGNED NOT NULL");
        }

        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'confirmed_at', 'canceled_at', 'completed_at', 'reminder_sent_at']);
        });
    }
};
