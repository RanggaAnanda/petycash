<?php

use Illuminate\Support\Facades\Route;
// Controllers for master routings
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\StoreController;
use App\Http\Controllers\Master\VendorController;
use App\Http\Controllers\Form\UangMasukController;

// Controllers for daftar routings
use App\Http\Controllers\Daftar\PetycashController;
use App\Http\Controllers\Form\UangKeluarController;

// Controllers for Form routings
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Form\OmsetController as FormOmsetController;
use App\Http\Controllers\Daftar\OmsetController as DaftarOmsetController;


// Master Resource Routes
Route::prefix('master')->name('master.')->group(
    function () {
        Route::resource('users', UserController::class);
        Route::resource('stores', StoreController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('vendors', VendorController::class);
    }
);

// Daftar Resource Routes
Route::prefix('daftar')->name('daftar.')->group(
    function () {
        Route::get('omset', [DaftarOmsetController::class, 'index'])
            ->name('omset.index');

        Route::get('petycash', [PetycashController::class, 'index'])
            ->name('petycash.index');
    }
);

// Form Resource Routes
Route::prefix('form')->name('forms.')->group(
    function () {
        Route::get('omset', [FormOmsetController::class, 'create'])
            ->name('omset.create');
        Route::get('omset/edit', [FormOmsetController::class, 'edit'])
            ->name('omset.edit');


        Route::get('uang-masuk', [UangMasukController::class, 'create'])
            ->name('uang-masuk.create');
        Route::get('uang-masuk/edit', [UangMasukController::class, 'edit'])
            ->name('uang-masuk.edit');

        Route::get('uang-keluar', [UangKeluarController::class, 'create'])
            ->name('uang-keluar.create');
        Route::get('uang-keluar/edit', [UangKeluarController::class, 'edit'])
            ->name('uang-keluar.edit');
    }
);

// Rangga
Route::get('/', function () {
    return view('auth/login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

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


Route::prefix('akuntansi')->group(function () {

    Route::get('/', function () {
        return redirect('/akuntansi/jurnal');
    });

    Route::get('/jurnal', function () {
        return view('akuntansi.jurnal');
    })->name('akuntansi.jurnal');

    Route::get('/buku-besar', function () {
        return view('akuntansi.buku-besar');
    })->name('akuntansi.buku-besar');

    Route::get('/arus-kas', function () {
        return view('akuntansi.arus-kas');
    })->name('akuntansi.arus-kas');

    Route::get('/laba-rugi', function () {
        return view('akuntansi.laba-rugi');
    })->name('akuntansi.laba-rugi');

    Route::get('/neraca', function () {
        return view('akuntansi.neraca');
    })->name('akuntansi.neraca');
});

Route::get('/profile', function () {
    return view('profile.index');
})->name('profile');



// Zull
require __DIR__ . '/auth.php';

// Master routes (temporary stubs) — simplified
Route::prefix('master')->name('master.')->group(function () {

    $resources = [
        'users' => [
            'controller' => 'User',
            'name' => 'users',
        ],
        'stores' => [
            'controller' => 'Store',
            'name' => 'stores',
        ],
        'vendor' => [
            'controller' => 'Vendor',
            'name' => 'vendor',
        ],
        'kategori' => [
            'controller' => 'Kategori',
            'name' => 'kategori',
        ],
        'accounts' => [
            'controller' => 'Account',
            'name' => 'accounts',
        ],
    ];

    Route::prefix('master')->name('master.')->group(function () {

        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });


    Route::get('/kategori/{kategori}/subkategori', function ($kategori) {
        return view('master.kategori.subkategori', compact('kategori'));
    })->name('kategori.subkategori');
});




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
