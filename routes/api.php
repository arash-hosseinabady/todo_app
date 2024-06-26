<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AclController;

Route::post('register', [AuthController::class, 'register'])->name('auth-register');
Route::post('login', [AuthController::class, 'login'])->name('auth-login');

Route::post('create-permission', [\App\Http\Controllers\AclController::class, 'createPermission'])->name('create-permission');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('acl')->name('acl.')->group(function () {
            Route::get('permissions', [AclController::class, 'permissions'])->name('permissions')
                ->middleware(['permission:' . PERMISSIONS['acl']['view']]);
            Route::get('roles', [AclController::class, 'roles'])->name('roles');
            Route::post('role', [AclController::class, 'createRole'])->name('createRole');
            Route::put('role/{id}', [AclController::class, 'editRole'])->name('editRole')
                ->where('id', '[0-9]+');
            Route::get('role/{id}', [AclController::class, 'showRole'])->name('showRole')
                ->where('id', '[0-9]+');
            Route::delete('role/{id}', [AclController::class, 'deleteRole'])->name('deleteRole')
                ->where('id', '[0-9]+');
            Route::post('permissions-to-user/{userId}', [AclController::class, 'permissionsToUser'])->name('permissionsToUser')
                ->where('userId', '[0-9]+');
            Route::post('permissions-to-role/{roleId}', [AclController::class, 'permissionsToRole'])->name('permissionsToRole')
                ->where('roleId', '[0-9]+');
            Route::post('roles-to-user/{userId}', [AclController::class, 'rolesToUser'])->name('rolesToUser')
                ->where('userId', '[0-9]+');
    });

    Route::prefix('notification')->name('notification.')->group(function () {
        Route::get('', [AuthController::class, 'notification'])->name('list');
        Route::get('mark-as-read', [AuthController::class, 'notificationMarkAsRead'])->name('mark-as-read');
    });

    Route::post('/todo-list', [TodoListController::class, 'store'])->name('todo-list-store');
    Route::put('/todo-list/{id}', [TodoListController::class, 'update'])->name('todo-list-update');
    Route::delete('/todo-list/{id}', [TodoListController::class, 'destroy'])->name('todo-list-destroy');
    Route::get('/todo-list/{id}', [TodoListController::class, 'show'])->name('todo-list-show')
        ->middleware(['permission:' . PERMISSIONS['todo_list']['view']]);
    Route::put('/todo-list/change-state/{id}/{state}', [TodoListController::class, 'changeState'])->name('todo-list-changeState');
});
