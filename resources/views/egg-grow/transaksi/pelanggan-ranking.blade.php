@extends('partials.master')

@section('content')
    <style>
        .header-section {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            color: #2d2d2d;
        }
    </style>

    <div class="container-fluid py-3">

        {{-- HEADER --}}
        <div class="header-section mt-2">
            <h4>🏆 Ranking Pembelian Pelanggan</h4>

            <div class="header-buttons">
                <a href="/dashboard/egg-grow/transaksi" class="btn-custom">
                    ← Kembali ke Transaksi
                </a>
            </div>
        </div>

        {{-- FILTER TANGGAL --}}
        <div class="mb-3">
            <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label for="start_date" class="form-label small">Tanggal Awal</label>
                    <input type="date" id="start_date" name="start_date" class="form-control form-control-sm"
                        value="{{ request('start_date') }}">
                </div>

                <div class="col-12 col-md-3">
                    <label for="end_date" class="form-label small">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" class="form-control form-control-sm"
                        value="{{ request('end_date') }}">
                </div>

                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">Filter</button>
                    <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm flex-fill">Reset</a>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 text-nowrap">
                    <thead class="table-dark text-center small">
                        <tr>
                            <th>Rank</th>
                            <th>Nama Pelanggan</th>
                            <th>Jumlah Transaksi</th>
                            <th>Telur Omega (Kg)</th>
                            <th>Telur Biasa (Kg)</th>
                            <th>Total Berat (Kg)</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse ($ranking as $item)
                            <tr>
                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                <td class="fw-semibold">{{ $item->nama_pelanggan }}</td>

                                <td class="text-center">
                                    <span class="badge bg-primary px-2 py-1">
                                        {{ $item->jumlah_transaksi }}x
                                    </span>
                                </td>

                                <td class="text-end">{{ number_format($item->total_omega / 1000, 2, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($item->total_biasa / 1000, 2, ',', '.') }}</td>
                                <td class="text-end fw-bold text-success">
                                    {{ number_format($item->total_berat / 1000, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
