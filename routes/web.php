<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-pagination', function () {
    return dd(view()->exists('pagination.tailwind'));
});


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
