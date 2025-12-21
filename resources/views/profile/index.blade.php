@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
<div class="max-w-2xl space-y-6 text-lg">

    <!-- ================= INFORMASI TOKO ================= -->
    <div class="bg-white dark:bg-gray-800 border rounded-lg p-6 shadow">
        <h3 class="font-semibold mb-4 text-gray-800 dark:text-gray-100">
            Informasi Toko
        </h3>

        <div class="space-y-3">
            <div class="flex">
                <span class="w-40 font-medium text-gray-600 dark:text-gray-300">
                    Nama Toko
                </span>
                <span class="text-gray-800 dark:text-gray-100">
                    : Planet Fashion Bandung
                </span>
            </div>

            <div class="flex">
                <span class="w-40 font-medium text-gray-600 dark:text-gray-300">
                    Email
                </span>
                <span class="text-gray-800 dark:text-gray-100">
                    : pf_bandung@planetfashion.id
                </span>
            </div>
        </div>
    </div>

    <!-- ================= GANTI PASSWORD ================= -->
    <div class="bg-white dark:bg-gray-800 border rounded-lg p-6 shadow">
        <h3 class="font-semibold mb-4 text-gray-800 dark:text-gray-100">
            Ganti Password
        </h3>

        <form class="space-y-5">

            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-600 dark:text-gray-300">
                    Password Lama
                </label>
                <input type="password"
                       class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600
                              bg-gray-50 dark:bg-gray-700
                              text-gray-800 dark:text-gray-100
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-600 dark:text-gray-300">
                    Password Baru
                </label>
                <input type="password"
                       class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600
                              bg-gray-50 dark:bg-gray-700
                              text-gray-800 dark:text-gray-100
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-600 dark:text-gray-300">
                    Konfirmasi
                </label>
                <input type="password"
                       class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600
                              bg-gray-50 dark:bg-gray-700
                              text-gray-800 dark:text-gray-100
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-500 hover:bg-blue-600
                               text-white font-medium">
                    Simpan Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
