<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TicketController;


//authentication
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

//events
Route::get('events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);

//seats
Route::get('/events/{id}/seats', [EventController::class, 'getEventSeats']);
Route::get('/venues/{id}/seats', [VenueController::class, 'getVenueSeats']);
Route::post('/seats/block', [SeatController::class, 'blockSeats']);
Route::delete('/seats/release', [SeatController::class, 'releaseSeats']);

//reservations
Route::post('/reservations', [ReservationController::class, 'store']);
Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations/{id}', [ReservationController::class, 'show']);
Route::post('/reservations/{id}/confirm', [ReservationController::class, 'confirm']);
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

//Tickets
Route::get('/tickets', [TicketController::class, 'index']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);
Route::get('/tickets/{id}/download', [TicketController::class, 'download']);
Route::post('/tickets/{id}/transfer', [TicketController::class, 'transfer']);






Route::middleware('auth:api')->get('/auth/user', [AuthController::class, 'user']);

