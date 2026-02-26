<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Veterinaria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

            if ($user) {
                // El usuario ya existe, actualizamos sus datos si es necesario
                $user->update([
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                ]);
            } else {
                // El usuario no existe, lo creamos
                
                // Creamos una veterinaria por defecto para el nuevo usuario
                $veterinaria = Veterinaria::create([
                    'nombre' => 'Clínica de ' . $googleUser->name,
                    'direccion' => 'Dirección por defecto',
                    'telefono' => '000000000',
                    'email' => $googleUser->email,
                ]);

                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(24)), // Contraseña aleatoria
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'veterinaria_id' => $veterinaria->id, // Asignamos la nueva veterinaria
                ]);
            }

            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Hubo un problema al autenticar con Google. Por favor, inténtalo de nuevo.');
        }
    }
}