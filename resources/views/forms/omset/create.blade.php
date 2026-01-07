@extends('layouts.app')

@section('title', 'Tambah Omset')
@section('page-title', 'Tambah Omset')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        <form action="{{ route('forms.omset.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" value="{{ date('Y-m-d') }}" />
            </div>

            <div>
                <x-input-label name="Toko" />
                <x-dropdown name="store_id" :options="$tokos->pluck('name', 'id')->toArray()" placeholder="Pilih Toko" required />
            </div>

            <div>
                <x-input-label name="Jumlah Omset" />
                <div class="flex">
                    <x-input-rp-label value="Rp" type="text" />
                    <x-input-rp name="nominal" placeholder="Masukkan nominal" type="text" required />
                </div>
            </div>

            <div>
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" />
            </div>

            <div class="my-5">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    SIMPAN
                </button>
            </div>

        </form>
    </div>
@endsection
