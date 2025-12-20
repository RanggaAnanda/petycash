(function () {

    window.formatRupiah = function (value) {
        let number = value.replace(/[^,\d]/g, '');
        let sisa = number.length % 3;
        let rupiah = number.substr(0, sisa);
        let ribuan = number.substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return rupiah ? rupiah : '';
    };

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input.rupiah').forEach(function (input) {
            input.addEventListener('input', function () {
                this.value = formatRupiah(this.value);
            });
        });
    });

})();
