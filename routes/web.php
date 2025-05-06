<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Students;
use App\Livewire\Books;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('students', Students::class)->name('students')->middleware('auth:sanctum');
Route::get('books', Books::class)->name('books')->middleware('auth:sanctum');