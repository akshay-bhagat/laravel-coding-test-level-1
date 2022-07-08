<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EventController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

$apiVersion = 'v1/';

Route::group(['prefix' => $apiVersion], function ($router) {
    $router->get('events/', [EventController::class, 'index'])->name('events'); // Return All Events
    $router->get('/active-events', [EventController::class, 'getActiveEvents'])->name('active-events');   // Return all active Events  
    $router->get('events/{id}', [EventController::class, 'show'])->name('get_event');   // Get one Event by id
    $router->post('events/', [EventController::class, 'store'])->name('create_event');   // Create an Event  
    $router->put('events/{id}', [EventController::class, 'update'])->name('update_event');   // Create event if not exist, else update the Event  
    $router->patch('events/{id}', [EventController::class, 'partialUpdate'])->name('update_partial');   // Partially update Event
    $router->delete('events/{id}', [EventController::class, 'destroy'])->name('delete_event');   // Soft delete an Event 
    
    //Server side data caching with redis
    $router->get('/redis/events', [EventController::class, 'cacheEvents'])->name('cache_events');
});
