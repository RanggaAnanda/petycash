<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

// Rangga
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


// Zull
require __DIR__ . '/auth.php';

// Master routes (temporary stubs)
Route::prefix('master')->group(function () {

    // Users
    Route::get('/users', function () {
        return view('master.users.index');
    })->name('master.users.index');

    Route::get('/users/create', function () {
        return view('master.users.create');
    })->name('master.users.create');

    Route::post('/users', function () {
        return redirect()->route('master.users.index');
    })->name('master.users.store');

    Route::get('/users/{id}/edit', function ($id) {
        return view('master.users.edit');
    })->name('master.users.edit');

    Route::patch('/users/{id}', function ($id) {
        return redirect()->route('master.users.index')->with('success', '(stub) User updated: ' . $id);
    })->name('master.users.update');

    Route::delete('/users/{id}', function ($id) {
        return redirect()->route('master.users.index')->with('success', '(stub) User deleted: ' . $id);
    })->name('master.users.destroy');

    // Tokos
    Route::get('/tokos', function () {
        return view('master.tokos.index');
    })->name('master.toko.index');

    Route::get('/tokos/create', function () {
        return view('master.tokos.create');
    })->name('master.toko.create');

    Route::post('/tokos', function () {
        return redirect()->route('master.toko.index')->with('success', '(stub) Toko created.');
    })->name('master.toko.store');

    Route::get('/tokos/{id}/edit', function ($id) {
        return view('master.tokos.edit');
    })->name('master.toko.edit');

    Route::patch('/tokos/{id}', function ($id) {
        return redirect()->route('master.toko.index')->with('success', '(stub) Toko updated: ' . $id);
    })->name('master.toko.update');

    Route::delete('/tokos/{id}', function ($id) {
        return redirect()->route('master.toko.index')->with('success', '(stub) Toko deleted: ' . $id);
    })->name('master.toko.destroy');
    // Kategori 
    Route::get('/kategori', function () {
        return view('master.kategori.index');
    })->name('master.kategori.index');

    Route::get('/kategori/create', function () {
        return view('master.kategori.create');
    })->name('master.kategori.create');

    Route::post('/kategori', function () {
        return redirect()->route('master.kategori.index')->with('success', '(stub) Kategori created.');
    })->name('master.kategori.store');

    Route::get('/kategori/{id}/edit', function ($id) {
        return view('master.kategori.edit');
    })->name('master.kategori.edit');

    Route::patch('/kategori/{id}', function ($id) {
        return redirect()->route('master.kategori.index')->with('success', '(stub) Kategori updated: ' . $id);
    })->name('master.kategori.update');

    Route::delete('/kategori/{id}', function ($id) {
        return redirect()->route('master.kategori.index')->with('success', '(stub) Kategori deleted: ' . $id);
    })->name('master.kategori.destroy');
    // Vendor
    Route::get('/vendor', function () {
        return view('master.vendor.index');
    })->name('master.vendor.index');

    Route::get('/vendor/create', function () {
        return view('master.vendor.create');
    })->name('master.vendor.create');

    Route::post('/vendor', function () {
        return redirect()->route('master.vendor.index')->with('success', '(stub) Vendor created.');
    })->name('master.vendor.store');

    Route::get('/vendor/{id}/edit', function ($id) {
        return view('master.vendor.edit');
    })->name('master.vendor.edit');

    Route::patch('/vendor/{id}', function ($id) {
        return redirect()->route('master.vendor.index')->with('success', '(stub) Vendor updated: ' . $id);
    })->name('master.vendor.update');

    Route::delete('/vendor/{id}', function ($id) {
        return redirect()->route('master.vendor.index')->with('success', '(stub) Vendor deleted: ' . $id);
    })->name('master.vendor.destroy');
});


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
