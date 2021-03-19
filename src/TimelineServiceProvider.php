<?php

namespace Sacred96\Timeline;

use Illuminate\Support\ServiceProvider;

class TimelineServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(
            dirname(__DIR__) . '/resources/lang',
            'timeline'
        );

        $this->publishMigrations();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTimeline();
    }

    private function publishMigrations()
    {
        $timestamp = date('Y_m_d_His');
        $stub = __DIR__ . '/../database/migrations/create_timeline_tables.php';
        $target = $this->app->databasePath() . '/migrations/' . $timestamp . '_create_timeline_tables.php';

        $this->publishes([$stub => $target], 'timeline.migrations');
    }

    private function registerTimeline()
    {
        $this->app->bind('timeline', function () {
            return $this->app->make(\Sacred96\Timeline\Timeline::class);
        });
    }

}
