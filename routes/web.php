<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControler;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ScheduleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [MovieController::class, 'home'])->name('home');

Route::get('/schedule/detail/{movie_id}', [MovieController::class, 'movieSchedule'])->name('schedule_detail');

Route::middleware (['isUser'])->group(function () {
    Route::get('/schedules/{schedule_id}/hours/{hourId}', [ScheduleController::class, 'showSeats'])
    ->name('schedule.show_seats');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::post('/tickets', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticketId}/order', [TicketController::class, 'orderPage'])->name('order');
        Route::post('/qrcode', [TicketController::class, 'createQrcode'])->name('qrcode');
        Route::get('/{ticketId}/payment', [TicketController::class, 'paymentPage'])->name('payment');
        Route::patch('/{ticketId}/payment/status', [TicketController::class, 'updateStatusPayment'])->name('payment.status');
        Route::get('/{ticketId}/payment/proof', [TicketController::class, 'proofPayment'])->name('payment.proof');
        Route::get('/{ticketId}/pdf', [TicketController::class, 'exportPdf'])->name('export_pdf');

    });
});

Route::get('/cinemas/list', [CinemaController::class, 'cinemaList'])->name('cinemas.list');
Route::get('/cinemas/{cinema_id}/schedules', [CinemaController::class, 'cinemaSchedules'])->name('cinema.schedules');

// Middleware isGuest untuk login & signup
Route::middleware('isGuest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::get('/signup', [UserControler::class, 'create'])->name('signup');
});

// Proses login, signup, logout
Route::post('/auth', [UserControler::class, 'authentication'])->name('auth');
Route::post('/signup', [UserControler::class, 'register'])->name('signup.send_data');
Route::get('/logout', [UserControler::class, 'logout'])->name('logout');

// Admin group
Route::middleware(['isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tickets/chart', [TicketController::class, 'chart'])->name('tickets.chart');
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');

    // Cinema routes
    Route::prefix('cinemas')->name('cinemas.')->group(function () {
        Route::get('/index', [CinemaController::class, 'index'])->name('index');
        Route::get('/create', [CinemaController::class, 'create'])->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
        Route::get('trash', [CinemaController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CinemaController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [CinemaController::class, 'deletePermanent'])->name('delete-permanent');
        Route::get('export', [CinemaController::class, 'export'])->name('export');
        Route::get('/datatables', [CinemaController::class, 'datatables'])->name('datatables');
    });

    // Data Pengguna CRUD
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserControler::class, 'index'])->name('index');
        Route::get('/create', [UserControler::class, 'create'])->name('create');
        Route::post('/store', [UserControler::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserControler::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserControler::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserControler::class, 'destroy'])->name('delete');
        Route::prefix('admin')->group(function () {
    Route::resource('users', UserController::class);
        Route::get('trash', [UserControler::class, 'trash'])->name('trash');
            Route::patch('/restore/{id}', [UserControler::class, 'restore'])->name('restore');
            Route::delete('/delete-permanent/{id}', [UserControler::class, 'deletePermanent'])->name('delete-permanent');
Route::get('/export', [UserControler::class, 'export'])->name('export');
Route::get('/datatables', [UserControler::class, 'datatables'])->name('datatables');


});

    });
    Route::prefix('movies')->name('movies.')->group(function () {
        Route::get('/chart', [MovieController::class, 'chart'])->name('chart');
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('delete');
        Route::put('/nonactive/{id}', [MovieController::class, 'nonactive'])->name('nonactive');
        Route::get('trash', [MovieController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [MovieController::class, 'deletePermanent'])->name('delete-permanent');
        Route::get('/export', [MovieController::class, 'export'])->name('export');
        Route::get('/datatables', [MovieController::class, 'datatables'])->name('datatables');
    });
});

Route::get('movies/active', [MovieController::class, 'homeMovies'])->name('home.Movies.all');

Route::prefix('/staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function() {
        return view('staff.dashboard');
    })->name('dashboard');
    Route::prefix('promos')->name('promos.')->group(function () {
        Route::get('/', [PromoController::class, 'index'])->name('index');
        Route::get('/create', [PromoController::class, 'create'])->name('create');
        Route::post('/store', [PromoController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PromoController::class, 'destroy'])->name('delete');
        Route::get('trash', [PromoController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanent'])->name('delete-permanent');
        Route::get('export', [PromoController::class, 'export'])->name('export');
        Route::get('/datatables', [PromoController::class, 'datatables'])->name('datatables');
    });

    Route::prefix('/schedules')->name('schedules.')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('index');
    Route::post('/store', [ScheduleController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
    Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');
    Route::get('trash', [ScheduleController::class, 'trash'])->name('trash');
    Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
    Route::delete('/delete-permanent/{id}', [ScheduleController::class, 'deletePermanent'])->name('delete-permanent');
    Route::get('/export', [ScheduleController::class, 'export'])->name('export');
    Route::get('/datatables', [ScheduleController::class, 'datatables'])->name('datatables');
    });
});
