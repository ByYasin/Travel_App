<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        
    }


    public function boot(): void
    {
        
        Config::set('app.description', 'En iyi tur deneyimleri ve geziler için tur sitemizi keşfedin. Özel turlar, grup gezileri ve daha fazlası.');
    }
}
