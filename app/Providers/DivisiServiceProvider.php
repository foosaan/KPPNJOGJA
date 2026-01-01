<?php

namespace App\Providers;

use App\Models\Divisi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class DivisiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share divisis to user views
        View::composer(['user.sidebar', 'user.app'], function ($view) {
            $divisis = Divisi::where('is_active', true)
                ->orderBy('nama')
                ->get();
            $view->with('divisis', $divisis);
        });
    }
}
