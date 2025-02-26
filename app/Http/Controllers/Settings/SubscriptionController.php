<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Subscription\SubscribeUser;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Plan;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Cashier\Checkout;

final class SubscriptionController
{
    public function index(Request $request): Response
    {
        return Inertia::render('settings/Subscription', [
            'plan' => $request->user()->plan(),
            'subscription' => $request->user()->subscription(),
        ]);
    }

    public function store(SubscriptionRequest $request, SubscribeUser $action): Checkout|RedirectResponse
    {
        try {
            return $action->handle($request->user(), $request->input('price'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return back()->with('error', 'Si è verificato un errore durante la creazione dell\'iscrizione.');
        }
    }

    public function edit(Request $request): Response|RedirectResponse
    {
        if ($request->user()->subscribed()) {
            return redirect()->route('subscription.show');
        }

        return Inertia::render('Subscriptions', [
            'plans' => Plan::all(),
            'currentPlan' => $request->user()->plan(),
            'csrf_token' => csrf_token(),
        ]);
    }

    public function show(Request $request): RedirectResponse
    {
        if (! $request->user()->hasStripeId()) {
            $request->user()->createAsStripeCustomer();
        }

        return $request->user()->redirectToBillingPortal(
            route('subscription.index'),
            ['locale' => 'it']
        );
    }
}
