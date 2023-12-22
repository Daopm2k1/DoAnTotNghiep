<?php
use Illuminate\Support\Facades\Route;
//Dashboard
Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard.index');
Route::get('dashboard/new-order', 'DashboardController@newOrder')->name('admin.dashboard.new_order');
Route::get('dashboard/export', 'DashboardController@export')->name('admin.dashboard.export');
Route::get('dashboard/revenue', 'DashboardController@revenue')->name('admin.dashboard.revenue');
Route::get('dashboard/revenue-detail', 'DashboardController@revenueDetail')->name('admin.dashboard.revenue_detail');
Route::get('dashboard/order-month', 'DashboardController@orderMonth')->name('admin.dashboard.order-month');
Route::get('dashboard/product-almost-over', 'DashboardController@productAlmostOver')->name('admin.dashboard.product-almost-over');
Route::post('dashboard/product-almost-over/store', 'DashboardController@productAlmostOverStore')->name('admin.dashboard.product-almost-over-store');

//User
Route::get('user', 'UserController@index')->name('admin.user.index');
Route::get('user/create', 'UserController@create')->name('admin.user.create');
Route::get('user/edit/{id}', 'UserController@edit')->name('admin.user.edit');
Route::post('user/store', 'UserController@store')->name('admin.user.store');
Route::delete('user/delete', 'UserController@delete')->name('admin.user.delete');
Route::get('user/{id}/reset-password','UserController@getResetPassword')->name('admin.resetpassword.get');
Route::post('user/{id}/reset-password','UserController@postResetPassword')->name('admin.resetpassword.post');


//Role
Route::get('role', 'RoleController@index')->name('admin.role.index');
Route::get('role/create', 'RoleController@create')->name('admin.role.create');
Route::get('role/edit/{id}', 'RoleController@edit')->name('admin.role.edit');
Route::post('role/store', 'RoleController@store')->name('admin.role.store');
Route::delete('role/delete', 'RoleController@delete')->name('admin.role.delete');

//Category
Route::get('category', 'CategoryController@index')->name('admin.category.index');
Route::get('category/create', 'CategoryController@create')->name('admin.category.create');
Route::get('category/edit/{id}', 'CategoryController@edit')->name('admin.category.edit');
Route::post('category/store', 'CategoryController@store')->name('admin.category.store');
Route::delete('category/delete', 'CategoryController@delete')->name('admin.category.delete');

//Product
Route::get('product', 'ProductController@index')->name('admin.product.index');
Route::get('product/create', 'ProductController@create')->name('admin.product.create');
Route::get('product/edit/{id}', 'ProductController@edit')->name('admin.product.edit');
Route::post('product/store', 'ProductController@store')->name('admin.product.store');
Route::delete('product/delete', 'ProductController@delete')->name('admin.product.delete');

//Product detail
Route::get('product/{id}/detail', 'ProductDetailController@index')->name('admin.product_detail.index');
Route::get('product/{id}/detail/create', 'ProductDetailController@create')->name('admin.product_detail.create');
Route::get('product/{id}/detail/edit/{idDetail}', 'ProductDetailController@edit')->name('admin.product_detail.edit');
Route::post('product/{id}/detail/store', 'ProductDetailController@store')->name('admin.product_detail.store');
Route::delete('product/detail/delete', 'ProductDetailController@delete')->name('admin.product_detail.delete');

//Size
Route::get('size', 'SizeController@index')->name('admin.size.index');
Route::get('size/create', 'SizeController@create')->name('admin.size.create');
Route::get('size/edit/{id}', 'SizeController@edit')->name('admin.size.edit');
Route::post('size/store', 'SizeController@store')->name('admin.size.store');
Route::delete('size/delete', 'SizeController@delete')->name('admin.size.delete');

//Color
Route::get('color', 'ColorController@index')->name('admin.color.index');
Route::get('color/create', 'ColorController@create')->name('admin.color.create');
Route::get('color/edit/{id}', 'ColorController@edit')->name('admin.color.edit');
Route::post('color/store', 'ColorController@store')->name('admin.color.store');
Route::delete('color/delete', 'ColorController@delete')->name('admin.color.delete'); 
Route::delete('color/delete', 'ColorController@delete')->name('admin.color.delete');

//Slide
Route::get('slide', 'SlideController@index')->name('admin.slide.index');
Route::get('slide/create', 'SlideController@create')->name('admin.slide.create');
Route::post('slide/store', 'SlideController@store')->name('admin.slide.store');
Route::delete('slide/delete', 'SlideController@delete')->name('admin.slide.delete');
//path
Route::get('get-path-product', 'SlideController@get_path_product');

//Opinion
Route::get('contact', 'OpinionController@index')->name('admin.opinion.index');
Route::delete('contact/delete', 'OpinionController@delete')->name('admin.opinion.delete');

//Tag
Route::get('tag', 'TagController@index')->name('admin.tag.index');
Route::get('tag/create', 'TagController@create')->name('admin.tag.create');
Route::get('tag/edit/{id}', 'TagController@edit')->name('admin.tag.edit');
Route::post('tag/store', 'TagController@store')->name('admin.tag.store');
Route::delete('tag/delete', 'TagController@delete')->name('admin.tag.delete');

//Product Tag
Route::get('product_tag', 'ProductTagController@index')->name('admin.product_tag.index');
Route::get('product_tag/create', 'ProductTagController@create')->name('admin.product_tag.create');
Route::get('product_tag/edit/{id}', 'ProductTagController@edit')->name('admin.product_tag.edit');
Route::post('taproduct_tag/store', 'ProductTagController@store')->name('admin.product_tag.store');
Route::delete('product_tag/delete', 'ProductTagController@delete')->name('admin.product_tag.delete'); 

//Wishlist
Route::get('wishlist', 'WishlistController@index')->name('admin.wishlist.index');
Route::get('wishlist/{id}/detail', 'WishlistController@detail')->name('admin.wishlist.detail');
Route::post('wishlist/{id}/store', 'WishlistController@store')->name('admin.wishlist.store');
Route::delete('wishlist/delete', 'WishlistController@delete')->name('admin.wishlist.delete');

//Order
Route::get('order', 'OrderController@index')->name('admin.order.index');
Route::get('/order-online-unpaid','OrderController@orderOnlineUnpaid')->name('admin.order.order_online_unpaid');
Route::put('order/{id}', 'OrderController@update')->name('admin.order.update');
Route::delete('order/delete', 'OrderController@delete')->name('admin.order.delete');

//Bill
// Route::get('/bill', 'BillController@index')->name('admin.bill.index');
// Route::delete('bill/delete', 'BillController@delete')->name('admin.bill.delete');

//Order detail
Route::get('/order/{id}/detail', 'OrderDetailController@index')->name('admin.order_detail.index');

//Discount
Route::get('discount', 'DiscountController@index')->name('admin.discount.index');
Route::get('discount/create', 'DiscountController@create')->name('admin.discount.create');
Route::get('discount/detail/{id}', 'DiscountController@edit')->name('admin.discount.edit');
Route::post('discount/store', 'DiscountController@store')->name('admin.discount.store');
Route::delete('discount/delete', 'DiscountController@delete')->name('admin.discount.delete'); 

//Comment
Route::get('product/{id}/comment', 'CommentController@index')->name('admin.comment.index');
Route::delete('product/{id}/comment/delete', 'CommentController@delete')->name('admin.comment.delete');

//Fee
Route::get('ship', 'ShipController@index')->name('admin.ship.index');
Route::get('ship/create', 'ShipController@create')->name('admin.ship.create');
Route::get('ship/edit/{id}', 'ShipController@edit')->name('admin.ship.edit');
Route::post('ship/store', 'ShipController@store')->name('admin.ship.store');
Route::delete('ship/delete', 'ShipController@delete')->name('admin.ship.delete');



//PostCate
Route::get('postcate', 'PostCateController@index')->name('admin.post_cate.index');
Route::get('postcate/create', 'PostCateController@create')->name('admin.post_cate.create');
Route::get('postcate/edit/{id}', 'PostCateController@edit')->name('admin.post_cate.edit');
Route::post('postcate/store', 'PostCateController@store')->name('admin.post_cate.store');
Route::delete('postcate/delete', 'PostCateController@delete')->name('admin.post_cate.delete');

//Post
Route::get('post', 'PostController@index')->name('admin.post.index');
Route::get('post/create', 'PostController@create')->name('admin.post.create');
Route::get('post/edit/{id}', 'PostController@edit')->name('admin.post.edit');
Route::post('post/store', 'PostController@store')->name('admin.post.store');
Route::delete('post/delete', 'PostController@delete')->name('admin.post.delete');

//InforContact
Route::get('inforcontact', 'InforContactController@index')->name('admin.infor_contact.index');
Route::get('inforcontact/create', 'InforContactController@create')->name('admin.infor_contact.create');
Route::get('inforcontact/edit/{id}', 'InforContactController@edit')->name('admin.infor_contact.edit');
Route::post('inforcontact/store', 'InforContactController@store')->name('admin.infor_contact.store');
Route::delete('inforcontact/delete', 'InforContactController@delete')->name('admin.infor_contact.delete');
