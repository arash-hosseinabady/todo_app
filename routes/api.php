<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;

$request = new Request();
//dd($request->getMethod());

Route::get('/todo-list', [TodoListController::class, 'index'])->name('todo-list');
Route::post('/todo-list', [TodoListController::class, 'store'])->name('todo-list-store');
Route::get('/todo-list/{id}', [TodoListController::class, 'show'])->name('todo-list-show');
Route::put('/todo-list/{id}', [TodoListController::class, 'update'])->name('todo-list-update');
Route::delete('/todo-list/{id}', [TodoListController::class, 'destroy'])->name('todo-list-destroy');
Route::put('/todo-list/change-state/{id}/{state}', [TodoListController::class, 'changeState'])->name('todo-list-changeState');
