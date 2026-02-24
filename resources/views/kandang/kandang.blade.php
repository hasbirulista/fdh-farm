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
            --success: #28a745;
            --info: #17a2b8;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .header-section h4 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }

        .btn-add-kandang {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
        }

        .btn-add-kandang:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.35);
            color: white;
        }

        .kandang-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }

        .kandang-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .kandang-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .kandang-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .kandang-icon {
            font-size: 36px;
            opacity: 0.9;
        }

        .kandang-name {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }

        .kandang-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: var(--light-bg);
            border-radius: 8px;
            border-left: 4px solid var(--primary);
        }

        .info-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .populasi-badge {
            display: inline-block;
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .production-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f0f0 100%);
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid var(--success);
        }

        .production-title {
            font-size: 0.8rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .production-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .production-item {
            text-align: center;
        }

        .production-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--success);
        }

        .production-label {
            font-size: 0.75rem;
            color: #666;
            margin-top: 4px;
        }

        .no-data {
            text-align: center;
            color: #999;
            font-size: 0.85rem;
            padding: 8px 0;
        }

        .pakan-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .pakan-item {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid var(--info);
            text-align: center;
        }

        .pakan-label {
            font-size: 0.75rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
        }

        .pakan-value {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--primary);
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .kandang-grid {
                grid-template-columns: 1fr;
            }

            .header-section {
                padding: 20px 15px;
            }

            .header-section h4 {
                font-size: 1.6rem;
            }

            .production-grid {
                grid-template-columns: 1fr;
            }

            .kandang-header {
                padding: 15px;
            }

            .kandang-name {
                font-size: 1.1rem;
            }

            .kandang-body {
                padding: 15px;
            }
        }
    </style>

    <div class="header-section d-flex justify-content-between align-items-center flex-wrap gap-3 mt-2">
        <div>
            <h4>Manajemen Kandang</h4>
        </div>
        @if ($role === 'owner')
            <a href="/dashboard/kandang/tambah-kandang" class="btn-add-kandang">+ Tambah Kandang</a>
        @endif
    </div>

    <div class="kandang-grid">
        @forelse ($kandangs as $kandang)
            <div class="kandang-card">

                <a href="{{ route('produksi.perKandang', ['namaKandang' => $kandang->nama_kandang]) }}"
                    class="text-decoration-none">
                    <div class="kandang-header">
                        <div class="kandang-icon">🐔</div>
                        <h5 class="kandang-name">{{ $kandang->nama_kandang }} - {{ $kandang->chicken_in }}</h5>
                    </div>
                </a>
                <div class="kandang-body">
                    <!-- Populasi Ayam -->
                    <div class="info-row">
                        <div class="info-label">📊 Populasi</div>
                        <span class="populasi-badge">{{ number_format($kandang->populasi_ayam) }} ekor</span>
                    </div>

                    <!-- Data Produksi Terakhir -->
                    @if ($kandang->lastProduksi)
                        <div class="production-section">
                            <div class="production-title">📈 Produksi Terakhir <span
                                    style="font-size: 0.75rem; color: #999; margin-bottom: 12px; text-align: center;">({{ \Carbon\Carbon::parse($kandang->lastProduksi->tanggal_produksi)->translatedFormat('d F Y') }})</span>
                            </div>

                            <div class="production-grid">
                                <div class="production-item">
                                    <div class="production-value">{{ $kandang->lastProduksi->jumlah_gram / 1000 }} Kg
                                    </div>
                                    <div class="production-label">Berat Produksi</div>
                                </div>
                                <div class="production-item">
                                    <div class="production-value">
                                        {{ number_format($kandang->lastProduksi->persentase_produksi, 2) }}%</div>
                                    <div class="production-label">Persentase Produksi</div>
                                </div>
                            </div>
                            <div class="production-grid" style="margin-top: 10px;">
                                <div class="production-item">
                                    <div class="production-value" style="color: #ff9800;">
                                        {{ $kandang->lastProduksi->usia }}
                                    </div>
                                    <div class="production-label">Usia (Minggu)</div>
                                </div>
                                <div class="production-item">
                                    <div class="production-value" style="color: #9c27b0;">
                                        @php
                                            $beratKg = $kandang->lastProduksi->jumlah_gram / 1000;
                                            $butirPerKg =
                                                $beratKg > 0 ? $kandang->lastProduksi->jumlah_butir / $beratKg : 0;
                                        @endphp
                                        {{ number_format($butirPerKg, 2) }}
                                    </div>
                                    <div class="production-label">Butir/Kg</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="production-section">
                            <div class="no-data">❌ Belum ada data produksi</div>
                        </div>
                    @endif

                    <!-- Stok Pakan -->
                    <div>
                        <div
                            style="font-size: 0.85rem; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                            🌾 Stok Pakan</div>
                        <div class="pakan-info">
                            @php
                                $grower = $kandang->kandangPakan->firstWhere('stokPakan.jenis_pakan', 'Grower');
                                $layer = $kandang->kandangPakan->firstWhere('stokPakan.jenis_pakan', 'Layer');

                                $growerKg = ($grower->stok ?? 0) / 1000;
                                $layerKg = ($layer->stok ?? 0) / 1000;
                            @endphp
                            <div class="pakan-item">
                                @if ($role === 'owner')
                                    <button type="button" class="btn p-0 w-100 text-center" data-bs-toggle="modal"
                                        data-bs-target="#modalGrower{{ $kandang->id }}">
                                        <div class="pakan-label">Grower</div>
                                        <div class="pakan-value">{{ number_format($growerKg, 2) }} kg</div>
                                    </button>
                                @else
                                    <div class="pakan-label">Grower</div>
                                    <div class="pakan-value">{{ number_format($growerKg, 2) }} kg</div>
                                @endif
                            </div>
                            <div class="pakan-item">
                                @if ($role === 'owner')
                                    <button type="button" class="btn p-0 w-100 text-center" data-bs-toggle="modal"
                                        data-bs-target="#modalLayer{{ $kandang->id }}">
                                        <div class="pakan-label">Layer</div>
                                        <div class="pakan-value">{{ number_format($layerKg, 2) }} kg</div>
                                    </button>
                                @else
                                    <div class="pakan-label">Layer</div>
                                    <div class="pakan-value">{{ number_format($layerKg, 2) }} kg</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($role === 'owner')
                <div class="modal fade" id="modalGrower{{ $kandang->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('kandang.update.pakan') }}" class="modal-content">
                            @csrf

                            <input type="hidden" name="kandang_id" value="{{ $kandang->id }}">
                            <input type="hidden" name="jenis_pakan" value="Grower">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Update Stok Grower - {{ $kandang->nama_kandang }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <input type="number" name="stok" class="form-control"
                                        value="{{ $grower->stok ?? 0 }}" min="0" required>
                                    <span class="input-group-text">Gram</span>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" required>
                                    <label class="form-check-label">
                                        Saya yakin ingin mengubah stok Grower
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
            @if ($role === 'owner')
                <div class="modal fade" id="modalLayer{{ $kandang->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('kandang.update.pakan') }}" class="modal-content">
                            @csrf

                            <input type="hidden" name="kandang_id" value="{{ $kandang->id }}">
                            <input type="hidden" name="jenis_pakan" value="Layer">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Update Stok Layer - {{ $kandang->nama_kandang }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <input type="number" name="stok" class="form-control"
                                        value="{{ $layer->stok ?? 0 }}" min="0" required>
                                    <span class="input-group-text">Gram</span>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" required>
                                    <label class="form-check-label">
                                        Saya yakin ingin mengubah stok Layer
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
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #999;">
                <h5>📭 Belum ada data kandang</h5>
            </div>
        @endforelse
    </div>
@endsection
