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

// Master routes (temporary stubs) — simplified
Route::prefix('master')->name('master.')->group(function () {
    $resources = [
        // uri => [viewPrefix, routeNameBase]
        'users' => ['view' => 'master.users', 'name' => 'users'],
        'tokos' => ['view' => 'master.tokos', 'name' => 'toko'], // keep "toko" singular in route names for backward compatibility
        'kategori' => ['view' => 'master.kategori', 'name' => 'kategori'],
        'vendor' => ['view' => 'master.vendor', 'name' => 'vendor'],
    ];

    foreach ($resources as $uri => $info) {
        $view = $info['view'];
        $base = $info['name'];

        Route::get("/{$uri}", function () use ($view) {
            return view("{$view}.index");
        })->name("{$base}.index");

        Route::get("/{$uri}/create", function () use ($view) {
            return view("{$view}.create");
        })->name("{$base}.create");

        Route::post("/{$uri}", function () use ($base) {
            return redirect()->route("master.{$base}.index")->with('success', "(stub) " . ucfirst($base) . " created.");
        })->name("{$base}.store");

        Route::get("/{$uri}/{id}/edit", function ($id) use ($view) {
            return view("{$view}.edit");
        })->name("{$base}.edit");

        Route::patch("/{$uri}/{id}", function ($id) use ($base) {
            return redirect()->route("master.{$base}.index")->with('success', "(stub) " . ucfirst($base) . " updated: " . $id);
        })->name("{$base}.update");

        Route::delete("/{$uri}/{id}", function ($id) use ($base) {
            return redirect()->route("master.{$base}.index")->with('success', "(stub) " . ucfirst($base) . " deleted: " . $id);
        })->name("{$base}.destroy");
    }

    // Subkategori route (show subkategori for a kategori)
    Route::get('/kategori/{id}/subkategori', function ($id) {
        // simple stub: pass kategori object to view; real implementation should use model
        $kategori = (object)[ 'id' => $id, 'name' => "Kategori " . $id ];
        return view('master.kategori.subkategori', compact('kategori'));
    })->name('kategori.subkategori');
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
