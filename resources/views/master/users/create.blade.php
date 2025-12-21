@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Master - Tambah User')

@php
    $roles = $roles ?? ['admin','user','repot','superadmin'];
    $tokos = $tokos ?? collect([
        (object)['id'=>1,'name'=>'Toko A'],
        (object)['id'=>2,'name'=>'Toko B'],
    ]);
@endphp

@section('content')
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            @include('daftar._user_form', [
                'action' => route('master.users.store'),
                'method' => 'POST',
                'roles' => $roles,
                'tokos' => $tokos,
                'submitText' => 'Simpan',
                'cancelUrl' => route('master.users.index'),
            ])
        </div>
    </div>
@endsection