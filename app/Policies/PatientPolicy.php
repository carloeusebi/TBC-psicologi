<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

final class PatientPolicy
{
    use HandlesAuthorization;

    public function viewAny(): bool
    {
        return true;
    }

    public function view(User $user, Patient $patient): Response
    {
        return $patient->user_id === $user->id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(): bool
    {
        return true;
    }

    public function update(User $user, Patient $patient): Response
    {
        return $patient->user_id === $user->id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Patient $patient): Response
    {
        return $patient->user_id === $user->id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
