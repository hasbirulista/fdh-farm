@php
    use Illuminate\Support\Facades\Auth;
    $role = Auth::user()->role;
@endphp
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

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
            border-left: 5px solid #2d2d2d;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
        }

        .info-card.profit {
            border-left-color: #28a745;
        }

        .info-card i {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .info-card h6 {
            font-weight: 700;
            margin: 10px 0 5px 0;
            font-size: 1.1rem;
        }

        .info-card small {
            color: #666;
            font-size: 0.85rem;
        }

        .menu-card {
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        .menu-card:hover {
            color: inherit;
            text-decoration: none;
        }

        .menu-card .card {
            height: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.3s ease;
        }

        .menu-card .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }

        .menu-card i {
            font-size: 32px;
            color: #2d2d2d;
        }

        .menu-card h6 {
            font-weight: 600;
            margin-top: 8px;
            color: #333;
        }
    </style>

    <div class="">

        {{-- HEADER --}}
        <div class="header-section mt-2">
            <h4>Egg Grow Dashboard</h4>
        </div>

        {{-- INFO CARDS --}}
        <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">

            <div class="col">
                <div class="info-card">
                    <i class="fi fi-rr-wallet"></i>
                    <h6>Rp {{ number_format($saldo_toko, 0, ',', '.') }}</h6>
                    <small>Saldo Toko</small>
                </div>
            </div>

            <div class="col">
                <div class="info-card profit" style="cursor:pointer" onclick="openProfitModal()">
                    <i class="fi fi-rr-chart-histogram"></i>
                    <h6 id="profitValue">Rp {{ number_format($profit_hari_ini, 0, ',', '.') }}</h6>
                    <small id="profitLabel">Profit Hari Ini</small>
                </div>
            </div>

            <div class="col">
                <div class="info-card" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#modalStokOmega">
                    <i class="fi fi-rr-egg"></i>
                    <h6>{{ $stok_telur_omega_toko_view }} Kg</h6>
                    <small>Stok Telur Omega</small>
                </div>
            </div>

            <div class="col">
                <div class="info-card profit" style="cursor:pointer" data-bs-toggle="modal"
                    data-bs-target="#modalStokBiasa">
                    <i class="fi fi-rr-egg"></i>
                    <h6>{{ $stok_telur_biasa_toko_view }} Kg</h6>
                    <small>Stok Telur Biasa</small>
                </div>
            </div>


        </div>

        {{-- MENU CARDS --}}
        <div class="row row-cols-2 row-cols-md-3 g-3 text-center">

            <div class="col">
                <a href="/dashboard/egg-grow/transaksi" class="menu-card">
                    <div class="card">
                        <div class="card-body">
                            <i class="fi fi-rr-shopping-cart-add"></i>
                            <h6 class="mb-0">Transaksi Penjualan</h6>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="/dashboard/egg-grow/barang-masuk" class="menu-card">
                    <div class="card">
                        <div class="card-body">
                            <i class="fi fi-rr-download"></i>
                            <h6 class="mb-0">Barang Masuk</h6>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="/dashboard/egg-grow/pengeluaran" class="menu-card">
                    <div class="card">
                        <div class="card-body">
                            <i class="fi fi-rr-coins"></i>
                            <h6 class="mb-0">Pengeluaran</h6>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="/dashboard/egg-grow/pelanggan" class="menu-card">
                    <div class="card">
                        <div class="card-body">
                            <i class="fi fi-rr-user"></i>
                            <h6 class="mb-0">Pelanggan</h6>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="/dashboard/egg-grow/follow-up" class="menu-card">
                    <div class="card">
                        <div class="card-body">
                            <i class="fi fi-rr-handshake"></i>
                            <h6 class="mb-0">Follow Up</h6>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>

    {{-- MODAL PROFIT --}}
    <div class="modal fade" id="profitModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Tanggal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="date" class="form-control" id="profitDate" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary w-100" onclick="loadProfit()">Tampilkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL STOK --}}
    {{-- MODAL STOK TELUR OMEGA --}}
    @if ($role === 'owner')
        <div class="modal fade" id="modalStokOmega" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('stok.telur.toko') }}" class="modal-content">
                    @csrf
                    <input type="hidden" name="jenis_telur" value="Omega">
                    <input type="hidden" name="jenis_stok" value="toko">

                    <div class="modal-header">
                        <h5 class="modal-title">Update Stok Telur Omega (Toko)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <input type="number" name="total_stok" class="form-control"
                                value="{{ $stok_telur_omega_toko_raw }}" min="0" step="0.01" required>
                            <span class="input-group-text">Gr</span>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmOmega" name="confirm" required>
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
                <form method="POST" action="{{ route('stok.telur.toko') }}" class="modal-content">
                    @csrf
                    <input type="hidden" name="jenis_telur" value="Biasa">
                    <input type="hidden" name="jenis_stok" value="toko">

                    <div class="modal-header">
                        <h5 class="modal-title">Update Stok Telur Biasa (Toko)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <input type="number" name="total_stok" class="form-control"
                                value="{{ $stok_telur_biasa_toko_raw }}" min="0" step="0.01" required>
                            <span class="input-group-text">Gr</span>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmBiasa" name="confirm" required>
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

    {{-- ✅ Konfirmasi checkbox sebelum submit --}}
    <script>
        // Omega
        document.querySelector('#modalStokOmega form').addEventListener('submit', function(e) {
            if (!document.getElementById('confirmOmega').checked) {
                e.preventDefault();
                alert('Centang dulu konfirmasi sebelum menyimpan!');
            }
        });

        // Biasa
        document.querySelector('#modalStokBiasa form').addEventListener('submit', function(e) {
            if (!document.getElementById('confirmBiasa').checked) {
                e.preventDefault();
                alert('Centang dulu konfirmasi sebelum menyimpan!');
            }
        });
    </script>



    <script>
        function openProfitModal() {
            new bootstrap.Modal(document.getElementById('profitModal')).show();
        }

        function loadProfit() {
            let tanggal = document.getElementById('profitDate').value;

            fetch(`/dashboard/egg-grow/profit-by-date?tanggal=${tanggal}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('profitValue').innerText = 'Rp ' + data.profit;
                    document.getElementById('profitLabel').innerText = 'Profit ' + tanggal;
                    bootstrap.Modal.getInstance(document.getElementById('profitModal')).hide();
                });
        }
    </script>
@endsection
