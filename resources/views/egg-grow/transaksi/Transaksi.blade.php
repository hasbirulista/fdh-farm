@extends('partials.master')

@section('content')
    <style>
        .header-section {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-section h4 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-custom {
            background: white;
            color: #2d2d2d;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            color: #2d2d2d;
        }

        .btn-custom.print {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .btn-custom.print:hover {
            color: white;
        }

        .btn-custom.ranking {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
        }

        .btn-custom.ranking:hover {
            color: white;
        }
    </style>

    <div class="">

        {{-- HEADER --}}
        <div class="header-section mt-2">
            <h4>Transaksi Penjualan</h4>

            <div class="header-buttons">
                <a href="/dashboard/egg-grow/transaksi/tambah" class="btn-custom">
                    ➕ Tambah Transaksi
                </a>

                <a href="/dashboard/egg-grow/pelanggan-ranking" class="btn-custom ranking">
                    🏆 Ranking Pelanggan
                </a>

                {{-- CETAK LAPORAN --}}
                @if ($bulan !== 'all')
                    <a href="{{ route('transaksi.cetak', ['bulan' => $bulan, 'tahun' => $tahun, '_t' => time()]) }}"
                        target="_blank" class="btn-custom print">
                        🖨️ Cetak Laporan
                    </a>
                @endif
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @foreach (['Tambah', 'Update', 'Delete'] as $msg)
            @if (session('message' . $msg . 'Transaksi'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil {{ strtolower($msg) }} data transaksi!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        {{-- FILTER --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">

                    {{-- BULAN --}}
                    <div class="col-6 col-md-3">
                        <label class="fw-semibold small">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all" {{ $bulan === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @foreach (range(1, 12) as $b)
                                <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TAHUN --}}
                    <div class="col-6 col-md-3">
                        <label class="fw-semibold small">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                            @for ($t = now()->year; $t >= now()->year - 5; $t--)
                                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                                    {{ $t }}
                                </option>
                            @endfor
                        </select>
                    </div>

                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Tanggal Transaksi</th>
                            <th>Jenis Telur</th>
                            <th>Total Berat</th>
                            <th>Harga Beli / Kilo</th>
                            <th>Harga Jual / Kilo</th>
                            <th>Total Harga</th>
                            <th>Pembayaran</th>
                            <th>Profit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $transaksi)
                            <tr>
                                <td class="text-center">{{ $transaksis->firstItem() + $loop->index }}</td>
                                <td>{{ $transaksi->pelanggan->nama_pelanggan }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
                                <td>{{ $transaksi->jenis_telur }}
                                    @if ($transaksi->harga_jual_kilo < $transaksi->harga_beli_kilo)
                                        - BS
                                    @endif
                                </td>
                                <td>{{ number_format($transaksi->total_berat, 0, ',', '.') }} gr</td>
                                <td>Rp {{ number_format($transaksi->harga_beli_kilo, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaksi->harga_jual_kilo, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $transaksi->pembayaran }}</td>
                                <td>
                                    Rp
                                    {{ number_format($transaksi->total_harga - $transaksi->harga_beli_kilo * ($transaksi->total_berat / 1000), 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column flex-md-row justify-content-center gap-1">
                                        <a href="/dashboard/egg-grow/transaksi/{{ $transaksi->id }}/edit"
                                            class="btn btn-warning btn-sm w-100 w-md-auto mb-1 mb-md-0">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm w-100 w-md-auto"
                                            data-bs-toggle="modal" data-bs-target="#hapus{{ $transaksi->id }}">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Data transaksi belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $transaksis->firstItem() }} –
                    {{ $transaksis->lastItem() }} dari {{ $transaksis->total() }} data
                </div>
                <div>
                    {{ $transaksis->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        @foreach ($transaksis as $transaksi)
            <div class="modal fade" id="hapus{{ $transaksi->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="/dashboard/egg-grow/transaksi/{{ $transaksi->id }}" method="POST" class="modal-content">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus data transaksi ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus Data</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection
