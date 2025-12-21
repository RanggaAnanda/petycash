@extends('layouts.app')

@section('title', 'Tambah Vendor')
@section('page-title', 'Master - Tambah Vendor')

@php
    $vendors = $vendors ?? collect();
@endphp

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="text-lg font-medium">Tambah Vendor</h3>
            <form action="{{ route('master.vendor.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf

                <div>
                    <x-input-label name="Nama" />
                    <x-text-input name="name" value="" placeholder="Nama vendor" />
                    <x-input-error name="name" />
                </div>

                <div>
                    <x-input-label name="Contact Person" />
                    <x-text-input name="contact_person" value="" placeholder="Nama contact" />
                    <x-input-error name="contact_person" />
                </div>

                <div>
                    <x-input-label name="Telepon" />
                    <x-text-input name="phone" value="" placeholder="0812..." />
                    <x-input-error name="phone" />
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
                    <a href="{{ route('master.vendor.index') }}" class="px-3 py-2 bg-gray-200 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
