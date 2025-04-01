<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

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
    // public function boot(): void
    // {
    //     //
    // }

    public function boot()
    {
        View::composer('*', function ($view) {
            $totalItems = 0;

            if (Auth::check()) {
                $carts = Cart::where('user_id', Auth::id())->get();
                $totalItems = $carts->sum(function ($cart) {
                    return $cart->items->sum('quantity');
                });
            }

            $view->with('totalItems', $totalItems);
        });
    }
}
