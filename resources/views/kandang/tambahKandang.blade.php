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
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .header-section h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.6rem;
        }

        .stats-container {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            flex: 1;
            min-width: 150px;
            text-align: center;
            border-left: 4px solid #2d2d2d;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d2d2d;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }

        .form-control {
            border: 1.5px solid #ddd;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .form-control:focus {
            border-color: #2d2d2d;
            box-shadow: 0 0 0 3px rgba(45, 45, 45, 0.1);
            outline: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            width: 100%;
            cursor: pointer;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-add {
            background: white;
            color: #2d2d2d;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f5f5f5;
            border-bottom: 1px solid #ddd;
            padding: 12px 15px;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 700;
            color: #2d2d2d;
            font-size: 0.95rem;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        .table thead {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        .table thead th {
            padding: 10px 12px;
            border: none;
        }

        .table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
            border-radius: 5px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #333;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .modal-content {
            border-radius: 8px;
        }

        .modal-header {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            border: none;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>

    <div class="mt-2">

        <div class="header-section d-flex justify-content-between align-items-center">
            <h4>Kandang</h4>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#tambahKandangModal">
                + Tambah Kandang
            </button>
        </div>
        @if (session('messageTambahKandang'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('messageTambahKandang') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('messageUpdateKandang'))
            <div class="alert alert-warning alert-dismissible fade show">
                {{ session('messageUpdateKandang') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('messageDeleteKandang'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('messageDeleteKandang') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">{{ $data_kandang->count() }}</div>
                <div class="stat-label">Total Kandang</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($data_kandang->sum('populasi_ayam')) }}</div>
                <div class="stat-label">Total Populasi</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>📋 Daftar Kandang</h5>
            </div>

            @if ($data_kandang->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 40px">No</th>
                                <th>Nama</th>
                                <th>Chick in</th>
                                <th>Anak Kandang</th>
                                <th>Populasi</th>
                                <th style="width: 100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_kandang as $kandang)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kandang->nama_kandang }}</td>
                                    <td>{{ $kandang->chicken_in }}</td>
                                    <td>{{ $kandang->anak_kandang }}</td>
                                    <td>{{ number_format($kandang->populasi_ayam) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-edit" data-bs-toggle="modal"
                                            data-bs-target="#editKandangModal" data-id="{{ $kandang->id }}"
                                            data-nama="{{ $kandang->nama_kandang }}"
                                            data-chicken="{{ $kandang->chicken_in }}"
                                            data-anak="{{ $kandang->anak_kandang }}"
                                            data-populasi="{{ $kandang->populasi_ayam }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-delete" data-bs-toggle="modal"
                                            data-bs-target="#hapus{{ $kandang->id }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="hapus{{ $kandang->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="/dashboard/kandang/tambah-kandang/{{ $kandang->id }}" method="POST" class="modal-content">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title">Hapus Kandang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Yakin ingin menghapus kandang
                    <strong>{{ $kandang->nama_kandang }}</strong> ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editKandangModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Kandang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama_kandang" class="form-control" required>

                    <label class="form-label">Chick in</label>
                    <input type="text" name="chicken_in" class="form-control" required>

                    <label class="form-label">Anak Kandang</label>
                    <input type="text" name="anak_kandang" class="form-control" required>

                    <label class="form-label">Populasi</label>
                    <input type="number" name="populasi_ayam" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="tambahKandangModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="/dashboard/kandang/tambah-kandang" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kandang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nama</label>
                    <input type="text" required name="nama_kandang" class="form-control">

                    <label class="form-label">Chick in</label>
                    <input type="text" required name="chicken_in" class="form-control">

                    <label class="form-label">Anak Kandang</label>
                    <input type="text" required name="anak_kandang" class="form-control">

                    <label class="form-label">Populasi</label>
                    <input type="number" required name="populasi_ayam" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const editModal = document.getElementById('editKandangModal');

        if (editModal) {
            editModal.addEventListener('show.bs.modal', function(event) {

                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const chicken = button.getAttribute('data-chicken');
                const anak = button.getAttribute('data-anak');
                const populasi = button.getAttribute('data-populasi');

                const form = editModal.querySelector('form');

                form.action = `/dashboard/kandang/tambah-kandang/${id}`; // 🔥 INI YANG BENAR kalau resource

                form.querySelector('input[name="nama_kandang"]').value = nama;
                form.querySelector('input[name="chicken_in"]').value = chicken;
                form.querySelector('input[name="anak_kandang"]').value = anak;
                form.querySelector('input[name="populasi_ayam"]').value = populasi;
            });
        }
    </script>
@endsection
