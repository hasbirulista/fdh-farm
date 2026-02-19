@extends('partials.master')

@section('content')
    <style>
        :root {
            --primary: #2d2d2d;
            --secondary: #1a1a1a;
            --light-bg: #f5f5f5;
            --border-light: #e0e0e0;
            --info: #17a2b8;
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

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }
    </style>

    <div class="mt-2">

        {{-- HEADER --}}
        <div class="header-section">
            <h4>Barang Masuk</h4>
            <div class="header-buttons">
                {{-- CETAK LAPORAN --}}
                @if ($bulan !== 'all')
                    <a href="{{ route('barangMasuk.cetak', [
                        'bulan' => request('bulan'),
                        'tahun' => request('tahun'),
                        '_t' => time(),
                    ]) }}"
                        target="_blank" class="btn-custom print">
                        🖨️ Cetak Barang Masuk
                    </a>
                @endif
            </div>
        </div>

        {{-- FILTER --}}
        <div class="filter-section">
            <div class="filter-title">Filter</div>
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
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}
                            </option>
                        @endfor
                    </select>
                </div>

            </form>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark text-center small">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Kandang</th>
                            <th>Jenis Telur</th>
                            <th>Jumlah Butir</th>
                            <th>Telur Pecah</th>
                            <th>Jumlah Gram</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_barang_masuk as $index => $barang_masuk)
                            <tr>
                                <td class="text-center">{{ $data_barang_masuk->firstItem() + $index }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($barang_masuk->tanggal_barang_masuk)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $barang_masuk->nama_kandang }}</td>
                                <td class="text-center">{{ $barang_masuk->jenis_telur }}</td>
                                <td class="text-center">{{ number_format($barang_masuk->jumlah_butir, 0, ',', '.') }}</td>
                                <td class="text-center">{{ number_format($barang_masuk->jumlah_pecah, 0, ',', '.') }}</td>
                                <td class="text-center">{{ number_format($barang_masuk->jumlah_gram, 0, ',', '.') }} gr
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data barang masuk belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $data_barang_masuk->firstItem() }} – {{ $data_barang_masuk->lastItem() }} dari
                    {{ $data_barang_masuk->total() }} data
                </div>
                <div>
                    {{ $data_barang_masuk->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>
@endsection
