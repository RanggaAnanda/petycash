@extends('layouts.app')

@section('title', 'Edit Toko')
@section('page-title', 'Master - Edit Toko')

@php
    $id = request()->route('id') ?? null;
    if (!isset($toko)) {
        $toko = (object)['id'=>$id,'name'=>'Toko '.$id,'alamat'=>'-','phone'=>'0812345678'];
    }
@endphp

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="text-lg font-medium">Edit Toko: {{ $toko->name }}</h3>
            <p class="text-sm text-gray-500">(This is a stub edit page)</p>
            <div class="mt-4">
                <a href="{{ route('master.toko.index') }}" class="px-3 py-2 bg-gray-200 rounded">Kembali</a>
            </div>
        </div>
    </div>
@endsection