@extends('layouts.app')

@section('title', 'Edit Uang Keluar')
@section('page-title', 'Edit Uang Keluar')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <form action="{{ route('forms.uang-keluar.update', $item->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" value="{{ $item->tanggal }}" />
            </div>
            <div>
                <x-input-label name="Kategori" />
                <x-dropdown name="kategori_id" id="kategori" :options="$kategoris->pluck('name', 'id')->toArray()" :value="$item->kategori_id" required />
            </div>
            <div>
                <x-input-label name="Sub Kategori" />
                <x-dropdown name="sub_kategori_id" id="sub_kategori" :value="$item->sub_kategori_id" :options="[]" />
            </div>
            <div>
                <x-input-label name="Jumlah" />
                <div class="flex">
                    <x-input-rp-label value="Rp" type="text" />
                    <x-input-rp type="text" name="nominal" value="{{ number_format($item->nominal, 0, ',', '.') }}"
                        required />
                </div>
            </div>
            <div>
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" value="{{ $item->keterangan }}" />
            </div>

            <div>
                <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">UPDATE</button>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedSub = {{ $item->sub_kategori_id ?? 'null' }};
        $('#kategori').change(function() {
            let kategoriId = $(this).val();
            $.get('/form/get-sub-kategori/' + kategoriId, function(data) {
                let options = '<option value="">-- Pilih Sub Kategori --</option>';
                data.forEach(item => {
                    let selected = (item.id == selectedSub) ? 'selected' : '';
                    options += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                });
                $('#sub_kategori').html(options);
            });
        }).trigger('change');
    </script>
@endpush
