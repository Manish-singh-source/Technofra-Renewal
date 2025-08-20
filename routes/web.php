<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ServiceController;
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
    Route::get('/add-client', [ClientController::class, 'addclient'])->name('add-client');
    Route::post('/store-client', [ClientController::class, 'storeclient'])->name('store-client');
    Route::delete('/client/delete/{id}', [ClientController::class, 'deleteclient'])->name('client.delete');
    Route::get('/client-details/{id}', [ClientController::class, 'viewclient'])->name('client.view');
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

    // Service CRUD routes
    Route::resource('services', ServiceController::class);

    // Mail routes for sending renewal emails
    Route::get('/send-mail/{service_id}', [MailController::class, 'sendMailForm'])->name('send-mail');
    Route::post('/send-mail', [MailController::class, 'sendMail'])->name('send-mail.send');

    // Additional routes for backward compatibility
    Route::get('/vendor1', [VendorController::class, 'index'])->name('vendor1');
    Route::get('/servies', [ServiceController::class, 'index'])->name('servies');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Keep old routes for backward compatibility
Route::get('/auth-basic-signin', [AuthController::class, 'showLoginForm'])->name('auth-basic-signin');
Route::get('/auth-basic-signup', [AuthController::class, 'showRegisterForm'])->name('auth-basic-signup');







