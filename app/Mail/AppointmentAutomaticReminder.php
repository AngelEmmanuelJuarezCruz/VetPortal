<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentAutomaticReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Cita $appointment;
    public int $daysUntil;

    public function __construct(Cita $appointment, int $daysUntil)
    {
        $this->appointment = $appointment;
        $this->daysUntil = $daysUntil;
    }

    public function build()
    {
        return $this->subject('Recordatorio automático: Tu cita es en ' . $this->daysUntil . ' días')
            ->view('emails.appointments.automatic-reminder')
            ->with([
                'appointment' => $this->appointment,
                'daysUntil' => $this->daysUntil
            ]);
    }
}
