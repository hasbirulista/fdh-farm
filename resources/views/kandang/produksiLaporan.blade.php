<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Produksi {{ $nama_kandang }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center; margin-bottom:5px;">
        Laporan Produksi Telur
    </h3>

    <p style="text-align:center; margin-bottom:10px;">
        <strong>{{ $nama_kandang }}</strong> — {{ $periode }}
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                @if ($tampilkanKandang)
                    <th>Kandang</th>
                @endif
                <th>Populasi</th>
                <th>Usia</th>
                <th>Telur</th>
                <th>Mati</th>
                <th>Apkir</th>
                <th>Butir</th>
                <th>TP</th>
                <th>Jumlah Gram</th>
                <th>Produksi (%)</th>
                <th>Kegiatan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chunks as $chunk)
                @foreach ($chunk as $produksi)
                    <tr>
                        <td>{{ $produksi->tanggal_produksi }}</td>
                        <td>{{ $produksi->nama_kandang }}</td>
                        ...
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>

</html>
