<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;

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

Route::get('/', function() {
    return redirect()->route('dashboard');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	// Profile routes
	Route::get('profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
	Route::post('profile/update-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
	Route::post('profile/update-photo', [App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
	Route::post('profile/update', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');



	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');

    // Document routes
    Route::resource('documents', DocumentController::class);
    Route::post('documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');

    // Approval routes
    Route::get('approvals', [App\Http\Controllers\ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('approvals/{document}/approve', [App\Http\Controllers\ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{document}/reject', [App\Http\Controllers\ApprovalController::class, 'reject'])->name('approvals.reject');
    
    // Approval History routes
    Route::get('approval-history', [App\Http\Controllers\ApprovalHistoryController::class, 'index'])->name('approval-history.index');
    Route::get('approval-history/{approval}', [App\Http\Controllers\ApprovalHistoryController::class, 'show'])->name('approval-history.show');

    // User management routes (admin only)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::resource('divisions', App\Http\Controllers\DivisionController::class)->except(['show']);
});


Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});



Route::get('/login', function () {
    return view('session/login-session');
})->name('login');