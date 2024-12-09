<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Blade;
use App\Models\Category;
use Illuminate\Pagination\Paginator;

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
        /*
        |--------------------------------------------------------------------------
        | Blade Directive
        |--------------------------------------------------------------------------
        */
        Blade::directive('generate_tags', function ($expression) {
            return "<?php 
                echo generate_tags($expression);
            ?>";
        });

        /*
        |--------------------------------------------------------------------------
        | Pagination Bootstrap
        |--------------------------------------------------------------------------
        */
        Paginator::useBootstrapFive();

        /* 
        |--------------------------------------------------------------------------
        | Kategori
        |--------------------------------------------------------------------------
        */
        Category::updated(function () {
            Cache::forget('categories');
        });

        Category::created(function () {
            Cache::forget('categories');
        });

        Category::deleted(function () {
            Cache::forget('categories');
        });

        // Cache data kategori selama 60 menit
        $categories = Cache::remember('categories', now()->addMinutes(60), function () {
            return Category::all();
        });

        // Bagikan data kategori ke semua view
        View::share('categories', $categories);
    }
}
