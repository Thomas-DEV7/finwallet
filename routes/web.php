<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\AdminController;



Route::get('/', function () {
    return view('welcome');
});
# Screens functionals
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/wallet/deposit', function () {
        return view('wallet.deposit');
    })->name('wallet.deposit');

    Route::get('/wallet/transfer', function () {
        return view('wallet.transfer');
    })->name('wallet.transfer');

    Route::get('/wallet/transactions', function () {
        return view('wallet.transactions');
    })->name('wallet.transactions');
});




Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
    Route::post('/wallet/transfer', [WalletController::class, 'transfer']);
    Route::post('/wallet/reverse', [WalletController::class, 'reverse']);

    Route::post('/transactions/reversal-request', [WalletController::class, 'storeReversalRequest'])
    ->name('transactions.reversal.request');
});



Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/reversal-requests', [AdminController::class, 'index'])->name('admin.reversal.requests');
    Route::post('/admin/reversal-requests/{uuid}/approve', [AdminController::class, 'approve'])->name('admin.reversal.requests.approve');
    Route::post('/admin/reversal-requests/{uuid}/reject', [AdminController::class, 'reject'])->name('admin.reversal.requests.reject');
});


require __DIR__.'/auth.php';
