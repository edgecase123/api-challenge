<?php

use App\Http\Controllers\Api\v1\TheOneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(static function () {
   Route::get('/character', [TheOneController::class, 'index']);
});
