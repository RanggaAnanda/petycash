@extends('layouts.app')

@section('title', 'Form Uang Masuk')
@section('page-title', 'Form Uang Masuk')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">

        <form method="POST" action="{{ route('forms.uang-masuk.store') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" value="{{ date('Y-m-d') }}" />
            </div>

            <div>
                <x-input-label name="Kategori" />
                <x-dropdown id="kategori" name="kategori_id" :options="$kategoris->pluck('name', 'id')" placeholder="Pilih Kategori" required />
            </div>

            <div>
                <x-input-label name="Sub Kategori" />
                <x-dropdown id="sub_kategori" name="sub_kategori_id" :options="[]" placeholder="Pilih Sub Kategori" />
            </div>

            <div>
                <x-input-label name="Jumlah" />
                <div class="flex">
                    <x-input-rp-label value="Rp" type="text" />
                    <x-input-rp type="text" name="nominal" />
                </div>
            </div>

            <div>
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" />
            </div>

            <button class="mt-4 bg-blue-600 text-white px-6 py-2 rounded">
                SIMPAN
            </button>

        </form>
    </div>

    <script>
        const dataKategori = @json($kategoris);
        const kategori = document.getElementById('kategori');
        const sub = document.getElementById('sub_kategori');

        kategori.addEventListener('change', function() {
            sub.innerHTML = '';
            const pilih = dataKategori.find(k => k.id == this.value);

            if (!pilih || pilih.has_child !== 'ya') {
                sub.innerHTML = '<option>Tidak ada sub kategori</option>';
                sub.disabled = true;
                return;
            }

            sub.disabled = false;
            sub.innerHTML = '<option value="">Pilih Sub Kategori</option>';
            pilih.sub_kategoris.forEach(s => {
                sub.innerHTML += `<option value="${s.id}">${s.name}</option>`;
            });
        });
    </script>
@endsection
