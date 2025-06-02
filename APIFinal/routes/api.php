<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\ContactsController;

//Register & Login
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

//list menu
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/{id}', [MenuController::class, 'show']);

//order
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

//profil
// Rute untuk mengupdate nama user (di tabel users) profile
Route::get('/profile/{user_id}', [ProfileController::class, 'show']);
Route::patch('/profile/{user_id}', [ProfileController::class, 'update']);

//reservasi
Route::get('/reservations', [ReservationController::class, 'index']); // Melihat semua reservasi
Route::post('/reservations', [ReservationController::class, 'store']); // Membuat reservasi
Route::get('/reservations/{id}', [ReservationController::class, 'show']); // Melihat detail reservasi
Route::put('/reservations/{id}', [ReservationController::class, 'update']); // Mengupdate reservasi
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']); // Menghapus reservasi

//contacts
Route::get('/contacts', [ContactsController::class, 'index']); // Melihat semua pesan kontak
Route::post('/contacts', [ContactsController::class, 'store']); // Mengirim pesan kontak baru
Route::get('/contacts/{id}', [ContactsController::class, 'show']); // Melihat detail pesan kontak
Route::put('/contacts/{id}', [ContactsController::class, 'update']); // Mengupdate status pesan kontak
Route::delete('/contacts/{id}', [ContactsController::class, 'destroy']);