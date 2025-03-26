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

    Route::get('/Summary_Items_Procured', function () {
        return view('livewire.items-procured-index');
    })->name('Summary_Items_Procured');
});
