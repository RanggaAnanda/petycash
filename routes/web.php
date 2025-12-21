<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

Route::get('/', function () {
    return view('auth/login');
})->name('login');

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

    Route::get('edit/uang-masuk', function () {
        return view('form.uang-masuk-edit');
    })->name('form.edit.uang-masuk');

    Route::get('edit/uang-keluar', function () {
        return view('form.uang-keluar-edit');
    })->name('form.edit.uang-keluar');

    Route::get('edit/omset', function () {
        return view('form.omset-edit');
    })->name('form.edit.omset');
});

Route::prefix('daftar')->group(function () {

    Route::get('/omset', function () {
        return view('daftar.omset');
    })->name('daftar.omset');

    Route::get('/pettycash', function () {
        return view('daftar.pettycash');
    })->name('daftar.pettycash');
});

Route::prefix('laporan')->group(function () {

    Route::get('/omset', function () {
        return view('laporan.omset');
    })->name('laporan.omset');

    Route::get('/pettycash', function () {
        return view('laporan.petycash');
    })->name('laporan.petycash');
});

Route::get('/profile', function () {
    return view('profile.index');
})->name('profile');
require __DIR__.'/auth.php';

Route::get('/export/omset', [ExportController::class, 'exportOmset'])
    ->name('export.omset');



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

