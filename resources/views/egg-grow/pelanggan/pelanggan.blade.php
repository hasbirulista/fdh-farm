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
            <h4>Pelanggan</h4>
            <div class="header-buttons">
                <a href="/dashboard/egg-grow/pelanggan/tambah" class="btn-custom">
                    ➕ Tambah Pelanggan
                </a>
                <a href="{{ route('pelanggan.cetak') }}" target="_blank" class="btn-custom print">
                    🖨️ Cetak Pelanggan
                </a>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @foreach (['Tambah', 'Update', 'Delete'] as $msg)
            @if (session('message' . $msg . 'Pelanggan'))
                <div class="alert alert-success alert-dismissible fade show">
                    <strong>Berhasil {{ strtolower($msg) }} data pelanggan!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        @endforeach

        {{-- FILTER --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end" id="filterForm">

                    {{-- SEARCH --}}
                    <div class="col-md-4 col-12">
                        <label class="fw-semibold small">Cari Pelanggan</label>
                        <input type="text" id="searchInput" name="q" class="form-control form-control-sm"
                            placeholder="Nama / No HP" value="{{ request('q') }}" oninput="autoSubmit()">
                    </div>

                    {{-- REPEAT ORDER --}}
                    <div class="col-md-3 col-6">
                        <label class="fw-semibold small">Repeat Order</label>
                        <select name="repeat" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            <option value="1" {{ request('repeat') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('repeat') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
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
                            <th>Nama Pelanggan</th>
                            <th>No HP</th>
                            <th>Alamat</th>
                            <th>Repeat</th>
                            <th>Interval</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggans as $index => $pelanggan)
                            <tr>
                                <td class="text-center">
                                    {{ $pelanggans->firstItem() + $index }}
                                </td>
                                <td>{{ $pelanggan->nama_pelanggan }}</td>
                                <td class="text-center">{{ $pelanggan->no_hp ?? '-' }}</td>
                                <td>{{ $pelanggan->alamat ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $pelanggan->repeat_order_aktif ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $pelanggan->repeat_order_aktif ? 'Aktif' : 'Tidak' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ $pelanggan->repeat_order_aktif ? $pelanggan->repeat_order_hari . ' hari' : '-' }}
                                </td>
                                <td class="text-center">
                                    <a href="/dashboard/egg-grow/pelanggan/{{ $pelanggan->id }}/edit"
                                        class="btn btn-sm btn-warning mb-1">
                                        Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#hapus{{ $pelanggan->id }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    Data pelanggan tidak ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $pelanggans->firstItem() ?? 0 }} – {{ $pelanggans->lastItem() ?? 0 }} dari
                    {{ $pelanggans->total() ?? 0 }} data
                </div>
                <div>
                    {{ $pelanggans->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        @foreach ($pelanggans as $pelanggan)
            <div class="modal fade" id="hapus{{ $pelanggan->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="/dashboard/egg-grow/pelanggan/{{ $pelanggan->id }}" method="POST" class="modal-content">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Hapus data pelanggan ini?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>

    <script>
        let typingTimer;

        function autoSubmit() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        }

        // 🔁 Fokus kembali ke input setelah reload jika sedang mencari
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('searchInput');

            if (input && input.value !== '') {
                input.focus();

                // Pindahkan kursor ke akhir teks
                const val = input.value;
                input.value = '';
                input.value = val;
            }
        });
    </script>
@endsection
