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
    </style>

    <div class="">

        {{-- HEADER --}}
        <div class="header-section mt-2">
            <h4>Data Pengeluaran</h4>

            <div class="header-buttons">
                <a href="{{ route('egg-grow.tambahPengeluaran') }}" class="btn-custom">
                    ➕ Tambah Pengeluaran
                </a>

                {{-- CETAK LAPORAN --}}
                @if ($bulan && $bulan !== 'all')
                    <a href="{{ route('egg-grow.cetakPengeluaran', [
                        'bulan' => request('bulan'),
                        'tahun' => request('tahun'),
                        '_t' => time(),
                    ]) }}"
                        target="_blank" class="btn btn-info">
                        🖨️ Cetak Pengeluaran
                    </a>
                @endif
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @foreach (['Tambah', 'Update', 'Delete'] as $msg)
            @if (session('message' . $msg . 'Pengeluaran'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil {{ strtolower($msg) }} data pengeluaran!</strong>
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
                            <option value="all" {{ request('bulan') === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @foreach (range(1, 12) as $b)
                                <option value="{{ $b }}"
                                    {{ request('bulan') == $b || (request('bulan') === null && $b == now()->month) ? 'selected' : '' }}>
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
                                <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                                    {{ $t }}</option>
                            @endfor
                        </select>
                    </div>

                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-dark text-center small">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Detail</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($data_pengeluaran as $index => $pengeluaran)
                            <tr>
                                <td>{{ $data_pengeluaran->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span
                                        class="badge {{ $pengeluaran->jenis_pengeluaran === 'telur pecah' ? 'bg-danger' : ($pengeluaran->jenis_pengeluaran === 'beli telur' ? 'bg-success' : 'bg-secondary') }}">
                                        {{ ucfirst($pengeluaran->jenis_pengeluaran) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($pengeluaran->jenis_pengeluaran === 'telur pecah')
                                        Telur {{ $pengeluaran->jenis_telur }} •
                                        {{ number_format($pengeluaran->berat_total, 0, ',', '.') }} gr
                                    @elseif ($pengeluaran->jenis_pengeluaran === 'beli telur')
                                        Telur {{ $pengeluaran->jenis_telur }} •
                                        {{ number_format($pengeluaran->berat_total / 1000, 2, ',', '.') }} Kg
                                    @else
                                        {{ $pengeluaran->nama_pengeluaran }}
                                    @endif
                                </td>
                                <td>
                                    Rp {{ number_format($pengeluaran->nominal, 0, ',', '.') }}
                                </td>
                                <td>{{ $pengeluaran->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($pengeluaran->jenis_pengeluaran !== 'beli telur')
                                        <div class="d-flex flex-column flex-md-row justify-content-center gap-1">
                                            <a href="{{ route('egg-grow.editPengeluaran', $pengeluaran->id) }}"
                                                class="btn btn-warning btn-sm w-100 w-md-auto mb-1 mb-md-0">Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm w-100 w-md-auto"
                                                data-bs-toggle="modal" data-bs-target="#hapus{{ $pengeluaran->id }}">
                                                Hapus
                                            </button>
                                        </div>
                                    @else
                                        <span style="color: #999;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data pengeluaran belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $data_pengeluaran->firstItem() ?? 0 }} – {{ $data_pengeluaran->lastItem() ?? 0 }} dari
                    {{ $data_pengeluaran->total() ?? 0 }} data
                </div>
                <div>
                    {{ $data_pengeluaran->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        @foreach ($data_pengeluaran as $pengeluaran)
            @if ($pengeluaran->jenis_pengeluaran !== 'beli telur')
                <div class="modal fade" id="hapus{{ $pengeluaran->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="/dashboard/egg-grow/pengeluaran/{{ $pengeluaran->id }}" method="POST"
                            class="modal-content">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi!</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Apakah anda yakin ingin menghapus data pengeluaran ini?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
@endsection
