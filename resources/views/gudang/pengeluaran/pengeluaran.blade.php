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
            <h4>Data Pengeluaran</h4>
            <div class="header-buttons">
                <a href="{{ route('gudang.tambahPengeluaran') }}" class="btn-custom">
                    ➕ Tambah Pengeluaran
                </a>

                {{-- CETAK LAPORAN --}}
                @if ($bulan !== 'all')
                    <a href="{{ route('pengeluaran.cetak', ['bulan' => request('bulan'), 'tahun' => request('tahun'), '_t' => time()]) }}"
                        target="_blank" class="btn-custom print">
                        🖨️ Cetak Pengeluaran
                    </a>
                @endif
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @foreach (['Tambah', 'Update', 'Delete'] as $msg)
            @if (session('message' . $msg . 'Pengeluaran'))
                <div class="alert alert-success alert-dismissible fade show">
                    <strong>Berhasil {{ strtolower($msg) }} data pengeluaran!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        {{-- FILTER --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">

                    {{-- Bulan --}}
                    <div class="col-md-3 col-6">
                        <label class="fw-semibold small">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all" {{ $bulan === 'all' ? 'selected' : '' }}>
                                Semua Bulan
                            </option>

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
                            <th>Informasi</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($data_pengeluaran as $pengeluaran)
                            <tr>
                                <td>{{ $data_pengeluaran->firstItem() + $loop->index }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span
                                        class="badge {{ $pengeluaran->jenis_pengeluaran === 'pakan' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($pengeluaran->jenis_pengeluaran) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($pengeluaran->jenis_pengeluaran === 'pakan')
                                        {{ $pengeluaran->jenis_pakan }} •
                                        {{ number_format($pengeluaran->berat_total, 0, ',', '.') }} Kg • Rp
                                        {{ number_format($pengeluaran->harga_kilo, 0, ',', '.') }}/Kg
                                    @else
                                        {{ $pengeluaran->nama_pengeluaran }}
                                    @endif
                                </td>
                                <td>{{ $pengeluaran->keterangan ?? '-' }}</td>
                                <td>
                                    Rp {{ number_format($pengeluaran->total_harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    <a href="{{ route('gudang.editPengeluaran', $pengeluaran->id) }}"
                                        class="btn btn-sm btn-warning mb-1">Edit</a>
                                    <form action="/dashboard/gudang/pengeluaran/{{ $pengeluaran->id }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-1"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data pengeluaran ini?\n\nTanggal: {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}\nJenis: {{ ucfirst($pengeluaran->jenis_pengeluaran) }}\nNominal: Rp {{ number_format($pengeluaran->total_harga, 0, ',', '.') }}');">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Data pengeluaran belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $data_pengeluaran->firstItem() }} – {{ $data_pengeluaran->lastItem() }}
                    dari {{ $data_pengeluaran->total() }} data
                </div>
                <div>
                    {{ $data_pengeluaran->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>

    </div>
@endsection
