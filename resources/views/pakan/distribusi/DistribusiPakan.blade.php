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
            --info: #17a2b8;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            color: var(--primary);
        }
    </style>

    <div class="mt-2">
        
        {{-- HEADER --}}
        <div class="header-section">
            <h4>Distribusi Pakan</h4>
            <div class="header-buttons">
                <a href="/dashboard/pakan/distribusi/tambah" class="btn-custom">
                    ➕ Tambah Distribusi
                </a>
            </div>
        </div>

        {{-- ALERT --}}
        @foreach (['messageTambahDistribusiPakan', 'messageUpdateDistribusiPakan', 'messageDeleteDistribusiPakan'] as $msg)
            @if (session($msg))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session($msg) }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        {{-- STOK PAKAN --}}
        <div class="row mb-3">
            <div class="col-6 mb-2">
                <div class="card shadow-sm border-start border-5 border-primary">
                    <button class="btn text-start p-0" data-bs-toggle="modal" data-bs-target="#modalStokGrower">
                        <div class="card-body text-center">
                            <i class="fi fi-sr-wheat mb-2" style="font-size:32px;"></i>
                            <h5 class="fw-bold mt-1">
                                {{ number_format($stok_pakan_grower, 2, ',', '.') }} Kg
                            </h5>
                            <p class="text-muted mb-0">Stok Pakan Grower</p>
                        </div>
                    </button>
                </div>
            </div>
            <div class="col-6 mb-2">
                <div class="card shadow-sm border-start border-5 border-success">
                    <button class="btn text-start p-0" data-bs-toggle="modal" data-bs-target="#modalStokLayer">
                        <div class="card-body text-center">
                            <i class="fi fi-rr-wheat mb-2" style="font-size:32px;"></i>
                            <h5 class="fw-bold mt-1">
                                {{ number_format($stok_pakan_layer, 2, ',', '.') }} Kg
                            </h5>
                            <p class="text-muted mb-0">Stok Pakan Layer</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- TABEL DISTRIBUSI --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark text-center small">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Kandang</th>
                            <th>Jenis Pakan</th>
                            <th>Jumlah Berat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($data_distribusi as $index => $distribusi)
                            <tr>
                                <td>{{ $data_distribusi->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->format('d/m/Y') }}</td>
                                <td>{{ $distribusi->kandang->nama_kandang }}</td>
                                <td>{{ $distribusi->stokPakan->jenis_pakan }}</td>
                                <td>{{ number_format($distribusi->jumlah_berat/1000, 2, ',', '.') }} kg</td>
                                <td>
                                    <div class="d-flex flex-column flex-md-row gap-1 justify-content-center">
                                        <a href="/dashboard/pakan/distribusi/{{ $distribusi->id }}/edit"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#hapus{{ $distribusi->id }}">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Belum ada data distribusi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $data_distribusi->firstItem() ?? 0 }} – {{ $data_distribusi->lastItem() ?? 0 }} dari
                    {{ $data_distribusi->total() ?? 0 }} data
                </div>
                <div>
                    {{ $data_distribusi->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        @foreach ($data_distribusi as $distribusi)
            <div class="modal fade" id="hapus{{ $distribusi->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="/dashboard/pakan/distribusi/{{ $distribusi->id }}" method="POST" class="modal-content">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus data ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        {{-- MODAL DETAIL --}}
        <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Distribusi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-bordered mb-0">
                            <tr>
                                <th>Tanggal</th>
                                <td id="detailTanggal"></td>
                            </tr>
                            <tr>
                                <th>Nama Kandang</th>
                                <td id="detailNamaKandang"></td>
                            </tr>
                            <tr>
                                <th>Jenis Pakan</th>
                                <td id="detailJenisPakan"></td>
                            </tr>
                            <tr>
                                <th>Jumlah Berat</th>
                                <td id="detailJumlahBerat"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if ($role === 'owner')
            {{-- Modal Grower --}}
            <div class="modal fade" id="modalStokGrower" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="{{ route('stok.pakan.update') }}" class="modal-content"
                        onsubmit="return document.getElementById('confirmGrower').checked">

                        @csrf

                        <input type="hidden" name="jenis_pakan" value="grower">

                        <div class="modal-header">
                            <h5 class="modal-title">Update Stok Pakan Grower</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <input type="number" name="berat_total" class="form-control"
                                    value="{{ $stok_pakan_grower }}" min="0" step="0.01" required>
                                <span class="input-group-text">Kg</span>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmGrower">
                                <label class="form-check-label">
                                    Saya yakin ingin mengubah stok pakan grower
                                </label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Layer --}}
            <div class="modal fade" id="modalStokLayer" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="{{ route('stok.pakan.update') }}" class="modal-content"
                        onsubmit="return document.getElementById('confirmLayer').checked">

                        @csrf

                        <input type="hidden" name="jenis_pakan" value="layer">

                        <div class="modal-header">
                            <h5 class="modal-title">Update Stok Pakan Layer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <input type="number" name="berat_total" class="form-control"
                                    value="{{ $stok_pakan_layer }}" min="0" step="0.01" required>
                                <span class="input-group-text">Kg</span>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmLayer">
                                <label class="form-check-label">
                                    Saya yakin ingin mengubah stok pakan layer
                                </label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    {{-- SCRIPT MODAL DETAIL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = document.getElementById('detailModal');
            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('detailTanggal').textContent = button.dataset.tanggal;
                document.getElementById('detailNamaKandang').textContent = button.dataset.namakandang;
                document.getElementById('detailJenisPakan').textContent = button.dataset.jenispakan;
                document.getElementById('detailJumlahBerat').textContent = button.dataset.jumlahberat +
                    ' gr';
            });
        });
    </script>
@endsection
