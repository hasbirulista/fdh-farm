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

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #2d2d2d;
            text-align: center;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .info-card i {
            font-size: 2rem;
            color: #2d2d2d;
            margin-bottom: 10px;
        }

        .info-card h6 {
            color: #333;
            margin-bottom: 8px;
        }

        .info-card small {
            color: #666;
            font-size: 0.9rem;
        }

        .menu-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
            border-top: 4px solid #2d2d2d;
        }

        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .menu-card i {
            font-size: 2.5rem;
            color: #2d2d2d;
            margin-bottom: 12px;
        }

        .menu-card h6 {
            color: #333;
            font-weight: 600;
            margin: 0;
        }

        .menu-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }
    </style>

    <div class="mt-2">

        {{-- HEADER --}}
        <div class="header-section">
            <h4>FDH Farm</h4>
        </div>

        {{-- INFO STOK --}}
        {{-- INFO STOK --}}
        <div class="row row-cols-2 row-cols-md-2 g-3 mb-4">

            <div class="col">
                <div class="info-card">
                    <button class="btn p-0 w-100 text-center" data-bs-toggle="modal" data-bs-target="#modalStokOmega">
                        <i class="fi fi-rr-egg"></i>
                        <h6 class="fw-bold mb-1">Stok Telur Omega</h6>
                        <small>
                            {{ number_format($stok_telur_omega_gudang, 2, ',', '.') }} Kg
                        </small>
                    </button>
                </div>
            </div>

            <div class="col">
                <div class="info-card">
                    <button class="btn p-0 w-100 text-center" data-bs-toggle="modal" data-bs-target="#modalStokBiasa">
                        <i class="fi fi-rr-egg"></i>
                        <h6 class="fw-bold mb-1">Stok Telur Biasa</h6>
                        <small>
                            {{ number_format($stok_telur_biasa_gudang, 2, ',', '.') }} Kg
                        </small>
                    </button>
                </div>
            </div>

        </div>


        {{-- MENU --}}
        <div class="row row-cols-2 row-cols-md-3 g-3">

            <div class="col">
                <a href="/dashboard/gudang/barang-masuk" class="text-decoration-none text-dark">
                    <div class="menu-card">
                        <i class="fi fi-rr-download"></i>
                        <h6>Barang Masuk</h6>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="/dashboard/gudang/barang-keluar" class="text-decoration-none text-dark">
                    <div class="menu-card">
                        <i class="fi fi-rr-upload"></i>
                        <h6>Barang Keluar</h6>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="/dashboard/gudang/pengeluaran" class="text-decoration-none text-dark">
                    <div class="menu-card">
                        <i class="fi fi-rr-coins"></i>
                        <h6>Pengeluaran</h6>
                    </div>
                </a>
            </div>

        </div>

    </div>
    {{-- MODAL STOK TELUR OMEGA --}}
    @if ($role === 'owner')
        <div class="modal fade" id="modalStokOmega" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('stok.telur.gudang') }}" class="modal-content"
                    onsubmit="return document.getElementById('confirmOmega').checked">

                    @csrf

                    <input type="hidden" name="jenis_telur" value="omega">
                    <input type="hidden" name="jenis_stok" value="gudang">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Stok Telur Omega</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <input type="number" name="total_stok" class="form-control"
                                value="{{ $stok_telur_omega_gram }}" min="0" step="0.01" required>
                            <span class="input-group-text">Gr</span>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmOmega">
                            <label class="form-check-label">
                                Saya yakin ingin mengubah stok telur omega
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

        {{-- MODAL STOK TELUR BIASA --}}

        <div class="modal fade" id="modalStokBiasa" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('stok.telur.gudang') }}" class="modal-content"
                    onsubmit="return document.getElementById('confirmBiasa').checked">

                    @csrf

                    <input type="hidden" name="jenis_telur" value="biasa">
                    <input type="hidden" name="jenis_stok" value="gudang">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Stok Telur Biasa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <input type="number" name="total_stok" class="form-control"
                                value="{{ $stok_telur_biasa_gram }}" min="0" step="0.01" required>
                            <span class="input-group-text">Gr</span>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmBiasa">
                            <label class="form-check-label">
                                Saya yakin ingin mengubah stok telur biasa
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
@endsection
