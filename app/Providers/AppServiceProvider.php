<?php

namespace App\Providers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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
    View::composer('*', function ($view) {
        // Ensure notifications exist in session, otherwise initialize dummy data
        if (!session()->has('notifications')) {
            session()->put('notifications', [
                [
                    'id' => 1,
                    'type' => 'new_request',
                    'title' => 'New Request Generated',
                    'message' => 'John Doe has generated a new service request.',
                    'is_read' => false,
                    'created_at' => now()->subMinutes(10)->diffForHumans(),
                ],
                [
                    'id' => 2,
                    'type' => 'driver_test_completed',
                    'title' => 'Driver Test Completed',
                    'message' => 'Driver Ali Khan successfully completed the driving test.',
                    'is_read' => false,
                    'created_at' => now()->subHours(1)->diffForHumans(),
                ],
            ]);
        }

        $view->with('notifications', session('notifications', []));
    });

    $locale = Session::get('locale', config('app.locale'));
    App::setLocale($locale);
}
}
