@extends('partials.master')

@section('content')
    <style>
        :root {
            --primary: #2d2d2d;
            --secondary: #1a1a1a;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
            color: var(--primary);
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
            color: var(--primary);
        }

        .btn-custom.print {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .btn-custom.print:hover {
            color: white;
        }
    </style>

    <div class="mt-2">
        {{-- HEADER --}}
        <div class="header-section">
            <h4>Barang Masuk</h4>
            <div class="header-buttons">
                {{-- CETAK LAPORAN --}}
                @if ($bulan !== 'all')
                    <a href="{{ route('eggGrow.barangMasuk.cetak', ['bulan' => request('bulan'), 'tahun' => request('tahun'), '_t' => time()]) }}"
                        target="_blank" class="btn-custom print">
                        🖨️ Cetak Barang Masuk
                    </a>
                @endif
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @foreach (['Tambah', 'Update', 'Delete'] as $msg)
            @if (session('message' . $msg . 'BarangMasuk'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil {{ strtolower($msg) }} data barang masuk!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    {{-- FILTER BULAN --}}
                    <div class="col-md-3 col-6">
                        <label class="fw-semibold small">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Bulan</option>
                            @foreach (range(1, 12) as $b)
                                <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- FILTER TAHUN --}}
                    <div class="col-md-3 col-6">
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
                    <thead class="table-dark text-center small">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis Telur</th>
                            <th>Jumlah (Kg)</th>
                            <th>Harga / Kg</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($data_barang_masuk as $index => $barang_masuk)
                            <tr>
                                <td>{{ $data_barang_masuk->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($barang_masuk->tanggal_barang_masuk)->format('d/m/Y') }}</td>
                                <td>{{ $barang_masuk->jenis_telur }}</td>
                                <td>{{ number_format($barang_masuk->jumlah_barang_masuk / 1000, 2, ',', '.') }} Kg</td>
                                <td>Rp.{{ number_format($barang_masuk->harga_kilo, 0, ',', '.') }},- </td>
                                <td>Rp.{{ number_format($barang_masuk->total_harga, 0, ',', '.') }},- </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Data barang masuk belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $data_barang_masuk->firstItem() ?? 0 }} – {{ $data_barang_masuk->lastItem() ?? 0 }}
                    dari
                    {{ $data_barang_masuk->total() ?? 0 }} data
                </div>
                <div>
                    {{ $data_barang_masuk->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>
@endsection
