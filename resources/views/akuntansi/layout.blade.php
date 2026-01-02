<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f4f4f4; }
        .text-right { text-align: right; }
        nav a { margin-right: 10px; }
    </style>
</head>
<body>

@include('akuntansi.menu')

<hr>

@yield('content')

<!-- DATA & SERVICE -->
<script src="{{ asset('js/coa.js') }}"></script>
<script src="{{ asset('js/jurnal.mock.js') }}"></script>
<script src="{{ asset('js/jurnal.service.js') }}"></script>
<script src="{{ asset('js/laporan.service.js') }}"></script>

@yield('script')

</body>
</html>
