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
            @php
                $method = 'PATCH';
            @endphp

            <form action="{{ route('master.users.update', $user->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label name="TOKO" />
                        <x-dropdown name="toko_id" :options="array_merge(['' => 'Pilih Toko..'], ($tokos->pluck('name','id')->toArray() ?? []))" :value="old('toko_id', $user->toko_id ?? '')" />
                        @error('toko_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label name="Nama" />
                        <x-input name="name" value="{{ old('name', $user->name ?? '') }}" />
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label name="Email" />
                        <x-input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" />
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label name="Password" />
                        <x-input type="password" name="password" />
                        @if(isset($user))
                            <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                        @endif
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label name="Hak Akses" />
                        <x-dropdown name="role" :options="collect($roles)->mapWithKeys(function($r){ return [$r => ucfirst($r)]; })->toArray()" :value="old('role', $user->role ?? 'user')" />
                        @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">{{ 'Update' }}</button>
                    <a href="{{ route('master.users.index') }}" class="ml-3 text-sm text-gray-600">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection