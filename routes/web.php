<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// root: send guests to login, signed-in users to their home page
Route::get('/', function () {
    return redirect(LoginController::homeFor(auth()->user()) ?? route('login'));
})->name('home');

// authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:10,1')->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// admin pages
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard.dashboardAdmin', ['title' => __('Dashboard')]);
    })->name('dashboard');

    Route::get('/liveview', function () {
        return view('pages.liveview.index', [
            'title' => __('Live View'),
            'go2rtcUrl' => config('cctv.go2rtc_url'),
            'playerMode' => config('cctv.player_mode'),
            'cameras' => config('cctv.test_cameras'),
        ]);
    })->name('liveview');
});
