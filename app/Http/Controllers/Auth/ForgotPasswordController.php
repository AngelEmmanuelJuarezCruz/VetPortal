<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Veterinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password recovery email.
     */
    public function sendPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $validated['email'];

        // First, try to find user by email (most common case)
        $user = User::where('email', $email)->first();

        if ($user) {
            // Get tenant/veterinaria info
            $tenant = null;
            $tenantInfo = null;

            if ($user->tenant_id) {
                $tenant = Tenant::find($user->tenant_id);
                if ($tenant) {
                    $tenantInfo = (object)[
                        'nombre' => $tenant->name,
                        'id' => $tenant->id
                    ];
                }
            }

            if (!$tenantInfo) {
                return back()->withErrors([
                    'email' => 'Este usuario no está asociado a ninguna clínica veterinaria.'
                ])->onlyInput('email');
            }

            // Generate temporary password
            $temporaryPassword = Str::random(12);

            // Update user password
            $user->update([
                'password' => bcrypt($temporaryPassword)
            ]);

            // Send email
            try {
                Mail::to($user->email)->send(new ForgotPasswordMail($user, $temporaryPassword, $tenantInfo));
            } catch (\Exception $e) {
                \Log::error('Error sending forgot password email to ' . $user->email . ': ' . $e->getMessage());
                return back()->withErrors([
                    'email' => 'Error al enviar el correo. Por favor intenta más tarde.'
                ])->onlyInput('email');
            }

            return back()->with('success', 
                'Se envió tu contraseña temporal al correo <strong>' . $user->email . '</strong>. ' .
                'Por favor revisa tu correo electrónico (incluyendo la carpeta de spam). ' .
                'Tu contraseña temporal es solo para este acceso, te recomendamos cambiarla después de ingresar.'
            );
        }

        // If not found by user email, try to find by veterinaria/tenant email
        $tenant = Tenant::where('email', $email)->first();
        
        if (!$tenant) {
            $veterinaria = Veterinaria::where('correo', $email)->first();
            if (!$veterinaria) {
                return back()->withErrors([
                    'email' => 'No encontramos un usuario o veterinaria registrada con ese correo.'
                ])->onlyInput('email');
            }
            // Use veterinaria as tenant for naming purposes
            $tenantInfo = (object)[
                'nombre' => $veterinaria->nombre,
                'id' => null
            ];
        } else {
            $tenantInfo = (object)[
                'nombre' => $tenant->name,
                'id' => $tenant->id
            ];
        }

        // Get users by tenant_id (new system)
        if ($tenantInfo->id) {
            $users = User::where('tenant_id', $tenantInfo->id)->get();
        } else {
            $users = collect();
        }

        if ($users->isEmpty()) {
            return back()->withErrors([
                'email' => 'No hay usuarios asociados a esta veterinaria. Por favor verifica el correo ingresado.'
            ])->onlyInput('email');
        }

        // Generate a temporary password for each user
        $successCount = 0;
        foreach ($users as $user) {
            $temporaryPassword = Str::random(12);

            // Update the user's password with the temporary one
            $user->update([
                'password' => bcrypt($temporaryPassword)
            ]);

            // Send the password via email
            try {
                Mail::to($user->email)->send(new ForgotPasswordMail($user, $temporaryPassword, $tenantInfo));
                $successCount++;
            } catch (\Exception $e) {
                \Log::error('Error sending forgot password email to ' . $user->email . ': ' . $e->getMessage());
            }
        }

        if ($successCount === 0) {
            return back()->withErrors([
                'email' => 'Error al enviar los correos. Por favor intenta más tarde.'
            ])->onlyInput('email');
        }

        return back()->with('success', 
            'Se enviaron las contraseñas temporales a los correos de los usuarios de la veterinaria "' . $tenantInfo->nombre . '". ' .
            'Por favor revisa tu correo electrónico (incluyendo la carpeta de spam).'
        );
    }
}
