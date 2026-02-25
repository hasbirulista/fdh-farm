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

        .chart-container {
            position: relative;
            width: 100%;
            overflow-x: auto;
        }

        .chart-wrapper {
            min-width: 600px;
            /* supaya bisa scroll kalau kandang banyak */
        }
    </style>

    <div class="">

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="header-section mt-2">
            <h4>Dashboard Owner</h4>
        </div>

        {{-- ================= HIGHLIGHT SALDO ================= --}}
        <div class="row g-3 mb-4">
            {{-- SALDO GUDANG --}}
            <div class="col-md-6 col-12">
                <div class="card shadow-sm border-0 bg-success bg-gradient text-white h-100">
                    <button class="btn text-white text-start p-0" data-bs-toggle="modal" data-bs-target="#modalSaldoGudang">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <small class="opacity-75">Saldo FDH FARM</small>
                                <h3 class="fw-bold mb-0">
                                    Rp {{ number_format($saldoGudang) }}
                                </h3>
                            </div>

                        </div>
                    </button>
                </div>
            </div>

            {{-- SALDO TOKO --}}
            <div class="col-md-6 col-12">
                <div class="card shadow-sm border-0 bg-primary bg-gradient text-white h-100">
                    <button class="btn text-white text-start p-0" data-bs-toggle="modal" data-bs-target="#modalSaldoToko">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <small class="opacity-75">Saldo Egg Grow</small>
                                <h3 class="fw-bold mb-0">
                                    Rp {{ number_format($saldoToko) }}
                                </h3>
                            </div>

                        </div>
                    </button>
                </div>
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('dashboard') }}" class="row g-2 mb-4">
            <div class="col-auto">
                <select id="bulan" name="bulan" class="form-select">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <select id="tahun" name="tahun" class="form-select">
                    @for ($y = now()->year - 5; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Filter</button>
            </div>
        </form>

        {{-- HEADER Gudang --}}
        <div class="mb-4 border-bottom pb-2">
            <h5 class="fw-bold mb-0">FDH FARM</h5>
            <small class="text-muted">{{ \Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->translatedFormat('F') }}
                {{ $tahun }}</small>
        </div>
        {{-- ================= SUMMARY ================= --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Produksi Hari Ini</small>
                        <h3 class="fw-bold text-success">{{ number_format($produksiHariIni ?? 0, 1) }}%</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Total Produksi
                            {{ \Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->translatedFormat('F') }}
                            {{ $tahun }}</small>
                        <h3 class="fw-bold text-primary">{{ number_format($rataRataBulanIni ?? 0, 1) }}%</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Telur Hari Ini</small>
                        <h3 class="fw-bold">{{ number_format($telurHariIni->total_butir ?? 0) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Populasi Ayam</small>
                        <h3 class="fw-bold">{{ number_format($totalPopulasi ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= GUDANG ================= --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Pemasukkan</small>
                        <h5 class="fw-bold text-success">
                            Rp {{ number_format($pemasukkanGudangBulanIni) }}
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Pengeluaran</small>
                        <h5 class="fw-bold text-danger">
                            Rp {{ number_format($pengeluaranGudangBulanIni) }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= PRODUKSI ================= --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Produksi Harian Bulan
                        {{ \Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->translatedFormat('F') }}</div>
                    <div class="card-body">
                        <canvas id="chartProduksiHarian"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Produksi Bulanan Tahun {{ $tahun }}</div>
                    <div class="card-body">
                        <canvas id="chartProduksiBulanan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= PRODUKSI PER KANDANG ================= --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Produksi Kandang Harian Bulan
                        {{ \Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->translatedFormat('F') }}</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div class="chart-wrapper">
                                <canvas id="chartProduksiKandangHarian"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Produksi Kandang Bulanan Tahun {{ $tahun }}</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div class="chart-wrapper">
                                <canvas id="chartProduksiKandangBulanan"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= TOKO ================= --}}
        {{-- HEADER Toko --}}
        <div class="mb-4 border-bottom pb-2">
            <h5 class="fw-bold mb-0">EGG GROW</h5>
            <small class="text-muted">{{ \Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->translatedFormat('F') }}
                {{ $tahun }}</small>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Penjualan</small>
                        <h5 class="fw-bold">Rp {{ number_format($penjualanTokoBulanIni) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Pengeluaran</small>
                        <h5 class="fw-bold text-danger">
                            Rp {{ number_format($pengeluaranTokoBulanIni) }}
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Laba</small>
                        <h5 class="fw-bold text-primary">
                            Rp {{ number_format($labaToko) }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= CHART PENJUALAN ================= --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Penjualan Harian Bulan
                        {{ \Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)->translatedFormat('F') }}</div>
                    <div class="card-body">
                        <canvas id="chartPenjualanHarian"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Penjualan Bulanan Tahun {{ $tahun }}</div>
                    <div class="card-body">
                        <canvas id="chartPenjualanBulanan"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= MODAL SALDO GUDANG ================= --}}
    <div class="modal fade" id="modalSaldoGudang" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('dashboard.saldo.gudang') }}" class="modal-content"
                onsubmit="return document.getElementById('confirmGudang').checked">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Update Saldo Gudang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="number" name="saldo" class="form-control mb-3" value="{{ $saldoGudang }}"
                        min="0" required>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmGudang">
                        <label class="form-check-label">
                            Saya yakin ingin mengubah saldo gudang
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

    {{-- ================= MODAL SALDO TOKO ================= --}}
    <div class="modal fade" id="modalSaldoToko" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('dashboard.saldo.toko') }}" class="modal-content"
                onsubmit="return document.getElementById('confirmToko').checked">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Update Saldo Toko</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="number" name="saldo" class="form-control mb-3" value="{{ $saldoToko }}"
                        min="0" required>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmToko">
                        <label class="form-check-label">
                            Saya yakin ingin mengubah saldo toko
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

    {{-- ================= CHART JS ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('chartProduksiHarian'), {
            type: 'line',
            data: {
                labels: @json($labelProduksiHarianFilter),
                datasets: [{
                    label: 'Produksi %',
                    data: @json($dataProduksiHarianFilter),
                    tension: 0.3
                }]
            }
        });

        new Chart(document.getElementById('chartProduksiBulanan'), {
            type: 'bar',
            data: {
                labels: @json($labelProduksiBulananGlobal),
                datasets: [{
                    label: 'Produksi %',
                    data: @json($dataProduksiBulananGlobal)
                }]
            }
        });

        const kandangHarian = @json($produksiKandangHarian);

        // Ambil semua tanggal unik dari semua kandang
        const allDates = Array.from(
            new Set(
                Object.values(kandangHarian)
                .flat()
                .map(i => i.tanggal) // ambil tanggal full
            )
        ).sort(); // urutkan

        // Map tanggal menjadi hanya tanggal saja (19, 20, 21...)
        const labels = allDates.map(d => new Date(d).getDate());

        new Chart(document.getElementById('chartProduksiKandangHarian'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: Object.keys(kandangHarian).map(k => ({
                    label: k,
                    data: allDates.map(date => {
                        const found = kandangHarian[k].find(i => i.tanggal === date);
                        return found ? found.produksi : 0;
                    }),
                    tension: 0.3
                }))
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
            }
        });

        const kandangBulanan = @json($produksiKandangBulanan);
        new Chart(document.getElementById('chartProduksiKandangBulanan'), {
            type: 'bar',
            data: {
                labels: kandangBulanan[Object.keys(kandangBulanan)[0]]?.map(i => i.bulan) ?? [],
                datasets: Object.keys(kandangBulanan).map(k => ({
                    label: k,
                    data: kandangBulanan[k].map(i => i.produksi)
                }))
            }
        });

        new Chart(document.getElementById('chartPenjualanHarian'), {
            type: 'line',
            data: {
                labels: @json($labelPenjualanHarian),
                datasets: [{
                    label: 'Penjualan',
                    data: @json($dataPenjualanHarian),
                    tension: 0.3
                }]
            }
        });

        new Chart(document.getElementById('chartPenjualanBulanan'), {
            type: 'bar',
            data: {
                labels: @json($labelPenjualanBulanan),
                datasets: [{
                    label: 'Penjualan',
                    data: @json($dataPenjualanBulanan)
                }]
            }
        });
    </script>
@endsection
