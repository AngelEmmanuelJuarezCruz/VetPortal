<?php

namespace App\Console\Commands;

use App\Mail\AppointmentAutomaticReminder;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders {days=3 : Días anticipados para enviar recordatorios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios automáticos a los clientes sobre sus citas próximas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysAhead = (int) $this->argument('days');
        
        $now = Carbon::now();
        $targetDate = $now->copy()->addDays($daysAhead)->toDateString();
        
        // Buscar citas pendientes de ese día específico
        $appointments = Cita::where('estado', 'pendiente')
            ->whereDate('fecha', $targetDate)
            ->with(['cliente', 'mascota', 'mascotas', 'veterinaria'])
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No hay citas pendientes para dentro de {$daysAhead} días.");
            return Command::SUCCESS;
        }

        $sent = 0;
        $failed = 0;

        foreach ($appointments as $appointment) {
            try {
                // Validar que el cliente tenga correo
                if (!$appointment->cliente || !$appointment->cliente->correo) {
                    $this->warn("Cita #{$appointment->id}: Cliente sin correo. Omitida.");
                    $failed++;
                    continue;
                }

                // Enviar el correo
                Mail::to($appointment->cliente->correo)
                    ->send(new AppointmentAutomaticReminder($appointment, $daysAhead));

                $this->info("✓ Recordatorio enviado a {$appointment->cliente->nombre} ({$appointment->cliente->correo})");
                $sent++;
            } catch (\Exception $e) {
                $this->error("✗ Error al enviar recordatorio para cita #{$appointment->id}: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->line("");
        $this->info("═════════════════════════════════════════════");
        $this->info("Recordatorios enviados: {$sent}");
        if ($failed > 0) {
            $this->warn("Recordatorios fallidos: {$failed}");
        }
        $this->info("═════════════════════════════════════════════");

        return Command::SUCCESS;
    }
}
