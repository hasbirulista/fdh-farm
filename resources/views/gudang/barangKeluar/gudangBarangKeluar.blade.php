@extends('partials.master')

@section('content')
    <style>
        :root {
            --primary: #2d2d2d;
            --secondary: #1a1a1a;
            --light-bg: #f5f5f5;
            --border-light: #e0e0e0;
            --success: #28a745;
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

        .btn-custom.primary {
            background: linear-gradient(135deg, var(--success) 0%, #1e7e34 100%);
            color: white;
        }

        .btn-custom.primary:hover {
            color: white;
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
            <h4>Barang Keluar</h4>
            <div class="header-buttons">
                <a href="/dashboard/gudang/barang-keluar/tambah" class="btn-custom primary">+ Tambah Barang Keluar</a>

                {{-- CETAK LAPORAN --}}
                @if ($bulan !== 'all')
                    <a href="{{ route('barangKeluar.cetak', [
                        'bulan' => request('bulan'),
                        'tahun' => request('tahun'),
                        '_t' => time(),
                    ]) }}"
                        target="_blank" class="btn-custom print">
                        🖨️ Cetak Barang Keluar
                    </a>
                @endif
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @foreach (['Tambah', 'Update', 'Delete'] as $msg)
            @if (session('message' . $msg . 'BarangKeluar'))
                <div class="alert alert-success alert-dismissible fade show">
                    <strong>Berhasil {{ strtolower($msg) }} data barang keluar!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        {{-- FILTER --}}
        <div class="filter-section">
            <div
                style="font-weight: 700; font-size: 1rem; color: var(--primary); margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px;">
                Filter</div>
            <form method="GET" class="row g-3 align-items-end">
                {{-- Bulan --}}
                <div class="col-md-3 col-6">
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

                {{-- Tahun --}}
                <div class="col-md-3 col-6">
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
                            <th>Nama Konsumen</th>
                            <th>Jenis Barang</th>
                            <th>Detail Barang</th>
                            <th>Harga</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_barang_keluar as $index => $item)
                            <tr>
                                <td class="text-center">{{ $data_barang_keluar->firstItem() + $index }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_barang_keluar)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $item->nama_konsumen }}</td>
                                <td class="text-center">
                                    @if ($item->jenis_barang === 'telur')
                                        Telur
                                    @elseif($item->jenis_barang === 'ayam_apkir')
                                        Ayam Apkir
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->jenis_barang === 'telur')
                                        <small>
                                            <strong>{{ $item->jenis_telur }}</strong><br>
                                            {{ number_format($item->jumlah_barang_keluar / 1000, 2, ',', '.') }} kg
                                        </small>
                                    @elseif($item->jenis_barang === 'ayam_apkir')
                                        <small>
                                            {{ number_format($item->jumlah_ayam, 0, ',', '.') }} ekor
                                        </small>
                                    @else
                                        <small>-</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->jenis_barang === 'telur')
                                        <small>Rp.{{ number_format($item->harga_kilo, 0, ',', '.') }}/kg</small>
                                    @elseif($item->jenis_barang === 'ayam_apkir')
                                        <small>-</small>
                                    @else
                                        <small>-</small>
                                    @endif
                                </td>
                                <td class="text-end">Rp.{{ number_format($item->total_harga, 0, ',', '.') }},-</td>
                                <td class="text-center">
                                    <a href="/dashboard/gudang/barang-keluar/{{ $item->id }}/edit"
                                        class="btn btn-sm btn-warning mb-1">Edit</a>
                                    <form action="/dashboard/gudang/barang-keluar/{{ $item->id }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data barang keluar belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $data_barang_keluar->firstItem() }} – {{ $data_barang_keluar->lastItem() }} dari
                    {{ $data_barang_keluar->total() }} data
                </div>
                <div>
                    {{ $data_barang_keluar->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>
@endsection
