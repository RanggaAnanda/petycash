@extends('layouts.app')

@section('title', 'Edit Omset')
@section('page-title', 'Edit Omset')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

        <form action="{{ route('forms.omset.update', $item->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" value="{{ $item->tanggal }}" />
            </div>

            <div>
                <x-input-label name="Toko" />
                <x-dropdown name="store_id" :options="$tokos->pluck('name', 'id')->toArray()" :selected="$item->store_id" required />
            </div>

            <div>
                <x-input-label name="Jumlah Omset" />
                <div class="flex">
                    <x-input-rp-label value="Rp" type="text" />
                    <x-input-rp name="nominal" value="{{ number_format($item->nominal, 0, ',', '.') }}" type="text"
                        required />

                </div>
            </div>

            <div>
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" value="{{ $item->keterangan }}" />
            </div>

            <div class="my-5 flex gap-3">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    UPDATE
                </button>

                <a href="{{ route('daftar.omset.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg">
                    BATAL
                </a>
            </div>

        </form>
    </div>
@endsection
