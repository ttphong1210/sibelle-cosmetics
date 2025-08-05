<?php

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

use App\Http\Controllers\Api\CheckOutControllerApi;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Route::get('welcome', function () {
    return view('frontend.welcome');
});
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return 'Application cache has been cleared';
});

Route::get('contact', function () {
    return view('frontend.contact');
})->name('contact');

// Route Front end

Route::get('/', 'FrontEndController@getHome')->name('home');
Route::get('product', 'FrontEndController@getProduct')->name('product');
Route::get('detail/{id}/{slug}.html', 'FrontEndController@getDetail');
Route::get('category/{id}/{slug}.html', 'FrontEndController@getCategory');
Route::get('brand/{id}/{slug}.html', 'FrontEndController@getBrand');
Route::get('search', 'FrontEndController@getSearch');
Route::get('favorite', 'FrontEndController@getFavorite');
Route::post('add-product-favorite', 'FrontEndController@postAddProductFavorite');
Route::get('tracking-order', 'FrontEndController@getTrackOrder');
Route::post('tracking-order', 'FrontEndController@postTrackOrder');
Route::get('detail-tracking-order', 'FrontEndController@getDetailTrackingOrder');

Route::get('email', 'FrontEndController@getEmail');
Route::get('load-comments', 'FrontEndController@getLoadComments');
Route::post('add-comments', 'FrontEndController@postAddComments');


Route::group(['prefix' => 'blog'], function () {
    Route::get('/', 'FrontendController@getBlog');
    // Route::get('blog/{id}/{slug}.html', 'FrontendController@getDetailBlog');
    Route::get('blog-id', 'FrontendController@getDetailBlog');


    // Route::get('kien-thuc', 'FrontendController@getBlogKThuc');
});

// Route Cart
Route::group(['prefix' => 'cart'], function () {
    Route::get('add/{id}', 'CartController@getAddCart');
    Route::get('show', 'CartController@getShowCart');
    Route::delete('delete/{id}', 'CartController@getDeleteCart');
    Route::get('update', 'CartController@getUpdateCart');
});
Route::get('complete', 'CartController@getComplete');
Route::get('checkout', 'CheckOutController@getCheckout');
Route::post('checkout', 'CheckOutController@postCheckout')->name('checkout.post');
Route::post('select-shipping-infomation', 'CheckOutController@postSelectShippingInfomation');
Route::post('charge-shipping', 'CheckOutController@postChargeShipping');
Route::get('delete-feeship', 'CheckOutController@getDeleteFeeship');


// Account Customer
Route::group(['prefix' => 'account'], function () {
    Route::get('register-customer', 'AccountCustomerController@getRegisterCustomer');
    Route::post('register-customer', 'AccountCustomerController@postRegisterCustomer');
    Route::get('login-customer', 'AccountCustomerController@getLoginCustomer');
    Route::post('login-customer', 'AccountCustomerController@postLoginCustomer');
    Route::get('forgot-password', 'AccountCustomerController@getForgotPassword');
    Route::post('reset-password', 'AccountCustomerController@postResetPassword');
    Route::get('update-new-password', 'AccountCustomerController@getUpdateNewPassword');
    Route::post('update-new-password', 'AccountCustomerController@postUpdateNewPassword');


    Route::get('logout-customer', 'AccountCustomerController@getLogOutCustomer');
});

// Route Admin
Route::group(['namespace' => 'Admin'], function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'CheckLogedOut'], function () {
        // Route::get('home', 'HomeController@getHome');
        Route::get('/home', 'DashboardController@index');
        Route::get('profile-user', 'DashboardController@getProfileUser');

        Route::group(['prefix' => 'user', 'middleware' => 'restrict-to'], function () {
            Route::get('all-user', 'UserController@getAllUser');
            Route::post('assign-role', 'UserController@postAssignRole');
            Route::get('delete-user/{id}', 'UserController@getDeleteUser');
        });
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', 'CategoryController@getCate');
            Route::get('add', 'CategoryController@getAddCate');
            Route::post('add', 'CategoryController@postAddCate');
            Route::get('edit/{id}', 'CategoryController@getEditCate');
            Route::post('edit/{id}', 'CategoryController@postEditCate');
            Route::get('delete/{id}', 'CategoryController@getDeleteCate');
        });

        Route::group(['prefix' => 'brand'], function () {
            Route::get('/', 'BrandController@getBrand');

            Route::get('add', 'BrandController@getAddBrand');
            Route::post('add', 'BrandController@postAddBrand');

            Route::get('edit/{id}', 'BrandController@getEditBrand');
            Route::post('edit/{id}', 'BrandController@postEditBrand');

            Route::get('delete/{id}', 'BrandController@getDeleteBrand');
        });

        Route::group(['prefix' => 'product', 'middleware' => 'restrict-to:admin'], function () {
            Route::get('/', 'ProductController@getProduct');

            Route::get('add', 'ProductController@getAddProduct');
            Route::post('add', 'ProductController@postAddProduct');

            Route::get('edit/{id}', 'ProductController@getEditProduct');
            Route::post('edit/{id}', 'ProductController@postEditProduct');

            Route::get('delete/{id}', 'ProductController@getDeleteProduct');

            Route::get('search', 'ProductController@getSearch');
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('/', 'OrderController@getOrder');

            Route::get('edit/{id}', 'OrderController@getEditOrder');
            Route::post('edit/{id}', 'OrderController@postEditOrder');

            Route::get('delete-order/{id}', 'OrderController@getDeleteOrder');
            // Customer

            Route::get('customer', 'OrderController@getCustomer');
            Route::get('customer/delete-customer/{id}', 'OrderController@getDeleteCustomer');
        });
        Route::group(['prefix' => 'delivery'], function () {
            Route::get('/', 'DeliveryController@getDelivery');
            Route::post('select-delivery', 'DeliveryController@postSelectDelivery');
            Route::post('add-delivery', 'DeliveryController@postAddDelivery');
            Route::post('update-delivery', 'DeliveryController@postEditDelivery');
        });

        // Route slider banner
        Route::group(['prefix' => 'slider'], function () {
            Route::get('/', 'SliderController@getSlider');
            Route::get('add-slider', 'SliderController@getAddSlider');
            Route::post('add-slider', 'SliderController@postAddSlider');
            Route::post('update-status-slider', 'SliderController@postUpdateStatusSlider');
        });

        //Route blog post
        Route::group(['prefix' => 'blog'], function () {
            Route::get('/', 'BlogPostController@getAllBlog');
            Route::get('add-blog', 'BlogPostController@getAddBlog');
            Route::post('add-blog', 'BlogPostController@postAddBlog');
            Route::get('edit-blog/{id}', 'BlogPostController@getEditBlog');
            Route::post('edit-blog/{id}', 'BlogPostController@postEditBlog');
            Route::get('delete/{id}', 'BlogPostController@getDeleteBlog');
        });
    });
});

//Authentication Route
Route::get('register-auth', 'AccountController@getRegisterAuth');
Route::post('register-auth', 'AccountController@postRegisterAuth');
Route::get('login-auth', 'AccountController@getLoginAuth')->name('login-auth');
Route::post('login-auth', 'AccountController@postLoginAuth');
Route::get('logout-auth', 'AccountController@getLogOutAuth');
Route::get('forgot-password-auth', 'AccountController@getForgotPassword');
Route::post('forgot-password-auth', 'AccountController@postResetPassword');
Route::get('update-password-auth', 'AccountController@getUpdateNewPassword');
Route::post('update-password-auth', 'AccountController@postUpdateNewPassword');


// Route::get('test-email', function(){
//     $testData = [
//         'customer' => [
//             'name' => 'Nguyễn Văn Test',
//             'number_phone' => '0123456789',
//             'email' => 'test@example.com'
//         ],
//         'order' => (object)[
//             'order_code' => 'TEST-' . date('YmdHis'),
//             'total' => 500000,
//             'date_order' => now(),
//             'order_payment' => 'COD',
//             'notes' => 'Đây là đơn hàng test'
//         ],
//         'cartInfo' => [
//             [
//                 'name' => 'Kem dưỡng da Test 1',
//                 'price' => 200000,
//                 'quantity' => 1
//             ],
//             [
//                 'name' => 'Serum Test 2',
//                 'price' => 300000,
//                 'quantity' => 2
//             ]
//         ],
//         'fullAddress' => '123 Đường Test, Phường Test, Quận Test, TP Test'
//     ];
//     $emailData = [
//         'customer_name' => $testData['customer']['name'],
//         'customer_phone' => $testData['customer']['number_phone'],
//         'customer_email' => $testData['customer']['email'],
//         'order_code' => $testData['order']->order_code,
//         'total' => $testData['order']->total,
//         'cartInfo' => $testData['cartInfo'],
//         'fullAddress' => $testData['fullAddress'],
//         'order_date' => $testData['order']->date_order->format('d/m/Y H:i'),
//         'payment_method' => $testData['order']->order_payment,
//         'notes' => $testData['order']->notes,
//     ];

//     $managerEmail = config('mail.manager_email', 'huuduongn91@gmail.com');
//     $managerName = config('mail.manager_name', 'Manager Si.Belle Cosmetics');
//     try {
//         // Gửi email test
//         Mail::send('frontend.new_order_notification', $emailData, function ($message) use ($managerEmail, $managerName, $testData) {
//             $message->to($managerEmail, $managerName)
//                     ->subject('TEST - Đơn hàng mới #' . $testData['order']->order_code);
//         });
//         return response()->json([
//             'status' => 'success',
//             'message' => 'Email test đã được gửi thành công!',
//             'data' => $emailData
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'Lỗi gửi email: ' . $e->getMessage()
//         ]);
//     }
// });

