<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\WishList;
use App\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        WishList::truncate();
        Product::truncate();

        factory(User::class, 10)->create();
        factory(WishList::class, 50)->create();
        factory(Product::class, 100)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
