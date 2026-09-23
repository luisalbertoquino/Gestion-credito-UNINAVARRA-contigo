<?php

namespace App\Providers;

use App\Models\Parametro;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

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
        $this->app->booted(function () {
            if (! Schema::hasTable('parametros')) {
                return;
            }

            view()->composer(['layouts.creditos', 'layouts.auth-creditos'], function (View $view) {
                $view->with('nombreInstitucion', Parametro::actual()->nombre_institucion);
            });
        });
    }
}
