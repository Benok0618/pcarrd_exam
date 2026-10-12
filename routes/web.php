<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProposalController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/proposals');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');

    Route::middleware('role:Proponent|Admin')->group(function () {
        Route::get('/proposals/create', [ProposalController::class, 'create'])->name('proposals.create');
        Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
        Route::get('/proposals/{proposal}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
        Route::put('/proposals/{proposal}', [ProposalController::class, 'update'])->name('proposals.update');
        Route::delete('/proposals/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
        Route::patch('/proposals/{proposal}/restore', [ProposalController::class, 'restore'])->name('proposals.restore');
    });

    Route::middleware('role:Reviewer|Admin')->group(function () {
        Route::patch('/proposals/{proposal}/status', [ProposalController::class, 'updateStatus'])->name('proposals.status');
    });

    Route::get('/proposals/{proposal}', [ProposalController::class, 'show'])->name('proposals.show');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    Route::middleware('role:Proponent|Admin')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::patch('/categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    });
});
