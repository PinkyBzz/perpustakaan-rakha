<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRatingController;
use App\Http\Controllers\BookReservationController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->middleware('verified')->name('dashboard');

    Route::get('/catalog', [BookController::class, 'catalog'])->name('books.catalog');
    Route::get('/books/{book}', [BookController::class, 'show'])
        ->whereNumber('book')
        ->name('books.show');
    Route::post('/books/{book}/ratings', [BookRatingController::class, 'store'])->name('books.ratings.store');

    Route::middleware('role:user')->group(function () {
        Route::post('/borrow', [BorrowRequestController::class, 'store'])->name('borrow.store');
        Route::post('/borrow/{borrowRequest}/return', [BorrowRequestController::class, 'requestReturn'])->name('borrow.request-return');
        
        // Reservations
        Route::post('/reservations', [BookReservationController::class, 'store'])->name('reservations.store');
        Route::delete('/reservations/{reservation}', [BookReservationController::class, 'cancel'])->name('reservations.cancel');
    });

    Route::middleware('role:admin,pegawai')->group(function () {
        Route::resource('books', BookController::class)->except(['show']);
        Route::get('/borrows', [BorrowRequestController::class, 'index'])->name('borrows.index');
    Route::post('/borrows/staff-create', [BorrowRequestController::class, 'staffCreate'])->name('borrows.staff-create');
        Route::post('/borrows/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])->name('borrows.approve');
        Route::post('/borrows/{borrowRequest}/reject', [BorrowRequestController::class, 'reject'])->name('borrows.reject');
        Route::post('/borrows/{borrowRequest}/confirm-return', [BorrowRequestController::class, 'confirmReturn'])->name('borrows.confirm-return');
        Route::get('/scanner', [BorrowRequestController::class, 'scanner'])->name('scanner');
        Route::post('/scanner/verify', [BorrowRequestController::class, 'verifyQR'])->name('scanner.verify');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
