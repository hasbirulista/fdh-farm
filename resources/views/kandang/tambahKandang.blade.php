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
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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

        .form-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #2d2d2d;
        }

        .form-section-title {
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 15px;
            font-size: 0.95rem;
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
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
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

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
            border-radius: 5px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-group-action {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #333;
        }

        .btn-edit:hover {
            background-color: #ffb300;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .card-footer {
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
            padding: 10px 15px;
            text-align: center;
            font-size: 0.85rem;
            color: #666;
        }

        .modal-content {
            border: none;
            border-radius: 8px;
        }

        .modal-header {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            border: none;
        }

        .modal-title {
            font-weight: 700;
            font-size: 0.95rem;
        }

        .modal-body {
            padding: 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #333;
            font-weight: 600;
        }

        .btn-warning:hover {
            background-color: #ffb300;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state-icon {
            font-size: 2rem;
            margin-bottom: 12px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .header-section h4 {
                font-size: 1.3rem;
            }

            .stat-card {
                padding: 12px 15px;
                min-width: 120px;
            }

            .stat-number {
                font-size: 1.25rem;
            }

            .table {
                font-size: 0.8rem;
            }

            .table thead th,
            .table tbody td {
                padding: 8px 10px;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="mt-2">
        <div class="header-section">
            <h4>🏠 Kandang</h4>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Nama kandang sudah ada.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('messageUpdateKandang'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Berhasil update kandang
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('messageTambahKandang'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Berhasil tambah kandang
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('messageDeleteKandang'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ Berhasil hapus kandang
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

        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="form-section">
                    <h5 class="form-section-title">➕ Tambah Kandang</h5>

                    <form action="/dashboard/kandang/tambah-kandang" method="POST">
                        @csrf

                        <div>
                            <label class="form-label">Nama</label>
                            <input type="text" required name="nama_kandang" class="form-control" placeholder="Nama kandang">
                        </div>
                        
                        <div>
                            <label class="form-label">Chick in</label>
                            <input type="text" required name="chicken_in" class="form-control" placeholder="Chick in">
                        </div>

                        <div>
                            <label class="form-label">Populasi</label>
                            <input type="number" required name="populasi_ayam" class="form-control" placeholder="Jumlah ayam">
                        </div>

                        <button class="btn-submit" type="submit">Simpan</button>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5>📋 Daftar Kandang</h5>
                    </div>

                    @if($data_kandang->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">No</th>
                                        <th>Nama</th>
                                        <th>Chick in</th>
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
                                            <td>{{ number_format($kandang->populasi_ayam) }}</td>
                                            <td>
                                                <div class="btn-group-action">
                                                    <button class="btn btn-sm btn-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editKandangModal" data-id="{{ $kandang->id }}"
                                                        data-nama="{{ $kandang->nama_kandang }}" data-chicken="{{ $kandang->chicken_in }}" data-populasi="{{ $kandang->populasi_ayam }}">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-delete" data-bs-toggle="modal"
                                                        data-bs-target="#hapus{{ $kandang->id }}">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $data_kandang->count() }} kandang
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">🏠</div>
                            <p>Belum ada kandang</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach ($data_kandang as $kandang)
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
                        Hapus kandang <strong>{{ $kandang->nama_kandang }}</strong>?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-delete">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="editKandangModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" id="formEditKandang" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Kandang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama_kandang" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chicken In</label>
                        <input type="text" name="chicken_in" id="editChickenIn" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Populasi</label>
                        <input type="number" name="populasi_ayam" id="editPopulasi" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const editModal = document.getElementById('editKandangModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('editNama').value = button.dataset.nama;
            document.getElementById('editChickenIn').value = button.dataset.chicken;
            document.getElementById('editPopulasi').value = button.dataset.populasi;
            document.getElementById('formEditKandang').action = `/dashboard/kandang/tambah-kandang/${button.dataset.id}`;
        });
    </script>
@endsection
