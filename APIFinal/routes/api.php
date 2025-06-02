<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Register & Login
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MenuController;

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/{id}', [MenuController::class, 'show']);