@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Master - Edit User')

@php
    $id = request()->route('id') ?? null;
    if (!isset($user)) {
        try {
            $user = \App\Models\User::findOrFail($id);
        } catch (\Throwable $e) {
            $user = (object)[ 'id' => $id, 'name' => 'User '.$id, 'email' => "user{$id}@example.com", 'role' => 'user', 'toko_id' => 1 ];
        }
    }
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
                'action' => route('master.users.update', $user->id),
                'method' => 'PATCH',
                'roles' => $roles,
                'tokos' => $tokos,
                'user' => $user,
                'submitText' => 'Update',
                'cancelUrl' => route('master.users.index'),
            ])
        </div>
    </div>
@endsection