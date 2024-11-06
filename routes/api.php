<?php

use App\Http\Controllers\TodosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->controller(TodosController::class)->group(function () {
    Route::get('/todos', 'index');
    Route::get('/todos/{id}', 'get');
    Route::post('/todos', 'store');
    Route::patch('/todos/{id}', 'update');
    Route::put('/todos/{id}', 'updatePUT');
    Route::delete('/todos/{id}', 'destroy');
});
