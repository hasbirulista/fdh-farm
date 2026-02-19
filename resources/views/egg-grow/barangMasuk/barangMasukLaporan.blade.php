<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Barang Masuk Egg Grow — {{ $periode }}</title>
    <style>
        <style>body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center; margin-bottom:5px;">Laporan Barang Masuk Egg Grow</h3>
    <p style="text-align:center; margin-bottom:20px;">
        <strong>Periode: </strong> — {{ $periode }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Telur</th>
                <th>Jumlah Barang Masuk (Kg)</th>
                <th>Harga / Kg</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_barang_masuk as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_barang_masuk)->format('d/m/Y') }}</td>
                    <td>{{ $item->jenis_telur }}</td>
                    <td class="text-right">{{ number_format($item->jumlah_barang_masuk/1000, 2, ',', '.') }} Kg</td>
                    <td class="text-right">Rp.{{ number_format($item->harga_kilo, 0, ',', '.') }}</td>
                    <td class="text-right">Rp.{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
