<form action="{{ $store->id ?? '' ? route('master.stores.update', $store->id) : route('master.stores.store') }}" method="POST">
    @csrf
    @if(isset($store))
        @method('PATCH')
    @endif

    <div>
        <label>Kode</label>
        <input type="text" name="kode" value="{{ old('kode', $store->kode ?? '') }}">
        @error('kode') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label>Nama Toko</label>
        <input type="text" name="name" value="{{ old('name', $store->name ?? '') }}">
        @error('name') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded">Simpan</button>
</form>
