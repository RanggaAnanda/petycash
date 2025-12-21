@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Master - Edit Kategori')

@php
    $id = request()->route('id') ?? null;
    if (!isset($kategori)) {
        $kategori = (object)['id'=>$id,'name'=>'Kategori '.$id,'parent_id'=>null];
    }
@endphp

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="text-lg font-medium">Edit Kategori: {{ $kategori->name }}</h3>
            <p class="text-sm text-gray-500">(This is a stub edit page)</p>
            <div class="mt-4">
                <a href="{{ route('master.kategori.index') }}" class="px-3 py-2 bg-gray-200 rounded">Kembali</a>
            </div>
        </div>
    </div>
@endsection