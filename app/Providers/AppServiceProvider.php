<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

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
        // Uygulama açıklaması için varsayılan değer
        Config::set('app.description', 'En iyi tur deneyimleri ve geziler için tur sitemizi keşfedin. Özel turlar, grup gezileri ve daha fazlası.');
    }
}
