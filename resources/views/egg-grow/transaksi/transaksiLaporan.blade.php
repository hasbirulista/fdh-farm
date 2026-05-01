<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - {{ $periode }}</title>

    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin: 0;
            padding: 0;
        }

        h3 {
            margin: 20px 0 10px 0;
            padding: 0;
            font-size: 14px;
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

        /* 🔥 INI YANG PENTING */
        thead {
            display: table-row-group;
        }

        tfoot td {
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .section {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <h2 class="text-center">LAPORAN TRANSAKSI EGG GROW</h2>
    <p class="text-center">
        <strong>Periode:</strong> {{ $periode }}
    </p>

    @if ($isHarian)
        {{-- ===== LAPORAN HARIAN (SATU TABEL) ===== --}}

        <div class="section">
            <h3>Transaksi {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h3>
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
                        <th>Pembayaran</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $allTransactions = $data
                            ->concat($dataKredit)
                            ->sortBy(function ($item) {
                                return $item->tanggal_transaksi;
                            })
                            ->values();
                    @endphp
                    @forelse ($allTransactions as $index => $item)
                        @php
                            $beratKg = $item->total_berat / 1000;
                            $profit = $item->total_harga - $item->harga_beli_kilo * $beratKg;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                @if ($item->tanggal_pelunasan && $item->tanggal_pelunasan != $item->tanggal_transaksi)
                                    {{ \Carbon\Carbon::parse($item->tanggal_pelunasan)->format('d/m/Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>{{ $item->pelanggan->nama_pelanggan ?? '-' }}</td>
                            <td class="text-center">{{ $item->jenis_telur }}
                                @if ($item->harga_jual_kilo < $item->harga_beli_kilo)
                                    - BS
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($beratKg, 2, ',', '.') }}</td>
                            <td class="text-right">Rp{{ number_format($item->harga_beli_kilo, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($item->harga_jual_kilo, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($item->total_harga, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($profit, 0, ',', '.') }},-</td>
                            <td class="text-center">
                                @if ($item->tanggal_pelunasan && $item->tanggal_pelunasan != $item->tanggal_transaksi)
                                    {{ $item->pembayaran }} - (pelunasan kredit
                                    {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }})
                                @else
                                    {{ $item->pembayaran }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Data transaksi tidak tersedia</td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    @php
                        // Total Berat: transaksi asli (exclude pelunasan) + kredit
                        $totalKg = ($data->filter(function ($item) {
                            return !($item->tanggal_pelunasan && $item->tanggal_pelunasan != $item->tanggal_transaksi);
                        })->sum('total_berat') + $dataKredit->sum('total_berat')) / 1000;
                        
                        // Total Omzet: dari semua transaksi tunai/transfer + kredit
                        $totalOmzetAsli = $data->sum('total_harga') + $dataKredit->sum('total_harga');
                        
                        // Total Profit: hanya dari tunai/transfer (exclude kredit)
                        $totalProfitAsli = $data->sum(function ($item) {
                            return $item->total_harga - $item->harga_beli_kilo * ($item->total_berat / 1000);
                        });
                    @endphp

                    <tr>
                        <td colspan="7" class="text-right">TOTAL BERAT</td>
                        <td colspan="3" class="text-center">
                            {{ number_format($totalKg, 2, ',', '.') }} Kg
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right">TOTAL OMZET</td>
                        <td colspan="3" class="text-center">
                            Rp {{ number_format($totalOmzetAsli, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if ($totalKredit > 0)
                        <tr>
                            <td colspan="7" class="text-right">TOTAL KREDIT</td>
                            <td colspan="3" class="text-center">
                                Rp {{ number_format($totalKredit, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-right">TOTAL CASH (OMZET - KREDIT)</td>
                            <td style="color:#00ff00" colspan="3" class="text-center">
                                Rp {{ number_format($totalCash, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="7" class="text-right">TOTAL PROFIT</td>
                        <td colspan="3" class="text-center">
                            Rp {{ number_format($totalProfitAsli, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        {{-- ===== LAPORAN BULANAN ===== --}}

        {{-- TABLE TRANSAKSI NON-KREDIT --}}
        <div class="section">
            <h3>Transaksi Tunai / Transfer</h3>
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
                        <th>Pembayaran</th>
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
                                @if ($item->tanggal_pelunasan && $item->tanggal_pelunasan != $item->tanggal_transaksi)
                                    {{ \Carbon\Carbon::parse($item->tanggal_pelunasan)->format('d/m/Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>{{ $item->pelanggan->nama_pelanggan ?? '-' }}</td>
                            <td class="text-center">{{ $item->jenis_telur }}
                                @if ($item->harga_jual_kilo < $item->harga_beli_kilo)
                                    - BS
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($beratKg, 2, ',', '.') }}</td>
                            <td class="text-right">Rp{{ number_format($item->harga_beli_kilo, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($item->harga_jual_kilo, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($item->total_harga, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($profit, 0, ',', '.') }},-</td>
                            <td class="text-center">
                                @if ($item->tanggal_pelunasan && $item->tanggal_pelunasan != $item->tanggal_transaksi)
                                    {{ $item->pembayaran }} - (pelunasan kredit
                                    {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }})
                                @else
                                    {{ $item->pembayaran }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Data transaksi tidak tersedia</td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="7" class="text-right">TOTAL OMZET TUNAI/TRANSFER</td>
                        <td colspan="3" class="text-center">
                            Rp {{ number_format($data->sum('total_harga'), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right">TOTAL PROFIT TUNAI/TRANSFER</td>
                        <td colspan="3" class="text-center">
                            Rp
                            {{ number_format($data->sum(function ($item) {return $item->total_harga - $item->harga_beli_kilo * ($item->total_berat / 1000);}),0,',','.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- TABEL KREDIT --}}
        <div class="section" style="page-break-inside: avoid;">
            <h3>Transaksi Kredit</h3>
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
                        <th>Status Pelunasan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dataKredit as $index => $item)
                        @php
                            $beratKg = $item->total_berat / 1000;
                            $profit = $item->total_harga - $item->harga_beli_kilo * $beratKg;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}</td>
                            <td>{{ $item->pelanggan->nama_pelanggan ?? '-' }}</td>
                            <td class="text-center">{{ $item->jenis_telur }}</td>
                            <td class="text-right">{{ number_format($beratKg, 2, ',', '.') }}</td>
                            <td class="text-right">Rp{{ number_format($item->harga_beli_kilo, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($item->harga_jual_kilo, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($item->total_harga, 0, ',', '.') }},-</td>
                            <td class="text-right">Rp{{ number_format($profit, 0, ',', '.') }},-</td>
                            <td class="text-center">{{ $item->status_pelunasan ?? 'Belum Lunas' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada transaksi kredit</td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="7" class="text-right">TOTAL OMZET KREDIT</td>
                        <td colspan="3" class="text-center">
                            Rp {{ number_format($dataKredit->sum('total_harga'), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right">TOTAL PROFIT KREDIT</td>
                        <td colspan="3" class="text-center">
                            Rp
                            {{ number_format($dataKredit->sum(function ($item) {return $item->total_harga - $item->harga_beli_kilo * ($item->total_berat / 1000);}),0,',','.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- RINGKASAN TOTAL --}}
        <div class="section" style="page-break-inside: avoid;">
            <table>
                <tfoot>
                    @php
                        // Hitung total profit hanya dari tunai/transfer (tanpa kredit)
                        $totalProfitTunaiTransfer = $data->sum(function ($item) {
                            return $item->total_harga - $item->harga_beli_kilo * ($item->total_berat / 1000);
                        });
                    @endphp
                    <tr>
                        <td colspan="7" class="text-right" style="background-color: #d0d0d0; font-size: 13px;">TOTAL
                            OMZET (TUNAI/TRANSFER + KREDIT)</td>
                        <td colspan="3" class="text-center" style="background-color: #d0d0d0; font-size: 13px;">
                            Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right" style="background-color: #d0d0d0; font-size: 13px;">TOTAL
                            PROFIT (TUNAI/TRANSFER SAJA - EXCLUDE KREDIT)</td>
                        <td colspan="3" class="text-center" style="background-color: #d0d0d0; font-size: 13px;">
                            Rp {{ number_format($totalProfitTunaiTransfer, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    @endif

</body>

</html>
