<?php

use Illuminate\Support\Facades\Route;

// Dummy Auth Routes - All routes work without validation, just for testing/development

Route::middleware('guest')->group(function () {
    // Register
    Route::get('register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('register', function () {
        return redirect()->route('dashboard');
    });

    // Login
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', function () {
        return redirect()->route('dashboard');
    });

    // Forgot Password
    Route::get('forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('forgot-password', function () {
        return back()->with('status', 'Password reset link sent! (dummy)');
    })->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', function (\Illuminate\Http\Request $request) {
        return view('auth.reset-password', ['request' => $request]);
    })->name('password.reset');

    Route::post('reset-password', function () {
        return redirect()->route('login')->with('status', 'Password reset successful! (dummy)');
    })->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Email Verification
    Route::get('verify-email', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', function ($id, $hash) {
        return redirect()->route('dashboard')->with('status', 'Email verified! (dummy)');
    })->name('verification.verify');

    Route::post('email/verification-notification', function () {
        return back()->with('status', 'Verification link sent! (dummy)');
    })->name('verification.send');

    // Confirm Password
    Route::get('confirm-password', function () {
        return view('auth.confirm-password');
    })->name('password.confirm');

    Route::post('confirm-password', function () {
        return redirect()->intended(route('dashboard'));
    });

    // Update Password
    Route::put('password', function () {
        return redirect()->route('dashboard')->with('status', 'Password updated! (dummy)');
    })->name('password.update');

    // Logout
    Route::post('logout', function () {
        return redirect('/');
    })->name('logout');
});
