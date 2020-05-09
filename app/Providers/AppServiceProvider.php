<?php

namespace App\Providers;

use App\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Product::updated(function ($product) {
            if ($product->qty == 0 && $product->isAvaliable()) {
                $product->status = Product::UNAVALIABLE_PRODUCT;
                $product->save();
            }
        });
    }
}
