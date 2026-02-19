<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>
    <style>
        body {
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

        .total-row td {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center; margin-bottom:5px;">Laporan Pengeluaran FDH FARM</h3>
    <p style="text-align:center; margin-bottom:20px;">
        <strong>Periode: </strong> — {{ $periode }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Detail</th>
                <th>Berat</th>
                <th>Harga / Kg</th>
                <th>Keterangan</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_pengeluaran as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($item->jenis_pengeluaran) }}</td>
                    <td>{{ $item->jenis_pengeluaran === 'pakan' ? $item->jenis_pakan : $item->nama_pengeluaran }}</td>
                    <td>
                        {{ $item->jenis_pengeluaran === 'pakan' ? number_format($item->berat_total, 0, ',', '.') . ' Kg' : '-' }}
                    </td>
                    <td>
                        {{ $item->jenis_pengeluaran === 'pakan' ? 'Rp ' . number_format($item->harga_kilo, 0, ',', '.') : '-' }}
                    </td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>

        {{-- TOTAL --}}
        <tfoot>
            <tr class="total-row">
                <td colspan="7" style="text-align:right;">TOTAL PENGELUARAN</td>
                <td>
                    Rp {{ number_format($data_pengeluaran->sum('total_harga'), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
