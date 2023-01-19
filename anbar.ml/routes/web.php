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

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/', [BrandController::class, 'index'])->name('home');
    Route::post('brand_insert', [BrandController::class, 'store']);
    Route::post('brand_update', [BrandController::class, 'edit']);
    Route::post('brand_delete', [BrandController::class, 'destroy']);

    Route::get('client', [ClientController::class, 'index']);
    Route::post('client_insert', [ClientController::class, 'store']);
    Route::post('client_update', [ClientController::class, 'edit']);
    Route::post('client_delete', [ClientController::class, 'destroy']);

    Route::get('product', [ProductController::class, 'index']);
    Route::post('product_insert', [ProductController::class, 'store']);
    Route::post('product_update', [ProductController::class, 'edit']);
    Route::post('product_delete', [ProductController::class, 'destroy']);

    Route::get('order', [OrderController::class, 'index']);
    Route::post('order_insert', [OrderController::class, 'store']);
    Route::post('order_update', [OrderController::class, 'edit']);
    Route::post('order_delete', [OrderController::class, 'destroy']);
    Route::post('order_confirm', [OrderController::class, 'confirm']);
    Route::post('order_cancel', [OrderController::class, 'cancel']);

    Route::get('xerc', [XercController::class, 'index']);
    Route::post('xerc_insert', [XercController::class, 'store']);
    Route::post('xerc_update', [XercController::class, 'edit']);
    Route::post('xerc_delete', [XercController::class, 'destroy']);

    Route::get('employee', [EmployeeController::class, 'index']);
    Route::post('employee_insert', [EmployeeController::class, 'store']);
    Route::post('employee_update', [EmployeeController::class, 'edit']);
    Route::post('employee_delete', [EmployeeController::class, 'destroy']);

    Route::get('employee_document/{id}', [EmployeeDocController::class, 'index']);
    Route::post('employee_document_insert', [EmployeeDocController::class, 'store']);
    Route::post('employee_document_update', [EmployeeDocController::class, 'edit']);
    Route::post('employee_document_delete', [EmployeeDocController::class, 'destroy']);

    Route::get('credit', [CreditController::class, 'index']);
    Route::post('credit_insert', [CreditController::class, 'store']);
    Route::post('credit_update', [CreditController::class, 'edit']);
    Route::get('pay/{id}', [CreditController::class, 'pay']);
    Route::post('pay_confirm/{id}', [CreditController::class, 'pay_confirm']);
    Route::get('pay_check/{id}', [CreditController::class, 'show']);
    Route::post('cancel', [CreditController::class, 'cancel']);

    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile_update', [ProfileController::class, 'update']);
});

Auth::routes(['verify '=> true]);

Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::get('auth/google', [LoginController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('admin', Admin::class)->middleware(['auth','verified']);
    Route::post('user_block', [Admin::class, 'block']);
    Route::post('user_unblock', [Admin::class, 'unblock']);
    Route::post('user_delete', [Admin::class, 'destroy']);
});

    


