<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VendorServiceController;
use App\Http\Controllers\VendorController;
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

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard route (protected by auth middleware) - also serves as index
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');


// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Client Controller
    Route::get('/client', [ClientController::class, 'client'])->name('client');
    Route::post('/client/toggle-status',  [ClientController::class, 'toggleStatus'])->name('client.toggleStatus');
    Route::get('/add-client', [ClientController::class, 'addclient'])->name('add-client');
    Route::post('/store-client', [ClientController::class, 'storeclient'])->name('store-client');
    Route::get('/edit-client/{id}', [ClientController::class, 'editclient'])->name('client.edit');
    Route::put('/update-client/{id}', [ClientController::class, 'updateclient'])->name('client.update');
    Route::delete('/client/delete/{id}', [ClientController::class, 'deleteclient'])->name('client.delete');
    Route::get('/client-details/{id}', [ClientController::class, 'viewclient'])->name('client.view');
     Route::delete('/client/delete-selected',  [ClientController::class, 'deleteSelected'])->name('delete.selected.client');

    // Client bulk upload routes
    Route::post('/client/bulk-upload', [ClientController::class, 'bulkUpload'])->name('client.bulk-upload');
    Route::get('/client/download-template', [ClientController::class, 'downloadTemplate'])->name('client.download-template');
    //end Client controller

    // Other protected routes
    Route::get('/add-servies', function () {
        return view('add-servies');
    })->name('add-servies');

    Route::get('/add-vendor', [VendorController::class, 'create'])->name('add-vendor');

    Route::get('/app-to-do', function () {
        return view('app-to-do');
    })->name('app-to-do');

    Route::get('/servies', function () {
        return view('servies');
    })->name('servies');

    Route::get('/user-profile', function () {
        return view('user-profile');
    })->name('user-profile');



    Route::get('/vendor', function () {
        return view('vendor');
    })->name('vendor');



    // Vendor CRUD routes
    Route::resource('vendors', VendorController::class);

    // Vendor bulk upload routes
    Route::post('/vendor1/bulk-upload', [VendorController::class, 'bulkUpload'])->name('vendors.bulk-upload');
    Route::get('/vendor1/download-template', [VendorController::class, 'downloadTemplate'])->name('vendors.download-template');

    // Service CRUD routes
    Route::resource('services', ServiceController::class);
    Route::post('/services/delete-selected', [ServiceController::class, 'deleteSelected'])->name('delete.selected.service');

    // Vendor Service CRUD routes
    Route::resource('vendor-services', VendorServiceController::class);
    Route::post('/vendor-services/delete-selected', [VendorServiceController::class, 'deleteSelected'])->name('delete.selected.vendor-service');

    // Mail routes for sending renewal emails
    Route::get('/send-mail/{service_id}', [MailController::class, 'sendMailForm'])->name('send-mail');
    Route::post('/send-mail', [MailController::class, 'sendMail'])->name('send-mail.send');

    // Notification routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/renewal', [NotificationController::class, 'getRenewalNotifications'])->name('renewal');
        Route::get('/counts', [NotificationController::class, 'getNotificationCounts'])->name('counts');
        Route::get('/urgent', [NotificationController::class, 'getUrgentNotifications'])->name('urgent');
        Route::get('/summary', [NotificationController::class, 'getNotificationSummary'])->name('summary');
        Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });

    // Additional routes for backward compatibility
    Route::get('/vendor1', [VendorController::class, 'index'])->name('vendor1');
     Route::delete('/vendor1/delete-selected', [VendorController::class, 'deleteSelected'])->name('delete.selected.vendor');
    Route::post('/vendor1/toggle-status',  [VendorController::class, 'toggleStatus'])->name('vendor1.toggleStatus');
    Route::get('/servies', [ServiceController::class, 'index'])->name('servies');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email')->middleware('throttle:5,1');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Keep old routes for backward compatibility
Route::get('/auth-basic-signin', [AuthController::class, 'showLoginForm'])->name('auth-basic-signin');
Route::get('/auth-basic-signup', [AuthController::class, 'showRegisterForm'])->name('auth-basic-signup');







