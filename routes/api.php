<?php

use App\Http\Controllers\Api\v1\SearchController;
use App\Http\Controllers\Api\v1\TheOneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(static function () {
    Route::get('/character', [TheOneController::class, 'index']);
    Route::get('/searchlist', [SearchController::class, 'getSearchLists']);
    Route::post('/searchlist', [SearchController::class, 'createSearchList']);
    Route::delete('/searchlist/{id}', [SearchController::class, 'removeSearchList']);
    Route::post('/searchlist/{id}/searches', [SearchController::class, 'createSearch']);
    Route::get('/searchlist/{id}/searches', [SearchController::class, 'getSearches']);
    Route::delete('/searchlist/{listId}/searches/{searchId}', [SearchController::class, 'removeSearch']);
    Route::get('/sanctum/csrf-cookie', action: array(CsrfCookieController::class, 'show'));
})->middleware('auto-login');
