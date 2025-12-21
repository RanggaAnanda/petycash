@extends('layouts.app')

@section('title', 'Tambah Toko')
@section('page-title', 'Master - Tambah Toko')

@php
    $tokos = $tokos ?? collect();
@endphp

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="text-lg font-medium">Tambah Toko</h3>
            <form action="{{ route('master.toko.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf

                <div>
                    <x-input-label name="Nama" />
                    <x-text-input name="name" value="" placeholder="Nama toko" />
                    <x-input-error name="name" />
                </div>

                <div>
                    <x-input-label name="Alamat" />
                    <x-text-input name="alamat" value="" placeholder="Alamat" />
                    <x-input-error name="alamat" />
                </div>

                <div>
                    <x-input-label name="Telepon" />
                    <x-text-input name="phone" value="" placeholder="0812..." />
                    <x-input-error name="phone" />
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
                    <a href="{{ route('master.toko.index') }}" class="px-3 py-2 bg-gray-200 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
