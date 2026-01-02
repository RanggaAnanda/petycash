@extends('layouts.app')

@section('title', 'Edit Toko')
@section('page-title', 'Master - Edit Toko')

@php
    $id = request()->route('id') ?? null;
    if (!isset($toko)) {
        // Fallback demo toko when none provided
        $toko = (object)['id'=>$id,'name'=>'Toko '.$id,'alamat'=>'-','phone'=>'0812345678'];
    }
@endphp

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="text-lg font-medium">Edit Toko: {{ $toko->name }}</h3>

            <form action="{{ route('master.toko.update', $toko->id) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label name="Nama" />
                    <x-text-input name="name" value="{{ old('name', $toko->name ?? '') }}" placeholder="Nama toko" />
                    <x-input-error name="name" />
                </div>

                <div>
                    <x-input-label name="Alamat" />
                    <x-text-input name="alamat" value="{{ old('alamat', $toko->alamat ?? '') }}" placeholder="Alamat" />
                    <x-input-error name="alamat" />
                </div>

                <div>
                    <x-input-label name="Telepon" />
                    <x-text-input name="phone" value="{{ old('phone', $toko->phone ?? '') }}" placeholder="0812..." />
                    <x-input-error name="phone" />
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Update</button>
                    <a href="{{ route('master.toko.index') }}" class="px-3 py-2 bg-gray-200 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection