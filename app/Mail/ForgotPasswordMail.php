<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $temporaryPassword;
    public object $tenant;

    public function __construct(User $user, string $temporaryPassword, object $tenant)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
        $this->tenant = $tenant;
    }

    public function build()
    {
        return $this->subject('Tu contraseña temporal - VetPortal')
            ->view('emails.forgot-password')
            ->with([
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'tenant' => $this->tenant
            ]);
    }
}
