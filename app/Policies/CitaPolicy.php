<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;

class CitaPolicy
{
    /**
     * Determine if the user can view the cita.
     */
    public function view(User $user, Cita $cita): bool
    {
        return $user->tenant_id === $cita->veterinaria_id;
    }

    /**
     * Determine if the user can create a cita.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the cita.
     */
    public function update(User $user, Cita $cita): bool
    {
        return $user->tenant_id === $cita->veterinaria_id;
    }

    /**
     * Determine if the user can delete the cita.
     */
    public function delete(User $user, Cita $cita): bool
    {
        return $user->tenant_id === $cita->veterinaria_id;
    }

    /**
     * Determine if the user can restore the cita.
     */
    public function restore(User $user, Cita $cita): bool
    {
        return $user->tenant_id === $cita->veterinaria_id;
    }

    /**
     * Determine if the user can permanently delete the cita.
     */
    public function forceDelete(User $user, Cita $cita): bool
    {
        return $user->tenant_id === $cita->veterinaria_id;
    }
}
