<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('form')->group(function () {

    Route::get('/uang-masuk', function () {
        return view('form.uang-masuk');
    })->name('form.uang-masuk');

    Route::get('/uang-keluar', function () {
        return view('form.uang-keluar');
    })->name('form.uang-keluar');

    Route::get('/omset', function () {
        return view('form.omset');
    })->name('form.omset');

});

Route::prefix('daftar')->group(function () {

    Route::get('/omset', function () {
        return view('daftar.omset');
    })->name('daftar.omset');

    Route::get('/pettycash', function () {
        return view('daftar.pettycash');
    })->name('daftar.pettycash');
});


require __DIR__.'/auth.php';

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

