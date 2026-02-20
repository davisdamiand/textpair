<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FontPairController;

Route::get('/', [FontPairController::class, 'index'])->name('fontpair.index');
Route::post('/', [FontPairController::class, 'store'])->name('fontpair.store');

//Delete
Route::delete('/{id}', [FontPairController::class, 'delete'])->name('fontpair.delete');

