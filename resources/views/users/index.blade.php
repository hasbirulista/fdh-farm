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
            padding: 25px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-section h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .btn-custom {
            background: white;
            color: var(--primary);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            color: var(--primary);
        }

        .card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        .table thead {
            background-color: #f0f0f0;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
        }

        .table thead th {
            padding: 12px 15px;
            border: none;
        }

        .table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .badge {
            padding: 6px 10px;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 5px;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
        }

        .dropdown-menu {
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .dropdown-item {
            padding: 8px 14px;
            font-size: 0.9rem;
            border: none;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #333;
        }

        .dropdown-item.text-danger:hover {
            background-color: #fff3f3;
            color: #dc3545;
        }

        .card-footer {
            background-color: #fafafa;
            border-top: 1px solid #e9ecef;
            padding: 15px 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.4;
        }

        .empty-state h5 {
            color: #666;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .header-section h4 {
                font-size: 1.4rem;
            }

            .btn-custom {
                width: 100%;
                justify-content: center;
            }

            .table {
                font-size: 0.88rem;
            }

            .table thead th {
                padding: 10px 8px;
            }

            .table tbody td {
                padding: 10px 8px;
            }

            .badge {
                padding: 5px 8px;
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="mt-2">
        {{-- HEADER --}}
        <div class="header-section">
            <h4>Data User</h4>
            <a href="{{ route('users.create') }}" class="btn-custom">
                ➕ Tambah User
            </a>
        </div>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- TABLE WRAPPER --}}
        <div class="card">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Role</th>
                                <th class="d-none d-md-table-cell">Kandang</th>
                                <th class="text-center" style="width: 100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @if($user->username !== 'superadmin')
                                    <tr>
                                        <td class="fw-semibold">{{ $user->username }}</td>
                                        <td>{{ $user->nama }}</td>
                                        <td>{{ $user->no_hp }}</td>
    
                                        {{-- ROLE BADGE --}}
                                        <td>
                                            <span class="badge @if ($user->role == 'owner') bg-dark @elseif ($user->role == 'kepala_gudang') bg-warning @elseif ($user->role == 'kepala_kandang') bg-info @else bg-primary @endif">
                                                {{ str_replace('_', ' ', ucfirst($user->role)) }}
                                            </span>
                                        </td>
    
                                        {{-- KANDANG (Hidden on Mobile) --}}
                                        <td class="d-none d-md-table-cell">
                                            {{ $user->kandang->nama_kandang ?? '-' }}
                                        </td>
    
                                        {{-- AKSI DROPDOWN --}}
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    ⋮ Menu
                                                </button>
    
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                            ✏️ Edit Profil
                                                        </a>
                                                    </li>
    
                                                    @if ($user->role !== 'owner')
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item text-danger">
                                                                    🗑️ Hapus User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <span class="dropdown-item text-muted" style="cursor: not-allowed;">
                                                                🔒 Protected (Owner)
                                                            </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- FOOTER INFO --}}
                <div class="card-footer">
                    Total: <strong>{{ $users->count() }} User</strong>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">👤</div>
                    <h5>Data User Belum Tersedia</h5>
                    <p>Belum ada user yang terdaftar. Mulai tambahkan user baru sekarang.</p>
                    <a href="{{ route('users.create') }}" class="btn-custom">
                        ➕ Tambah User Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
