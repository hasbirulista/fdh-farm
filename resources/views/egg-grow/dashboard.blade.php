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
            border-radius: 14px;
            padding: 18px 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        /* hover halus */
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        /* ICON BULAT */
        .info-card .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
            background: #f1f3f5;
            color: #2d2d2d;
        }

        /* WARNA KHUSUS */
        .info-card.profit .icon-box {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        /* VALUE */
        .info-card h6 {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
        }

        /* LABEL */
        .info-card small {
            color: #777;
            font-size: 0.8rem;
        }

        /* GARIS AKSEN TIPIS */
        .info-card::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #2d2d2d;
            opacity: 0.1;
        }

        .info-card.profit::after {
            background: #28a745;
        }

        /* CARD KHUSUS KEUANGAN (DOUBLE INFO) */
        .info-card.double {
            padding: 16px;
        }

        .info-card .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }

        .info-card .info-row span {
            font-size: 0.75rem;
            color: #777;
        }

        .info-card .info-row strong {
            font-size: 0.95rem;
            font-weight: 600;
        }

        /* WARNA KHUSUS */
        .info-card.credit .icon-box {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .info-card.credit::after {
            background: #007bff;
        }

        .info-card.harga .icon-box {
            background: rgba(255, 140, 0, 0.1);
            color: #ff8c00;
        }

        .info-card.harga::after {
            background: #ff8c00;
        }

        /* SUBTEXT UNTUK HARGA */
        .info-card .sub-text {
            font-size: 0.75rem;
            color: #999;
            margin-top: 2px;
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

    <div class="mb-4">

        {{-- HEADER --}}
        <div class="header-section mt-2">
            <h4>Egg Grow Dashboard</h4>
        </div>

        {{-- INFO CARDS --}}
        <div class="row g-3 mb-4">

            {{-- ===== BARIS 1 ===== --}}
            <div class="col-12 col-lg-6">
                <div class="info-card double h-100">
                    <div class="icon-box">
                        <i class="fi fi-rr-wallet"></i>
                    </div>

                    <h6 class="mb-2">Keuangan</h6>

                    <div class="info-row">
                        <strong>Saldo Total</strong>
                        <strong>Rp.{{ number_format($saldo_toko + $saldo_toko_credit + $saldo_telur_omega + $saldo_telur_biasa, 0, ',', '.') }},-</strong>
                    </div>

                    <div class="info-row">
                        <strong>Saldo Toko</strong>
                        <strong>Rp.{{ number_format($saldo_toko, 0, ',', '.') }},-</strong>
                    </div>

                    <div class="info-row">
                        <strong>Saldo Kredit</strong>
                        <strong>Rp.{{ number_format($saldo_toko_credit, 0, ',', '.') }},-</strong>
                    </div>

                    <hr>

                    <div class="info-row">
                        <strong>(SALDO BARANG)</strong>
                    </div>

                    <div class="info-row">
                        <strong>- Telur Biasa</strong>
                        <strong>Rp.{{ number_format($saldo_telur_biasa, 0, ',', '.') }},-</strong>
                    </div>

                    <div class="info-row">
                        <strong>- Telur Omega</strong>
                        <strong>Rp.{{ number_format($saldo_telur_omega, 0, ',', '.') }},-</strong>
                    </div>
                </div>
            </div>

            {{-- STOK (KANAN ATAS) --}}
            <div class="col-12 col-lg-6">
                <div class="info-card h-100">

                    {{-- HEADER --}}
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box">
                            <i class="fi fi-rr-egg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Stok Telur</h6>
                            <small>Total stok telur toko</small>
                        </div>
                    </div>

                    {{-- ISI --}}
                    <div class="d-flex flex-column gap-2">

                        {{-- OMEGA --}}
                        <div class="d-flex justify-content-between align-items-center p-2 rounded"
                            style="cursor:pointer; background:#f8f9fa;" data-bs-toggle="modal"
                            data-bs-target="#modalStokOmega">

                            <span>Omega</span>
                            <strong>{{ $stok_telur_omega_toko_view }} Kg</strong>
                        </div>

                        {{-- BIASA --}}
                        <div class="d-flex justify-content-between align-items-center p-2 rounded"
                            style="cursor:pointer; background:#f8f9fa;" data-bs-toggle="modal"
                            data-bs-target="#modalStokBiasa">

                            <span>Biasa</span>
                            <strong>{{ $stok_telur_biasa_toko_view }} Kg</strong>
                        </div>

                    </div>

                </div>
            </div>

            {{-- ===== BARIS 2 ===== --}}
            <div class="col-12 col-md-6">
                <div class="info-card profit h-100 d-flex flex-column justify-content-center" style="cursor:pointer"
                    onclick="openProfitModal()">

                    <div class="icon-box">
                        <i class="fi fi-rr-chart-histogram"></i>
                    </div>

                    <small id="profitLabel">Profit Hari Ini</small>
                    <h6 id="profitValue">Rp {{ number_format($profit_hari_ini, 0, ',', '.') }}</h6>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="info-card harga h-100 d-flex flex-column justify-content-center" style="cursor:pointer"
                    onclick="openHargaModal()">

                    <div class="icon-box">
                        <i class="fi fi-rr-tags"></i>
                    </div>
                    <small id="hargaLabel">Harga Telur Hari ini</small>
                    {{-- OMEGA --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="fw-bold">(Telur Omega)</small>
                        <strong id="hargaOmega">Rp.{{ number_format($harga_telur_omega, 0, ',', '.') }},-</strong>
                    </div>

                    {{-- BIASA --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="fw-bold">(Telur Biasa)</small>
                        <strong id="hargaBiasa">Rp.{{ number_format($harga_telur_biasa, 0, ',', '.') }},-</strong>
                    </div>

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
                <a href="/dashboard/egg-grow/kredit" class="menu-card">
                    <div class="card">
                        <div class="card-body">
                            <i class="fi fi-rr-credit-card"></i>
                            <h6 class="mb-0">Kredit</h6>
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
        </div>

    </div>

    {{-- MODAL HARGA TELUR --}}
    <div class="modal fade" id="hargaModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Tanggal Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="date" class="form-control" id="hargaDate" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning w-100" onclick="loadHarga()">Tampilkan</button>
                </div>
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

        function openHargaModal() {
            new bootstrap.Modal(document.getElementById('hargaModal')).show();
        }

        function loadHarga() {
            let tanggal = document.getElementById('hargaDate').value;

            fetch(`/dashboard/egg-grow/harga-by-date?tanggal=${tanggal}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('hargaOmega').innerText = 'Rp ' + data.omega;
                    document.getElementById('hargaBiasa').innerText = 'Rp ' + data.biasa;

                    document.getElementById('hargaLabel').innerText = 'Harga Telur ' + tanggal;

                    bootstrap.Modal.getInstance(document.getElementById('hargaModal')).hide();
                });
        }
    </script>
@endsection
