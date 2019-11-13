<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/users/', 'UserController@index');
$router->post('/users/', 'UserController@store');
$router->get('/users/{user_id}', 'UserController@show');
$router->put('/users/{user_id}', 'UserController@update');
$router->delete('/users/{user_id}', 'UserController@destroy');
// WishList
$router->get('/wishlists', ['middleware' => 'checkUserId', 'uses' => 'WishListController@index']);
$router->post('/wishlists', ['middleware' => 'checkUserId', 'uses' => 'WishListController@store']);
$router->get('/wishlists/{wishlist_id}', ['middleware' => 'checkUserId', 'uses' => 'WishListController@show']);
$router->put('/wishlists/{wishlist_id}', ['middleware' => 'checkUserId', 'uses' => 'WishListController@update']);
$router->delete('/wishlists/{wishlist_id}', ['middleware' => 'checkUserId', 'uses' => 'WishListController@destroy']);
//Product
$router->get('/products', ['middleware' => ['checkUserId', 'checkWishListId'], 'uses' => 'ProductController@index']);
$router->post('/products', ['middleware' => ['checkUserId', 'checkWishListId'], 'uses' => 'ProductController@store']);
$router->get('/products/{product_id}', ['middleware' => ['checkUserId', 'checkWishListId'], 'uses' => 'ProductController@show']);
$router->put('/products/{product_id}', ['middleware' => ['checkUserId', 'checkWishListId'], 'uses' => 'ProductController@update']);
$router->delete('/products/{product_id}', ['middleware' => ['checkUserId', 'checkWishListId'], 'uses' => 'ProductController@destroy']);
