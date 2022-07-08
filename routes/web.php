<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

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
require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// only authenticated users can create, update and delete events
Route::group(['middleware' => 'auth'], function(){
    Route::get('/events',               [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create',        [EventController::class, 'create'])->name('events.create');
    Route::get('/events/{id}',          [EventController::class, 'show'])->name('events.show');
    Route::get('events/{id}/edit',      [EventController::class, 'edit'])->name('events.edit');
    Route::put('events/{id}/edit',      [EventController::class, 'update'])->name('events.update');
    Route::post('events/event_store',   [EventController::class, 'store'])->name('events.store');
    Route::delete('events/delete/{id}', [EventController::class, 'destroy'])->name('events.destroy');    
});

Route::post('/search_external_api',[EventController::class, 'search'])->name('api.search');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
