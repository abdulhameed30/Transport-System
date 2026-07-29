<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ManagersController;
use App\Http\Controllers\MovementOfficerController;
use App\Http\Controllers\TicketOfficerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'logIn']);

Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Manager Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/', [ManagersController::class, 'index'])->name('home');
    Route::get('/users', [ManagersController::class, 'getUsers'])->name('users');

    // User Management
    Route::get('/users/create', [UserController::class, 'create'])->name('create-user');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('edit-user');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Trips
    Route::get('/trips/completed', [ManagersController::class, 'completedTrips'])->name('completed-trips');
});


/*
|--------------------------------------------------------------------------
| Ticket Officer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['role:Ticket_Officer'])->prefix('ticket-officer')->name('ticket-officer.')->group(function () {
    Route::get('/', [TicketOfficerController::class, 'index'])->name('home');

    // Trips Management
    Route::get('/trips/create', [TicketOfficerController::class, 'createTrip'])->name('create-trip');
    Route::post('/trips', [TicketOfficerController::class, 'storeTrip'])->name('store-trip');
    Route::get('/trips/{id}/edit', [TicketOfficerController::class, 'editTrip'])->name('edit-trip');
    Route::put('/trips/{id}', [TicketOfficerController::class, 'updateTrip'])->name('update-trip');
    Route::delete('/trips/{id}', [TicketOfficerController::class, 'cancelledTrip'])->name('cancelled-trip');
    Route::get('/trips/completed', [TicketOfficerController::class, 'completedTrips'])->name('completed-trips');
});


/*
|--------------------------------------------------------------------------
| Driver Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['role:Driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/', [DriverController::class, 'index'])->name('home');
    Route::post('/trips/start', [DriverController::class, 'startTrip'])->name('start-trip');
    Route::post('/trips/update-stage', [DriverController::class, 'updateStage'])->name('update-stage');
    Route::get('/trips/completed', [DriverController::class, 'completedTrips'])->name('completed-trips');
});


/*
|--------------------------------------------------------------------------
| Movement Officer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['role:Movement_Officer'])->prefix('movement-officer')->name('movement_officer.')->group(function () {
    Route::get('/', [MovementOfficerController::class, 'index'])->name('home');
    Route::get('/trips/completed', [MovementOfficerController::class, 'completedTrips'])->name('completed-trips');
});
