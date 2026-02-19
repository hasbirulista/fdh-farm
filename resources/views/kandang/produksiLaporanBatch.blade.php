<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Produksi - {{ $nama_kandang }} - {{ $periode }}</title>
    <style>
        <style>body {
            font-family: Helvetica, Arial, sans-serif;
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
    <h2 style="text-align:center;">LAPORAN PRODUKSI FDH FARM</h2>
    <h2 style="text-align:center;">{{ $nama_kandang }}</h2>
    <p style="text-align:center; margin-bottom:20px;">
        <strong>(Periode : {{ $periode }}) </strong>
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                @if ($tampilkanKandang)
                    <th>Kandang</th>
                @endif
                <th>Populasi</th>
                <th>Usia</th>
                <th>Telur</th>
                <th>Butir</th>
                <th>Gram</th>
                <th>Produksi (%)</th>
                <th>Butir/Kg</th>
                <th>Kegiatan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_produksi as $index => $produksi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($produksi->tanggal_produksi)->format('d/m/Y') }}</td>
                    @if ($tampilkanKandang)
                        <td>{{ $produksi->nama_kandang }}</td>
                    @endif
                    <td class="text-right">{{ number_format($produksi->populasi_ayam, 0, ',', '.') }}</td>
                    <td>{{ $produksi->usia }}</td>
                    <td>{{ $produksi->jenis_telur }}</td>
                    <td class="text-right">{{ number_format($produksi->jumlah_butir, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($produksi->jumlah_gram, 0, ',', '.') }} gr</td>
                    <td class="text-center">{{ number_format($produksi->persentase_produksi, 2, ',', '.') }}%</td>
                    <td class="text-right">
                        {{ number_format($produksi->jumlah_butir / ($produksi->jumlah_gram / 1000), 2, ',', '.') }}
                    </td>
                    <td class="text-left">{{ $produksi->kegiatan ?? '-' }}</td>
                    <td class="text-left">{{ $produksi->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
