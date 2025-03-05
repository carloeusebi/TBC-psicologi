<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

final class EvaluationPolicy
{
    use HandlesAuthorization;

    public function viewAny(): bool
    {
        return true;
    }

    public function view(User $user, Evaluation $evaluation): Response
    {
        return $evaluation->user->is($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(): bool
    {
        return true;
    }

    public function update(User $user, Evaluation $evaluation): Response
    {
        return $evaluation->user->is($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Evaluation $evaluation): Response
    {
        return $evaluation->user->is($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
