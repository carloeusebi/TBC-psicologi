<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\User\DeleteUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'canChangeEmail' => ! $request->user()->usingSocialite(),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->usingSocialite()) {
            $request->user()->email = $request->user()->getOriginal('email');
        } elseif ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request, DeleteUserAction $action): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $action->handle($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
