<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran - {{ $periode }}</title>
    <style>
        <style>body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
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

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>LAPORAN PENGELUARAN EGG GROW</h2>
    <p style="text-align:center; margin-bottom:10px;">
        <strong>Periode:</strong> {{ $periode }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Detail</th>
                <th>Keterangan</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data_pengeluaran as $index => $pengeluaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($pengeluaran->jenis_pengeluaran) }}</td>
                    <td>
                        @if ($pengeluaran->jenis_pengeluaran === 'telur pecah')
                            Telur {{ $pengeluaran->jenis_telur }} •
                            {{ number_format($pengeluaran->berat_total, 0, ',', '.') }} gr
                        @elseif ($pengeluaran->jenis_pengeluaran === 'beli telur')
                            Telur {{ $pengeluaran->jenis_telur }} •
                            {{ number_format($pengeluaran->berat_total / 1000, 2, ',', '.') }} Kg
                        @else
                            {{ $pengeluaran->nama_pengeluaran }}
                        @endif
                    </td>
                    <td class="text-center">{{ $pengeluaran->keterangan ?? '-' }}</td>
                    <td class="text-center">Rp {{ number_format($pengeluaran->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Data pengeluaran tidak tersedia</td>
                </tr>
            @endforelse
        </tbody>
        @php
            $totalPengeluaran = $data_pengeluaran->sum('nominal');
        @endphp
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="font-weight:bold">TOTAL PENGELUARAN</td>
                <td class="text-center" style="font-weight:bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
