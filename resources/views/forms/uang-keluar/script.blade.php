<script>
    document.addEventListener('DOMContentLoaded', () => {

        const dataKategori = @json($kategoris);
        const kategoriEl = document.getElementById('kategori');
        const subEl = document.getElementById('sub_kategori');
        const selectedSub = "{{ $selectedSub ?? '' }}";

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
