@extends('layouts.app')

@section('title', 'Tambah Uang Keluar')
@section('page-title', 'Tambah Uang Keluar')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        <form action="{{ route('forms.uang-keluar.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" value="{{ date('Y-m-d') }}" />
            </div>

            <div>
                <x-input-label name="Kategori" />
                <x-dropdown name="kategori_id" id="kategori" :options="$kategoris->pluck('name', 'id')->toArray()" required />
            </div>

            <div>
                <x-input-label name="Sub Kategori" />
                <x-dropdown name="sub_kategori_id" id="sub_kategori" :options="[]" />
            </div>

            <div>
                <x-input-label name="Jumlah" />
                <div class="flex">
                    <x-input-rp-label type="text" value="Rp" />
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

@push('scripts')
    @include('forms.uang-keluar.script')
@endpush
