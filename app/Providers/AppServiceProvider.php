<?php

namespace App\Providers;

use App\Mail\UserMailChangedVerification;
use App\Mail\UserVerification;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;
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


        User::created(function ($user) {
            retry(5, function () use ($user) {
                Mail::to($user)->send(new UserVerification($user));
            }, 100);
        });

        User::updated(function ($user) {

            if ($user->isDirty('email')) {
                retry(5, function () use ($user) {
                    Mail::to($user)->send(new UserMailChangedVerification($user));
                }, 100);
            }
        });
    }
}
