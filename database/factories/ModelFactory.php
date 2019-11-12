<?php

$factory->define(App\WishList::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(4),
        'user_id' => mt_rand(1, 10)
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    $url =  'http://www.' . str_replace(' ', '_', $faker->sentence(1)) . 'com';
    return [
        'name' => $faker->sentence(4),
        'link' => $url,
        'wish_list_id' => mt_rand(1, 50),
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $hasher = app()->make('hash');
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => $hasher->make("secret")
    ];
});
