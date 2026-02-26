<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCanceled extends Mailable
{
    use Queueable, SerializesModels;

    public Cita $appointment;

    public function __construct(Cita $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('Cancelacion de cita')
            ->view('emails.appointments.canceled')
            ->with(['appointment' => $this->appointment]);
    }
}
