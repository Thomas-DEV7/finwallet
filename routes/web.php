<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\AdminController;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

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
Route::get('/dashboard', [WalletController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/transfer', [WalletController::class, 'transfer']);
    Route::post('/wallet/reverse', [WalletController::class, 'reverse']);
    Route::post('/transactions/reversal-request', [WalletController::class, 'storeReversalRequest'])
        ->name('transactions.reversal.request');
});


Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::put('/users/update', [AdminController::class, 'update'])->name('admin.users.update'); // Atualizar usuário
    Route::delete('/users/delete', [AdminController::class, 'delete'])->name('admin.users.delete'); // Excluir usuário
    Route::get('/admin/reversal-requests', [AdminController::class, 'reversalRequests'])->name('admin.reversal.requests');
    Route::post('/admin/reversal-requests/{uuid}/approve', [AdminController::class, 'approve'])->name('admin.reversal.requests.approve');
    Route::post('/admin/reversal-requests/{uuid}/reject', [AdminController::class, 'reject'])->name('admin.reversal.requests.reject');
});


require __DIR__ . '/auth.php';
