<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - {{ $periode }}</title>

    <style>
        <style>body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin: 0;
            padding: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #eeeeee;
            text-align: center;
        }

        tfoot td {
            font-weight: bold;
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <h2 class="text-center">LAPORAN TRANSAKSI EGG GROW</h2>
    <p class="text-center">
        <strong>Periode:</strong> {{ $periode }}
    </p>

    {{-- TABLE TRANSAKSI --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Jenis Telur</th>
                <th>Berat (Kg)</th>
                <th>Harga Beli / Kg</th>
                <th>Harga Jual / Kg</th>
                <th>Total Harga</th>
                <th>Profit</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $index => $item)
                @php
                    $beratKg = $item->total_berat / 1000;
                    $profit = $item->total_harga - $item->harga_beli_kilo * $beratKg;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}
                    </td>
                    <td>{{ $item->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td class="text-center">{{ $item->jenis_telur }}
                        @if ($item->harga_jual_kilo < $item->harga_beli_kilo)
                            - BS
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($beratKg, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_beli_kilo, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_jual_kilo, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($profit, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Data transaksi tidak tersedia</td>
                </tr>
            @endforelse
        </tbody>

        {{-- RINGKASAN MENYATU --}}
        <tfoot>
            <tr>
                <td colspan="7" class="text-right">TOTAL OMZET</td>
                <td colspan="2" class="text-center">
                    Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="7" class="text-right">TOTAL PROFIT</td>
                <td colspan="2" class="text-center">
                    Rp {{ number_format($totalProfit, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
