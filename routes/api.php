<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register'])->name('auth-register');
Route::post('login', [AuthController::class, 'login'])->name('auth-login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('auth-logout');

    Route::get('/todo-list', [TodoListController::class, 'index'])->name('todo-list');
    Route::post('/todo-list', [TodoListController::class, 'store'])->name('todo-list-store');
    Route::get('/todo-list/{id}', [TodoListController::class, 'show'])->name('todo-list-show');
    Route::put('/todo-list/{id}', [TodoListController::class, 'update'])->name('todo-list-update');
    Route::delete('/todo-list/{id}', [TodoListController::class, 'destroy'])->name('todo-list-destroy');
    Route::put('/todo-list/change-state/{id}/{state}', [TodoListController::class, 'changeState'])->name('todo-list-changeState');
});
