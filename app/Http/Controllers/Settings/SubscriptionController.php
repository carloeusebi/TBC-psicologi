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
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Checkout;

final class SubscriptionController
{
    public function index(Request $request): Response
    {
        return Inertia::render('settings/Subscription', [
            'plan' => $request->user()->plan(),
            'subscription' => $request->user()->subscription(),
            'invoices' => Inertia::defer(fn () => $request->user()->invoices(parameters: ['limit' => 10])),
        ]);
    }

    public function create(SubscriptionRequest $request, SubscribeUser $action): Checkout|RedirectResponse
    {
        if ($request->user()->hasStripeId() && $request->user()->subscribed()) {
            return to_route('subscriptions.show');
        }

        try {
            return $action->handle($request->user(), $request->input('price'));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return back()->with('error', 'Si è verificato un errore durante la creazione dell\'iscrizione.');
        }
    }

    public function edit(Request $request): Response
    {

        return Inertia::render('Subscriptions', [
            'plans' => Plan::all(),
            'secret' => Cashier::stripe()->customerSessions->create([
                'customer' => $request->user()->stripe_id,
                'components' => ['pricing_table' => ['enabled' => true]],
            ])['client_secret'],
        ]);
    }

    public function show(Request $request): RedirectResponse
    {
        if (! $request->user()->hasStripeId()) {
            $request->user()->createAsStripeCustomer();
        }

        return $request->user()->redirectToBillingPortal(
            URL::previous(route('subscription')),
            ['locale' => 'it']
        );
    }
}
