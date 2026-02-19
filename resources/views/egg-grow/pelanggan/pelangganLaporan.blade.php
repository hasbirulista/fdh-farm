<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pelanggan</title>
    <style>
        <style>body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
</head>

<body>

    <h3 style="text-align:center; margin-bottom:15px;">
        DATA PELANGGAN <br>EGG GROW
    </h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Repeat Order</th>
                <th>Interval (Hari)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_pelanggan as $no => $pelanggan)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td class="text-left">{{ $pelanggan->nama_pelanggan }}</td>
                    <td>{{ $pelanggan->no_hp ?? '-' }}</td>
                    <td class="text-left">{{ $pelanggan->alamat ?? '-' }}</td>
                    <td>
                        {{ $pelanggan->repeat_order_aktif ? 'Aktif' : 'Tidak Aktif' }}
                    </td>
                    <td>
                        {{ $pelanggan->repeat_order_aktif ? $pelanggan->repeat_order_hari : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
