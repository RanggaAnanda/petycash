@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Master - Tambah Kategori')

@php
    $parents = $parents ?? collect([
        (object)['id'=>null,'name'=>'-- Root --'],
        (object)['id'=>1,'name'=>'Kategori A'],
        (object)['id'=>2,'name'=>'Kategori B'],
    ]);
@endphp

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="text-lg font-medium">Tambah Kategori</h3>
            <form action="{{ route('master.kategori.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf

                <div>
                    <x-input-label name="Nama" />
                    <x-text-input name="name" value="" placeholder="Nama kategori" />
                    <x-input-error name="name" />
                </div>

                <div>
                    <x-input-label name="Parent" />
                    <select name="parent_id" class="rounded border px-2 py-2 w-64">
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                    <a href="{{ route('master.kategori.index') }}" class="px-3 py-2 bg-gray-200 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
