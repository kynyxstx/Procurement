<?php

use App\Livewire\NewIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\SupplierDirectoryIndex;
use App\Livewire\ItemsProcuredIndex;
use App\Livewire\ProcurementMonitoringIndex;
use App\Livewire\ProcurementOutgoingIndex;

Route::get('/test-pagination', fn() => dd(view()->exists('pagination.tailwind')));

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    //Straight after logging in, redirect to the dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/Supplier_Directory', SupplierDirectoryIndex::class)->name('Supplier_Directory');
    Route::get('/Summary_Items_Procured', ItemsProcuredIndex::class)->name('Summary_Items_Procured');
    Route::get('/Procurement_Monitoring', ProcurementMonitoringIndex::class)->name('Procurement_Monitoring');
    Route::get('/Procurement-Outgoing', ProcurementOutgoingIndex::class)->name('Procurement-Outgoing');
});