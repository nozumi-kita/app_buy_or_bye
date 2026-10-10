<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Email;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
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
        Email::defaults(function () {
            $email = Rule::email()->rfcCompliant(strict: true);

            return $this->app->isProduction()
                ? $email->validateMxRecord()
                : $email;
        });

        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->mixedCase()->symbols()->numbers()->uncompromised()
                : $rule;
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('guest-login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('account.destroy', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()->id);
        });

        RateLimiter::for('account.update', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()->id);
        });
    }
}
