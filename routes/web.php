<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return '
        <div style="display:flex;flex-direction:column;justify-content:center;align-items:center;height:100vh;background:#f5f6fa;">
            <h1 style="font-family:sans-serif;font-size:2.5rem;color:#222;">
                LoR Character Search API Server
            </h1>
            <p style="font-size: 1.75rem">Access the <a href="http://localhost:5173/" target="_blank">front-end</a></p>
        </div>';

})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
