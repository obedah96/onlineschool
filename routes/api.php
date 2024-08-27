<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\test;
use App\Http\Controllers\SubscribersController;
use App\Http\Controllers\CourcesController;
use App\Http\Controllers\CourcesTimeController;
use App\Http\Controllers\GuestUsersController;
use App\Http\Controllers\FreeLessonsController;
use Spatie\GoogleCalendar\Event;



Route::get('/',function(){return "welcome to our api";});

Route::group(['prefix' => 'blogs'], function () {
    Route::post('/', [BlogsController::class, 'create']);
    Route::get('/', [BlogsController::class, 'index']);
    Route::put('/{id}', [BlogsController::class, 'update']);
    Route::delete('/', [BlogsController::class, 'destroy']);
});


Route::group(['prefix' => 'subscribers'], function () {
    Route::post('/', [SubscribersController::class, 'create']);
    Route::get('/', [SubscribersController::class, 'index']);
    Route::put('/{id}', [SubscribersController::class, 'update']);
    Route::delete('/', [SubscribersController::class, 'destroy']);
});



Route::group(['prefix' => 'courses'], function () {
    Route::post('/', [CourcesController::class, 'create']);
    Route::get('/', [CourcesController::class, 'index']);
    Route::put('/{id}', [CourcesController::class, 'update']);
    Route::delete('/', [CourcesController::class, 'destroy']);
});

Route::group(['prefix' => 'courses_time'], function () {
    Route::post('/', [CourcesTimeController::class, 'create']);
    Route::get('/', [CourcesTimeController::class, 'index']);
    Route::put('/{id}', [CourcesTimeController::class, 'update']);
    Route::delete('/', [CourcesTimeController::class, 'destroy']);
});

Route::group(['prefix' => 'guest_users'], function () {
    Route::post('/', [GuestUsersController::class, 'create']);
    Route::get('/', [GuestUsersController::class, 'index']);
    Route::put('/{id}', [GuestUsersController::class, 'update']);
    Route::delete('/', [GuestUsersController::class, 'destroy']);
});

Route::group(['prefix' => 'free_lessons'], function () {
    Route::post('/', [FreeLessonsController::class, 'create']);
    Route::get('/', [FreeLessonsController::class, 'index']);
    Route::put('/{id}', [FreeLessonsController::class, 'update']);
    Route::delete('/', [FreeLessonsController::class, 'destroy']);
});
    Route::get('create_event',[FreeLessonsController::class,'createEvent']);
       
