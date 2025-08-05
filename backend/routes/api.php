<?php

use App\Http\Controllers\Api\Admin\ManagerAccountAuthControllerApi;
use App\Http\Controllers\Api\Admin\ManagerBrandControllerApi;
use App\Http\Controllers\Api\Admin\ManagerCategoryControllerApi;
use App\Http\Controllers\Api\Admin\ManagerOrderControllerApi;
use App\Http\Controllers\Api\Admin\ManagerProductControllerApi;
use App\Http\Controllers\Api\Authentication\AuthControllerApi;
use App\Http\Controllers\Api\Authentication\Customer\AccountCustomerApi;
use App\Http\Controllers\Api\BrandControllerApi;
use App\Http\Controllers\Api\CartControllerApi;
use App\Http\Controllers\Api\CategoryControllerApi;
use App\Http\Controllers\Api\CheckOutControllerApi;
use App\Http\Controllers\Api\ProductControllerApi;
use App\Http\Controllers\Api\SliderControllerApi;
use App\Http\Controllers\Api\TrackingOrderControllerApi;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('cors')->group(function () {

    //Product page user
    Route::get('product', [ProductControllerApi::class, 'getAllProduct']);
    Route::get('productfeatured', [ProductControllerApi::class, 'getProductFeatured']);
    Route::get('productnew', [ProductControllerApi::class, 'getNewProduct']);
    Route::get('productsuggested', [ProductControllerApi::class, 'getSuggestedProduct']);
    Route::get('detail/{id}', [ProductControllerApi::class, 'getProductDetail']);
    Route::get('products/category/{id}', [ProductControllerApi::class, 'getProductWithCategory']);
    Route::get('products/brand/{id}', [ProductControllerApi::class, 'getProductWithBrand']);
    Route::get('search', [ProductControllerApi::class, 'getSearchProduct']);

    //Category
    Route::get('category', [CategoryControllerApi::class, 'getCategory']);
    Route::post('category', [CategoryControllerApi::class, 'postCategory']);
    Route::get('category/{id}', [CategoryControllerApi::class, 'getEditCategory']);

    //Brand
    Route::get('brand', [BrandControllerApi::class, 'getBrand']);

    //Slider
    Route::get('slider', [SliderControllerApi::class, 'getSlider']);

    // Cart
    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', [CartControllerApi::class, 'getCart']);
        Route::get('add/{id}', [CartControllerApi::class, 'addToCart']);
    });

    // CheckOut
    Route::get('city', [CheckOutControllerApi::class, 'getCityApi']);
    Route::post('select-shipping-information', [CheckOutControllerApi::class, 'postSelectShippingInformationApi']);
    Route::options('select-shipping-information', function () {
        return response()->json([], 200);
    });
    Route::post('charge-shipping', [CheckOutControllerApi::class, 'calculateShipping']);
    Route::options('charge-shipping', function () {
        return response()->json([], 200);
    });
    Route::post('checkout', [CheckOutControllerApi::class, 'actionPostCheckOut']);
    Route::options('checkout', function () {
        return response()->json([], 200);
    });
    Route::post('/check-inventory', [CheckOutControllerApi::class, 'checkInventory']);
    Route::post('/reserved-stock', [CheckOutControllerApi::class, 'reservedStock']);
    Route::post('/delete-reserved-stock', [CheckOutControllerApi::class, 'deleteReservedStock']);

    //Customer API
    Route::group(['prefix' => 'customer'], function () {
        Route::post('login', [AccountCustomerApi::class, 'login']);
        Route::post('logout', [AccountCustomerApi::class, 'logout'])->middleware('auth:customer');

    });

    //Tracking Order
    Route::post('tracking-order', [TrackingOrderControllerApi::class, 'postTrackingOrder']);
    Route::get('detail-tracking-order', [TrackingOrderControllerApi::class, 'detailTrackingOrder']);

    //Login authentication
    Route::post('login', [AuthControllerApi::class, 'actionLoginAuth']);
    Route::post('logout', [AuthControllerApi::class, 'actionLogOutAuth'])->middleware('auth:api');
    Route::post('auth/register-account', [AuthControllerApi::class, 'actionRegisterAccountAuth']);
    Route::post('auth/forgot-password', [AuthControllerApi::class, 'actionForgotPasswordAuthAccount']);
    Route::post('auth/update-new-password-auth', [AuthControllerApi::class, 'updateNewPasswordAccountAuth']);;
    Route::get('admin/auth/check', [AuthControllerApi::class, 'checkAuth'])->middleware('auth:api');

    //ADMIN auth:api
    Route::group(['prefix' => 'admin', 'middleware' => 'auth:api'], function () {
        //Product
        Route::group(['prefix' => 'product'], function () {
            Route::get('/', [ManagerProductControllerApi::class, 'listManagerProduct']);
            Route::post('add', [ManagerProductControllerApi::class, 'ManagerAddProduct']);
            Route::post('edit/{id}', [ManagerProductControllerApi::class, 'actionEditProduct']);
            Route::get('search', [ManagerProductControllerApi::class, 'actionSearchProduct']);
            Route::get('delete/{id}', [ManagerProductControllerApi::class, 'actionDeleteProduct']);
        });

        //Category
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', [ManagerCategoryControllerApi::class, 'listManagerCategory']);
            Route::post('add', [ManagerCategoryControllerApi::class, 'actionAddNewCategory']);
            Route::get('delete/{id}', [ManagerCategoryControllerApi::class, 'actionDeleteCategory']);
        });

        //Brand
        Route::group(['prefix' => 'brand'], function () {
            Route::get('list-brands', [ManagerBrandControllerApi::class, 'listManagerBrands']);
            Route::post('add', [ManagerBrandControllerApi::class, 'actionAddNewBrands']);
            Route::get('delete/{id}', [ManagerBrandControllerApi::class, 'actionDeleteBrands']);
        });

        //Account Authentication 
        Route::group(['prefix' => 'account-auth', 'middleware' => 'restrict-to:admin'], function () {
            Route::get('roles', [ManagerAccountAuthControllerApi::class, 'listRoles']);
            Route::get('list', [ManagerAccountAuthControllerApi::class, 'showListAccountAuth']);
            Route::post('edit/{id}', [ManagerAccountAuthControllerApi::class, 'actionSubmitEditAccountAuth']);
        });

        Route::group(['prefix' => 'order'], function () {
            Route::get('list-order', [ManagerOrderControllerApi::class, 'listOrder']);
            Route::get('edit-order/{id}', [ManagerOrderControllerApi::class, 'actionGetEditOrder']);
            Route::post('edit-order/{id}', [ManagerOrderControllerApi::class, 'actionSaveEditOrder']);
            Route::delete('delete/{id}', [ManagerOrderControllerApi::class, 'actionDeleteOrder'])->middleware(['restrict-to:admin']);
        });
    });

    
});
