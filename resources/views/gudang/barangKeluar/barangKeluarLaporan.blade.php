<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar — {{ $periode }}</title>
    <style>
        <style><style>body {
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
    <h3 style="text-align:center; margin-bottom:5px;">Laporan Barang Keluar FDH FARM</h3>
    <p style="text-align:center; margin-bottom:20px;">
        <strong>Periode: </strong> — {{ $periode }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Konsumen</th>
                <th>Jenis Barang</th>
                <th>Detail Barang</th>
                <th>Harga</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBeratTelur = 0;
                $totalHargaTelur = 0;
            @endphp

            @foreach ($data_barang_keluar as $index => $item)
                @if ($item->jenis_barang === 'telur')
                    @php
                        $totalBeratTelur += $item->jumlah_barang_keluar / 1000;
                        $totalHargaTelur += $item->total_harga;
                    @endphp
                @endif

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_barang_keluar)->format('d/m/Y') }}</td>
                    <td>{{ $item->nama_konsumen }}</td>
                    <td>
                        {{ $item->jenis_barang === 'telur' ? 'Telur' : 'Ayam Apkir' }}
                    </td>
                    <td>
                        @if ($item->jenis_barang === 'telur')
                            {{ $item->jenis_telur }} -
                            {{ number_format($item->jumlah_barang_keluar / 1000, 2, ',', '.') }} Kg
                        @else
                            {{ number_format($item->jumlah_ayam, 0, ',', '.') }} ekor
                        @endif
                    </td>
                    <td style="text-align:right;">
                        @if ($item->jenis_barang === 'telur')
                            Rp {{ number_format($item->harga_kilo, 0, ',', '.') }}/Kg
                        @else
                            Rp {{ number_format($item->total_harga / $item->jumlah_ayam, 0, ',', '.') }}/Ekor
                        @endif
                    </td>
                    <td style="text-align:right;">
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>

        @php
            $rataHargaKg = $totalBeratTelur > 0 ? $totalHargaTelur / $totalBeratTelur : 0;
        @endphp

        <tfoot>
            <tr>
                <td style="text-align: right" colspan="5"><strong>Jumlah Total Berat Telur</strong></td>
                <td  colspan="2"><strong>{{ number_format($totalBeratTelur, 2, ',', '.') }} Kg</strong></td>
            </tr>
            <tr>
                <td style="text-align: right" colspan="5"><strong>Jumlah Total Harga</strong></td>
                <td colspan="2"><strong>Rp {{ number_format($totalHargaTelur, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="text-align: right" colspan="5"><strong>Jumlah Rata-rata Harga Telur / Kg</strong></td>
                <td colspan="2"><strong>Rp {{ number_format($rataHargaKg, 0, ',', '.') }}/Kg</strong></td>
            </tr>
        </tfoot>
    </table>


</body>

</html>
