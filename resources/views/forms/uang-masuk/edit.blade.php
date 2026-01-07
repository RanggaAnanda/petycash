@extends('layouts.app')

@section('title', 'Edit Uang Masuk')
@section('page-title', 'Edit Uang Masuk')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">

        <form method="POST" action="{{ route('forms.uang-masuk.update', $item->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" value="{{ $item->tanggal }}" />
            </div>

            <div>
                <x-input-label name="Kategori" />
                <x-dropdown name="kategori_id" :options="$kategoris->pluck('name', 'id')" :value="$item->kategori_id" />
            </div>

            <x-dropdown name="sub_kategori_id" :options="['' => '-- Tanpa Sub Kategori --'] +
                ($subKategoris ?? collect())->pluck('name', 'id')->toArray()" :value="$item->sub_kategori_id" />



            <div>
                <x-input-label name="Jumlah" />
                <div class="flex">
                    <x-input-rp-label value="Rp" type="text" />
                    <x-input-rp type="text" name="nominal" value="{{ number_format($item->nominal, 0, ',', '.') }}" />
                </div>
            </div>

            <div>
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" value="{{ $item->keterangan }}" />
            </div>

            <div>
                <button class="mt-4 bg-blue-600 text-white px-6 py-2 rounded">
                    UPDATE
                </button>
            </div>

        </form>
    </div>
@endsection
