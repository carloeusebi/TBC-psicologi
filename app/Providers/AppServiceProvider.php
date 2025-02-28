<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Number::useCurrency(config('cashier.currency'));
        Number::useLocale($this->app->getLocale());

        Model::shouldBeStrict(! $this->app->isProduction());

        Date::use(CarbonImmutable::class);

        Password::defaults(function () {
            $rules = Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols();

            if ($this->app->isProduction()) {
                // @codeCoverageIgnoreStart
                $rules->uncompromised();
                // @codeCoverageIgnoreEnd
            }

            return $rules;
        });
    }
}
