<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Admin
Route::post('/product/{id}/add-tag', '\App\Http\Controllers\Admin\ProductController@addTag')->name('admin.product.add_tag');
Route::post('/product/{id}/remove-tag', '\App\Http\Controllers\Admin\ProductController@removeTag')->name('admin.product.remove_tag');
Route::post('/user/{id}/active', '\App\Http\Controllers\Admin\UserController@lockAccount')->name('admin.user.active');
Route::get('/search-user-discount', '\App\Http\Controllers\Admin\UserController@searchUserDiscount')->name('admin.user.searchUserDiscount');
Route::post('/post/{id}/add-tag', '\App\Http\Controllers\Admin\PostController@addTag')->name('admin.post.add_tag');
Route::post('/post/{id}/remove-tag', '\App\Http\Controllers\Admin\PostController@removeTag')->name('admin.post.remove_tag');

//User
Route::get('/product/{id}/detail/{color}', '\App\Http\Controllers\ProductController@getSize')->name('user.user.active');
Route::get('/check-ship', '\App\Http\Controllers\CheckoutController@checkShip')->name('user.checkout.check_ship');
Route::get('/remove-discount', '\App\Http\Controllers\CheckoutController@removeDiscount')->name('user.checkout.remove_discount');
Route::get('/get-district', '\App\Http\Controllers\CheckoutController@getDistrict')->name('user.checkout.get_district');
Route::get('/get-ward', '\App\Http\Controllers\CheckoutController@getWard')->name('user.checkout.get_ward');
Route::get('/get-address', '\App\Http\Controllers\CheckoutController@getAddress')->name('user.checkout.get_address');
Route::post('/delete-address', '\App\Http\Controllers\CheckoutController@deleteAddress')->name('user.checkout.delete_address');
Route::post('/add-comment','\App\Http\Controllers\CommentController@addComment');
Route::post('/delete-comment','\App\Http\Controllers\CommentController@deleteComment');
Route::get('/load-more','\App\Http\Controllers\CommentController@loadMore');