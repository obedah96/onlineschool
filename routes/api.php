<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\test;
use App\Http\Controllers\SubscribersController;
use App\Http\Controllers\CourcesController;
use App\Http\Controllers\CourcesTimeController;

Route::get('/',function(){return "welcome to our api";});
Route::post('blogs',[BlogsController::class,'create']);
Route::get('blogs',[BlogsController::class,'index']);
Route::post('blogs/update',[BlogsController::class,'update']);
Route::delete('blogs',[BlogsController::class,'destroy']);


 Route::post('subscribers',[SubscribersController::class,'create']);
Route::get('subscribers',[SubscribersController::class,'index']);
Route::post('subscribers/update',[SubscribersController::class,'update']);
Route::delete('subscribers',[SubscribersController::class,'destroy']);



Route::post('cources',[CourcesController::class,'create']);
Route::get('cources',[CourcesController::class,'index']);
Route::post('cources/update',[CourcesController::class,'update']);
Route::delete('cources',[CourcesController::class,'destroy']);

Route::post('cources_time',[CourcesTimeController::class,'create']);
Route::get('cources_time',[CourcesTimeController::class,'index']);
Route::post('cources_time/update',[CourcesTimeController::class,'update']);
Route::delete('cources_time',[CourcesTimeController::class,'destroy']);