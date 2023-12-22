<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
//Home
Route::get('home', 'HomeController@index');
Route::get('/', 'HomeController@index')->name('home');

//Login
Route::get('login','UserController@getLogin')->name('login');
Route::post('login','UserController@postLogin')->name('login');

//Logout
Route::get('logout','UserController@logout')->name('logout');

//Register
Route::get('register','UserController@getRegister')->name('register.get');
Route::post('register','UserController@postRegister')->name('register.post');
Route::get('active/{email}/{string}','UserController@activeAccount')->name('activeAccount');

//Home
Route::get('category/{id}','CategoryController@show_category_product');
Route::get('product/{id}/detail','ProductController@show_detail_product')->name('user.product.detail');
Route::get('profile','ProfileController@index')->name('user.profile.index');
Route::post('profile/store', 'ProfileController@store')->name('user.profile.store');

//Change password
Route::get('change-password','UserController@getChangePassword')->name('user.change_password.get');
Route::post('change-password', 'UserController@postChangePassword')->name('user.change_password.post');

//Checkout
Route::get('checkout','CheckoutController@checkout');
Route::post('checkout-vnp','CheckoutController@checkoutVNPay');
Route::get('return-vnpay','CheckoutController@returnVNPay');
Route::get('return-momo','CheckoutController@returnMomo');
Route::post('checkout','CheckoutController@store');

Route::get('check-discount','CheckoutController@checkDiscount');
Route::get('/login-checkout','CheckoutController@login_checkout');
Route::post('/order-place','CheckoutController@order_place');

//Checkout Ajax
Route::post('/order-place-ajax','CheckoutController@order_place_ajax');

//Cart
Route::post('save-cart','CartController@save_cart');
Route::get('cart','CartController@show_cart');
Route::post('delete-cart','CartController@deleteCart');
Route::post('update-cart','CartController@updateCart');
Route::get('delete-to-cart/{rowId}','CartController@delete_to_cart');
Route::post('update-cart-quantity','CartController@update_cart_quantity');
//Cart Ajax
Route::post('/add-cart-ajax','CartController@add_cart_ajax');
Route::get('show-cart-ajax','CartController@show_cart_ajax');
Route::get('delete-all-product','CartController@delete_all_product');

//Wishlist
Route::post('add-wishlist','ProductController@add_wishlist');
Route::post('remove-wishlist','ProductController@removeWishlist');
Route::get('wishlist','ProductController@show_wishlist');
Route::get('delete-wishlist/','CartController@delete_wishlist');

//SendMail
Route::get('show_contact','MailController@contact');
Route::post('show_contact','MailController@post_contact');

//Opinion
Route::post('/opinion/add','OpinionController@add')->name('user.opinion.add');

//Tag
Route::get('/tag/{slug}','TagController@index')->name('user.tag.index');

Route::get('/quickview','ProductController@quickview');
Route::post('send-comment','ProductController@send_comment');
Route::post('load-comment','ProductController@load_comment');

//About
Route::get('/about','AboutController@index')->name('user.about.index');

//Terms
Route::get('/terms','TermsController@index')->name('user.terms.index');

//Privacy
Route::get('/privacy','PrivacyController@index')->name('user.privacy.index');

//Lie
Route::get('/exchange','ExchangeController@index')->name('user.exchange.index');

//Comment
Route::post('comment{id}','ProductController@post_comment');
//Search Ajax
Route::get('/autocomplete-ajax','HomeController@autocomplete_ajax');

//Order
Route::get('/order','OrderController@index')->name('user.order.index');
Route::get('order/{id}/detail','OrderController@orderDetail')->name('user.order.detail');
Route::delete('order/{id}/delete','OrderController@destroyOrder')->name('user.order.delete');
Route::post('order/{id}/payback','OrderController@payBack')->name('user.order.pay_back');

//View
Route::get('show-product','HomeController@show_product')->name('show-product');

//Discount
Route::get('/discount','DiscountController@index')->name('user.discount.index');

//Post
Route::get('post-cate/{path}','PostController@show_post_cate');
Route::get('post/{path}','PostController@show_post');

//Info-Contact
Route::get('infor-contact','InforContactController@infor_contact');