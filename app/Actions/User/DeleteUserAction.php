<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use DB;

final class DeleteUserAction
{
    public function handle(User $user): ?bool
    {
        return DB::transaction(function () use ($user) {
            $user->evaluations()->delete();

            $user->patients()->delete();

            return $user->delete();
        });
    }
}
