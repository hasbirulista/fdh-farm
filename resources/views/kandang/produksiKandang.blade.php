@php
    use Illuminate\Support\Facades\Auth;
    $role = Auth::user()->role;
@endphp
@extends('partials.master')

@section('content')
    <style>
        :root {
            --primary: #2d2d2d;
            --secondary: #1a1a1a;
            --light-bg: #f5f5f5;
            --border-light: #e0e0e0;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
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

        .header-section .header-subtitle {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.8);
            margin-left: 10px;
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

        .filter-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--primary);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--primary);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-group select {
            border: 2px solid var(--border-light);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(45, 45, 45, 0.1);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
            border-left: 5px solid var(--primary);
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .summary-card.success {
            border-left-color: var(--success);
        }

        .summary-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .summary-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .summary-badge {
            display: inline-block;
            background: var(--light-bg);
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .summary-card.success .summary-value {
            color: var(--success);
        }

        .table-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .table thead th {
            border-bottom: 2px solid var(--primary);
            padding: 15px 12px;
            text-align: center;
        }

        .table tbody td {
            padding: 14px 12px;
            border-color: var(--border-light);
            font-size: 0.9rem;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
            border-bottom: 1px solid var(--border-light);
        }

        .table tbody tr:hover {
            background-color: var(--light-bg);
        }

        .badge-produksi {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-excellent {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-good {
            background-color: #cce5ff;
            color: #004085;
        }

        .badge-fair {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-poor {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-edit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .btn-edit:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .pagination {
            gap: 5px;
        }

        .pagination .page-link {
            border-radius: 6px;
            border: 1px solid var(--border-light);
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background-color: var(--light-bg);
            border-color: var(--primary);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-color: var(--primary);
        }

        .table-footer {
            background: var(--light-bg);
            border-top: 1px solid var(--border-light);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            color: #155724;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success .btn-close {
            color: #155724;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state h5 {
            margin: 10px 0;
            font-size: 1.3rem;
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-section h4 {
                font-size: 1.5rem;
            }

            .header-buttons {
                width: 100%;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .table-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .table thead {
                font-size: 0.75rem;
            }

            .table tbody td {
                font-size: 0.85rem;
                padding: 10px 8px;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="header-section mt-2">
        <div>
            <h4>
                Produksi
                @isset($nama_kandang)
                    <span class="header-subtitle">- {{ $nama_kandang }}</span>
                @endisset
            </h4>
        </div>
        <div class="header-buttons">
            <a href="/dashboard/kandang/produksi/tambah" class="btn-custom">
                + Tambah Produksi
            </a>

            @php
                $bulanFilter = request('bulan');
                $kandangFilter = $nama_kandang ?? null;
            @endphp
            @if (in_array($role, ['owner', 'kepala_kandang']))
                @if ($bulanFilter || $kandangFilter)
                    <a href="{{ route('produksi.cetak', [
                        'namaKandang' => $nama_kandang ?? null,
                        'bulan' => request('bulan'),
                        'tahun' => request('tahun'),
                        '_t' => time(),
                    ]) }}"
                        target="_blank" class="btn-custom print">
                        🖨️ Cetak Laporan
                    </a>
                @endif
            @endif
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @foreach (['Tambah', 'Update', 'Delete'] as $msg)
        @if (session('message' . $msg . 'Produksi'))
            <div class="alert alert-success alert-dismissible fade show">
                <strong>Berhasil {{ strtolower($msg) }} data produksi!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- FILTER TERPADU --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end"
                action="{{ isset($nama_kandang) ? route('produksi.perKandang', $nama_kandang) : route('produksi.all') }}">

                {{-- FILTER KANDANG --}}
                <div class="col-md-4 col-12">
                    <label class="fw-semibold small">Filter Kandang</label>
                    <select class="form-select form-select-sm" id="filterKandang">
                        <option value="">Semua Kandang</option>
                        @foreach ($daftarKandang as $kandang)
                            <option value="{{ $kandang->nama_kandang }}"
                                {{ isset($nama_kandang) && $nama_kandang == $kandang->nama_kandang ? 'selected' : '' }}>
                                {{ $kandang->nama_kandang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- FILTER BULAN --}}
                <div class="col-md-3 col-6">
                    <label class="fw-semibold small">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach (range(1, 12) as $b)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
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
                            <option value="{{ $t }}"
                                {{ request('tahun', now()->year) == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endfor
                    </select>
                </div>

            </form>

        </div>
    </div>

    {{-- SUMMARY --}}
    @if ($nama_kandang)
        <div class="row mb-3">
            <div class="col-md-4 col-12">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">
                            Rata-rata Produksi {{ $bulanSekarang }}
                        </small>
                        <h3 class="fw-bold mt-1">
                            {{ number_format($rataRataProduksi ?? 0, 2, ',', '.') }} %
                        </h3>
                        <span class="badge bg-primary">{{ $nama_kandang }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">
                            Total Berat Produksi {{ $bulanSekarang }}
                        </small>
                        <h3 class="fw-bold mt-1">
                            {{ number_format($totalBeratGram / 1000, 2, ',', '.') }} Kg
                        </h3>
                        <span class="badge bg-success">{{ $nama_kandang }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-dark text-center small">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kandang</th>
                        <th>Populasi</th>
                        <th>Usia</th>
                        <th>Mati</th>
                        <th>Apkir</th>
                        <th>Telur</th>
                        <th>Butir</th>
                        <th>Gram</th>
                        <th>Telur Pecah</th>
                        <th>Produksi</th>
                        <th>Butir/Kg</th>
                        <th>Kegiatan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_produksi as $produksi)
                        <tr>
                            <td class="text-center">{{ $data_produksi->firstItem() + $loop->index }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($produksi->tanggal_produksi)->format('d/m/Y') }}</td>
                            <td class="text-center fw-semibold">{{ $produksi->nama_kandang }}</td>
                            <td class="text-end">{{ number_format($produksi->populasi_ayam, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $produksi->usia }}</td>
                            <td class="text-end">{{ number_format($produksi->mati, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($produksi->apkir, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $produksi->jenis_telur }}</td>
                            <td class="text-end">{{ number_format($produksi->jumlah_butir, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($produksi->jumlah_gram, 0, ',', '.') }} gr</td>
                            <td class="text-center">{{ $produksi->jumlah_pecah }}</td>

                            <td class="text-center">
                                <span
                                    class="badge
                                    {{ $produksi->persentase_produksi > 89
                                        ? 'bg-success'
                                        : ($produksi->persentase_produksi > 84
                                            ? 'bg-warning'
                                            : ($produksi->persentase_produksi > 79
                                                ? 'bg-warning'
                                                : 'bg-danger')) }}">
                                    {{ number_format($produksi->persentase_produksi, 2, ',', '.') }}%
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-success">
                                    {{ number_format($produksi->jumlah_butir / ($produksi->jumlah_gram / 1000), 2, ',', '.') }}
                                </span>
                            </td>

                            <td class="small">{{ $produksi->kegiatan ?? '-' }}</td>
                            <td class="small">{{ $produksi->keterangan ?? '-' }}</td>

                            <td class="text-center">
                                <a href="/dashboard/kandang/produksi/{{ $produksi->id }}/edit"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="/dashboard/kandang/produksi/{{ $produksi->id }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data produksi ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="small text-muted">
                Menampilkan {{ $data_produksi->firstItem() }} – {{ $data_produksi->lastItem() }} dari
                {{ $data_produksi->total() }} data
            </div>
            <div>
                {{ $data_produksi->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <script>
        document.getElementById('filterKandang').addEventListener('change', function() {
            let kandang = this.value;
            let bulan = "{{ request('bulan') }}";
            let tahun = "{{ request('tahun') }}";

            // Jika kosong → redirect ke all
            if (!kandang) {
                window.location.href = "{{ route('produksi.all') }}" + "?bulan=" + bulan + "&tahun=" + tahun;
            } else {
                window.location.href = "{{ url('/dashboard/kandang/produksi/kandang') }}/" + kandang + "?bulan=" +
                    bulan + "&tahun=" + tahun;
            }
        });
    </script>
@endsection
