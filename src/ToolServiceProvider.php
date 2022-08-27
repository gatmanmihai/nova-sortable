<?php

namespace Outl1ne\NovaSortable;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-sortable', __DIR__ . '/../dist/js/entry.js');
            Nova::translations(
                lang_path('vendor/nova-sortable/'.app()->getLocale().'.json')
            );
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('vendor/nova-sortable'),
            ], 'nova-sortable');
        }

        $this->loadJsonTranslationsFrom(lang_path('vendor/nova-sortable'));
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) return;

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-sortable')
            ->domain(config('nova.domain', null))
            ->namespace('\Outl1ne\NovaSortable\Http\Controllers')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
