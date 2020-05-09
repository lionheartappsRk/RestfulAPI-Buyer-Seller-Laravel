<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();
        
        $userQty = 1000;
        $categoryQty = 30;
        $productsQty = 1000;
        $transactionQty = 1000;

       //  $this->call(UserSeeder::class);

       factory(User::class, $userQty)->create();
       factory(Category::class, $categoryQty)->create();

       factory(Product::class, $productsQty)->create()->each(
           function($product){
                $categories = Category::all()->random(mt_rand(1,5))->pluck('id');
                $product->categories()->attach($categories);
           }
       );

       factory(Transaction::class, $transactionQty)->create();

    }
}
