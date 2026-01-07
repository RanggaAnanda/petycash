@extends('layouts.app')

@section('title', 'Edit Uang Keluar')
@section('page-title', 'Edit Uang Keluar')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <form action="{{ route('forms.uang-keluar.update', $item->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Tanggal -->
            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" value="{{ $item->tanggal }}" />
            </div>

            <!-- Kategori -->
            <div>
                <x-input-label name="Kategori" />
                <x-dropdown name="kategori_id" id="kategori" :options="$kategoris->pluck('name', 'id')->toArray()" :value="$item->kategori_id" required />
            </div>

            <!-- Sub Kategori -->
            <div>
                <x-input-label name="Sub Kategori" />
                <x-dropdown name="sub_kategori_id" id="sub_kategori" :options="[]" :value="$item->sub_kategori_id" />
            </div>

            <!-- Nominal -->
            <div>
                <x-input-label name="Jumlah" />
                <div class="flex">
                    <x-input-rp-label value="Rp" type="text" />
                    <x-input-rp type="text" name="nominal" value="{{ number_format($item->nominal, 0, ',', '.') }}"
                        required />
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" value="{{ $item->keterangan }}" />
            </div>

            <!-- Button -->
            <div>
                <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">UPDATE</button>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const dataKategori = @json($kategoris);
            const kategoriEl = document.getElementById('kategori');
            const subEl = document.getElementById('sub_kategori');
            const selectedSub = "{{ $item->sub_kategori_id ?? '' }}";

            function loadSub() {
                const kat = dataKategori.find(k => k.id == kategoriEl.value);
                subEl.innerHTML = '';

                if (!kat || kat.has_child === 'tidak') {
                    subEl.disabled = true;
                    return;
                }

                subEl.disabled = false;

                kat.sub_kategoris.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.textContent = sub.name;
                    if (sub.id == selectedSub) opt.selected = true;
                    subEl.appendChild(opt);
                });
            }

            kategoriEl.addEventListener('change', loadSub);

            if (kategoriEl.value) loadSub();
        });
    </script>
@endpush
