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

        body.modal-open .card:hover {
            transform: none !important;
        }

        body.modal-open .dashboard-card:hover {
            transform: none !important;
        }

        .modal {
            will-change: transform;
        }
    </style>

    <div class="mt-2">
        <div class="header-section">
            <h4>Kredit</h4>
            <div class="header-buttons">
                <a href="/dashboard/egg-grow/" class="btn-custom">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>

        {{-- SEARCH FORM --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end" id="filterForm">
                    <div class="col-md-4 col-12">
                        <label class="fw-semibold small">Cari Pelanggan</label>
                        <input type="text" id="searchInput" name="q" class="form-control form-control-sm"
                            placeholder="Nama pelanggan" value="{{ request('q') }}" oninput="autoSubmit()">
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark text-center small">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Berat</th>
                            <th>Harga/Kg</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($data as $index => $item)
                            <tr>
                                <td class="text-center">
                                    {{ $data->firstItem() + $index }}
                                </td>

                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $item->pelanggan->nama_pelanggan }}
                                </td>

                                <td class="text-center">
                                    {{ $item->total_berat / 1000 }} Kg
                                </td>

                                <td class="text-center">
                                    Rp {{ number_format($item->harga_jual_kilo) }}
                                </td>

                                <td class="text-center">
                                    Rp {{ number_format($item->total_harga) }}
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-danger">Belum Lunas</span>
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#modalLunas{{ $item->id }}">
                                        Lunas
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    Tidak ada transaksi kredit
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @foreach ($data as $item)
                    <div class="modal fade" id="modalLunas{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Pelunasan</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                {{-- FORM PINDAH KE BODY --}}
                                <form action="{{ route('kredit.lunas', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body">
                                        Apakah anda akan mengupdate data ini menjadi <b>lunas</b>? <br><br>

                                        <b>Data Transaksi</b><br>
                                        Nama : {{ $item->pelanggan->nama_pelanggan }} <br>
                                        Total Harga : Rp {{ number_format($item->total_harga) }} <br>
                                        Pembayaran : {{ $item->pembayaran }} <br><br>

                                        <label class="mb-1">Ubah Metode Pembayaran:</label>

                                        <select name="pembayaran" class="form-control" required>
                                            <option value="{{ $item->pembayaran }}" selected>
                                                {{ $item->pembayaran }}
                                            </option>

                                            @if ($item->pembayaran == 'Tunai')
                                                <option value="Transfer">Transfer</option>
                                                <option value="Kredit">Kredit</option>
                                            @elseif($item->pembayaran == 'Transfer')
                                                <option value="Tunai">Tunai</option>
                                                <option value="Kredit">Kredit</option>
                                            @else
                                                <option value="Tunai">Tunai</option>
                                                <option value="Transfer">Transfer</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">
                                            Ya, Lunas
                                        </button>

                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    @if(request('q'))
                        Hasil pencarian: <strong>{{ $data->total() }}</strong> data
                    @else
                        Menampilkan {{ $data->firstItem() ?? 0 }} – {{ $data->lastItem() ?? 0 }} dari
                        {{ $data->total() ?? 0 }} data
                    @endif
                </div>

                <div>
                    {{ $data->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        let typingTimer;

        function autoSubmit() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        }
    </script>
@endsection
