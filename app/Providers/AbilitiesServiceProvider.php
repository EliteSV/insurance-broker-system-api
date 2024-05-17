<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AbilitiesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHelpers();
    }

    /**
     * Register the application's helper functions.
     *
     * @return void
     */
    private function registerHelpers()
    {
        if (file_exists($file = app_path('Helpers/helpers.php'))) {
            require $file;
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
