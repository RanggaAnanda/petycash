<?php

use Illuminate\Support\Facades\Route;
// Controllers for master routings
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\StoreController;
use App\Http\Controllers\Master\VendorController;
use App\Http\Controllers\Form\UangMasukController;
use App\Http\Controllers\Master\AccountController;


// Controllers for daftar routings
use App\Http\Controllers\Daftar\PetycashController;
use App\Http\Controllers\Form\UangKeluarController;

// Controllers for Form routings
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Laporan\AkuntansiController;
use App\Http\Controllers\Master\SubKategoriController;
use App\Http\Controllers\Form\OmsetController as FormOmsetController;
use App\Http\Controllers\Daftar\OmsetController as DaftarOmsetController;

// Master Resource Routes
Route::prefix('master')->name('master.')->group(
    function () {
        Route::resource('users', UserController::class);
        Route::resource('stores', StoreController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('vendors', VendorController::class);
        Route::resource('accounts', AccountController::class);
    }
);

Route::get('/master/kategori/{kategori}/sub', [SubKategoriController::class, 'index'])->name('master.subkategori.index');
Route::post('/master/kategori/{kategori}/sub', [SubKategoriController::class, 'store'])->name('master.subkategori.store');
Route::delete('/master/subkategori/{id}', [SubKategoriController::class, 'destroy'])->name('master.subkategori.destroy');

// web.php
Route::get('/master/kategori/get-next-code/{status}', [KategoriController::class, 'getNextCode']);

// Daftar Resource Routes
Route::prefix('daftar')->name('daftar.')->group(
    function () {
        // routes/web.php
        Route::get('petycash', [PetycashController::class, 'index'])
            ->name('petycash.index');

        Route::post('petycash/filter', [PetycashController::class, 'filter'])
            ->name('petycash.filter');

        Route::get('petycash', [PetycashController::class, 'index'])
            ->name('petycash.index');

        // Tambahkan ini di dalam Route::prefix('daftar')->name('daftar.')->group(...)
        Route::get('omset', [DaftarOmsetController::class, 'index'])->name('omset.index');
    }
);

// Form Resource Routes
Route::prefix('form')->name('forms.')->group(function () {

    // Omset
    Route::resource('omset', FormOmsetController::class)
        ->only(['create', 'store', 'edit', 'update']);

    // Uang Masuk
    Route::resource('uang-masuk', UangMasukController::class)
        ->only(['create', 'store', 'edit', 'update']);

    // Uang Keluar
    Route::resource('uang-keluar', UangKeluarController::class)
        ->only(['create', 'store', 'edit', 'update']);

    // AJAX / Helper
    Route::get(
        'get-sub-kategori/{kategoriId}',
        [UangKeluarController::class, 'getSubKategori']
    )->name('get-sub-kategori');
});


Route::get('/api/subkategori/{kategoriId}', [UangKeluarController::class, 'getSubKategori']);

// Rangga
Route::get('/', function () {
    return view('dashboard');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/profile', function () {
    return view('profile.index');
})->name('profile');

Route::prefix('laporan/pettycash')->name('laporan.pettycash.')->group(function () {
    Route::get('/jurnal', [AkuntansiController::class, 'jurnal'])->name('jurnal');
    Route::get('/buku-besar', [AkuntansiController::class, 'bukuBesar'])->name('buku-besar');
    Route::get('/laba-rugi', [AkuntansiController::class, 'labaRugi'])->name('laba-rugi');
    Route::get('/neraca', [AkuntansiController::class, 'neracaSaldo'])->name('neraca');
    Route::get('/arus-kas', [AkuntansiController::class, 'arusKas'])->name('arus-kas');
});



// // Zull
// require __DIR__ . '/auth.php';

// // Master routes (temporary stubs) — simplified
// Route::prefix('master')->name('master.')->group(function () {

//     $resources = [
//         'users' => [
//             'controller' => 'User',
//             'name' => 'users',
//         ],
//         'stores' => [
//             'controller' => 'Store',
//             'name' => 'stores',
//         ],
//         'vendor' => [
//             'controller' => 'Vendor',
//             'name' => 'vendor',
//         ],
//         'kategori' => [
//             'controller' => 'Kategori',
//             'name' => 'kategori',
//         ],
//         'accounts' => [
//             'controller' => 'Account',
//             'name' => 'accounts',
//         ],
//     ];

//     Route::prefix('master')->name('master.')->group(function () {

//         Route::get('users', [UserController::class, 'index'])->name('users.index');
//         Route::post('users', [UserController::class, 'store'])->name('users.store');
//         Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
//         Route::patch('users/{id}', [UserController::class, 'update'])->name('users.update');
//         Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
//     });


//     Route::get('/kategori/{kategori}/subkategori', function ($kategori) {
//         return view('master.kategori.subkategori', compact('kategori'));
//     })->name('kategori.subkategori');
// });




// Route::get('/export/omset', [ExportController::class, 'exportOmset'])
//     ->name('export.omset');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Route::prefix('form')->group(function () {

//     Route::get('/uang-masuk', function () {
//         return view('form.uang-masuk');
//     })->name('form.uang-masuk');

//     Route::get('/uang-keluar', function () {
//         return view('form.uang-keluar');
//     })->name('form.uang-keluar');

//     Route::get('/omset', function () {
//         return view('form.omset');
//     })->name('form.omset');

//     Route::get('edit/uang-masuk', function () {
//         return view('form.uang-masuk-edit');
//     })->name('form.edit.uang-masuk');

//     Route::get('edit/uang-keluar', function () {
//         return view('form.uang-keluar-edit');
//     })->name('form.edit.uang-keluar');

//     Route::get('edit/omset', function () {
//         return view('form.omset-edit');
//     })->name('form.edit.omset');
// });

// Route::prefix('daftar')->group(function () {

//     Route::get('/omset', function () {
//         return view('daftar.omset');
//     })->name('daftar.omset');

//     Route::get('/pettycash', function () {
//         return view('daftar.pettycash');
//     })->name('daftar.pettycash');
// });

// Route::prefix('laporan')->group(function () {
//     Route::get('/omset', function () {
//         return view('laporan.omset');
//     })->name('laporan.omset');

//     Route::get('/pettycash', function () {
//         return view('laporan.petycash');
//     })->name('laporan.petycash');
// });