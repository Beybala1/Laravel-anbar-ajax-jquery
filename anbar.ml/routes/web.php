<?php
use App\Http\Controllers\Admin;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XercController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocController;
use App\Http\Controllers\Auth\VerificationController;
use App\Models\Order;
use App\Models\Product;

Route::middleware(['auth','verified'])->group(function () {

    Route::resource('brand', BrandController::class);
    Route::resource('client', ClientController::class);
    Route::resource('product', ProductController::class);
    Route::resource('xerc', XercController::class);
    Route::resource('employee', EmployeeController::class);
    Route::resource('employee_document', EmployeeDocController::class);

    Route::controller(OrderController::class)->group(function(){
        Route::resource('order', OrderController::class);
        Route::post('order_confirm', 'confirm');
        Route::post('order_cancel', 'cancel');
    }); 

    Route::controller(CreditController::class)->group(function(){
        Route::resource('credit', CreditController::class);
        Route::get('pay_check/{id}', 'pay_check'); 
        Route::get('pay/{id}','pay');
        Route::post('pay_confirm/{id}', 'pay_confirm');
        Route::post('cancel', 'cancel');
    }); 

    Route::controller(ProfileController::class)->group(function(){
        Route::get('profile', 'index');
        Route::post('profile_update', 'update');
    }); 

    Route::get('/', function(){
        $product_brand = Product::where('products.user_id','=',auth()->id())->get();
        $orders_data = Order::join('products','products.id','=','orders.product_id')->where('products.user_id','=',auth()->id())->get();
        return view('brand',compact('product_brand','orders_data'));
    });
});

Route::controller(VerificationController::class)->group(function(){
    Route::get('email/verify', 'show')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('email/resend', 'resend')->name('verification.resend');
}); 

Route::controller(LoginController::class)->group(function(){
    Route::get('auth/google', 'redirectToGoogle');
    Route::get('auth/google/callback', 'handleGoogleCallback');
}); 

Auth::routes(['verify '=> true]);

Route::middleware(['auth', 'isAdmin','verified'])->controller(Admin::class)->group(function () {
    Route::get('admin', 'index');
    Route::post('user_block', 'block');
    Route::post('user_unblock', 'unblock');
    Route::post('user_delete', 'destroy');
});

    


