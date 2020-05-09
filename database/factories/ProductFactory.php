<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        //
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'qty' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::AVALIABLE_PRODUCT, Product::UNAVALIABLE_PRODUCT]),
        'image' => $faker->randomElement(['1.png', '2.png', '3.png']),
        //  'seller_id' => User::all()->random()->id,
        'seller_id' => User::inRandomOrder()->first()->id,

    ];
});
