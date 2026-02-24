@extends('partials.master')

@section('content')
    <style>
        :root {
            --primary: #2d2d2d;
            --secondary: #1a1a1a;
            --light-bg: #f5f5f5;
            --border-light: #e0e0e0;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 40px 30px;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            animation: slideDown 0.5s ease;
        }

        .header-section h2 {
            margin: 0;
            font-weight: 700;
            font-size: 2.2rem;
            letter-spacing: -0.5px;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            border-left: 5px solid var(--primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-bg);
        }

        .form-section-title h5 {
            margin: 0;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.3rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control,
        .form-select {
            border: 2px solid var(--border-light);
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
            font-family: inherit;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(45, 45, 45, 0.1);
        }

        .result-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f0f0 100%);
            border-left: 4px solid var(--primary);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .result-box-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .result-box-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            word-break: break-word;
        }

        textarea {
            border: 2px solid var(--border-light);
            border-radius: 8px;
            padding: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
            resize: vertical;
            font-family: inherit;
            min-height: 100px;
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(45, 45, 45, 0.1);
        }

        .warning-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 15px 20px;
            color: #856404;
            font-weight: 600;
            display: none;
            margin-top: 15px;
            animation: slideUp 0.3s ease;
        }

        .warning-box.show {
            display: block;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            padding: 30px 0;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 16px 50px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(45, 45, 45, 0.25);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(45, 45, 45, 0.35);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            border-radius: 8px;
            border: 2px solid;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .header-section {
                padding: 25px 20px;
                margin-bottom: 25px;
            }

            .header-section h2 {
                font-size: 1.8rem;
            }

            .form-section {
                padding: 20px;
                margin-bottom: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .form-section-title h5 {
                font-size: 1.1rem;
            }

            .result-box-value {
                font-size: 1.5rem;
            }

            .btn-submit {
                width: 100%;
                padding: 14px 30px;
            }
        }
    </style>

    <div class="header-section mt-2">
        <h2>✏️ Edit Data Produksi</h2>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <form action="/dashboard/kandang/produksi/{{ $data_produksi->id }}" method="POST" id="produksiForm">
        @method('PUT')
        @csrf

        <!-- 📅 Informasi Dasar -->
        <div class="form-section">
            <div class="form-section-title">
                <span style="font-size: 1.8rem;">📅</span>
                <h5>Informasi Dasar</h5>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_produksi" class="form-label">Tanggal Produksi</label>
                    <input type="date" required name="tanggal_produksi" class="form-control"
                        value="{{ $data_produksi->tanggal_produksi }}">
                </div>
                <div class="form-group">
                    <label for="kandang_select" class="form-label">Pilih Kandang</label>
                    <select id="kandang_select" required name="kandang_id" class="form-select">
                        @foreach ($data_kandang as $kandang)
                            <option value="{{ $kandang->id }}" data-populasi="{{ $kandang->populasi_ayam }}"
                                {{ $data_produksi->nama_kandang == $kandang->nama_kandang ? 'selected' : '' }}>
                                {{ $kandang->nama_kandang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" id="populasi_ayam" name="populasi_ayam" value="{{ $data_produksi->populasi_ayam }}">
        </div>

        <!-- 🐔 Data Kesehatan Ayam -->
        <div class="form-section">
            <div class="form-section-title">
                <span style="font-size: 1.8rem;">🐔</span>
                <h5>Data Ayam</h5>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="usia" class="form-label">Usia (Minggu)</label>
                    <input type="number" required name="usia" class="form-control" min="0"
                        value="{{ $data_produksi->usia }}">
                </div>
                <div class="form-group">
                    <label for="mati" class="form-label">Jumlah Ayam Mati</label>
                    <input type="number" required name="mati" class="form-control" min="0"
                        value="{{ $data_produksi->mati }}">
                </div>
                <div class="form-group">
                    <label for="apkir" class="form-label">Jumlah Ayam Apkir</label>
                    <input type="number" required name="apkir" class="form-control" min="0"
                        value="{{ $data_produksi->apkir }}">
                </div>
            </div>
        </div>

        <!-- 🥚 Data Produksi Telur -->
        <div class="form-section">
            <div class="form-section-title">
                <span style="font-size: 1.8rem;">🥚</span>
                <h5>Data Produksi Telur</h5>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_telur" class="form-label">Jenis Telur</label>
                    <select id="jenis_telur" required name="jenis_telur" class="form-select">
                        <option value="{{ $data_produksi->jenis_telur }}" selected>{{ $data_produksi->jenis_telur }}
                        </option>
                        @if ($data_produksi->jenis_telur == 'Omega')
                            <option value="Biasa">Biasa</option>
                        @else
                            <option value="Omega">Omega</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah_butir" class="form-label">Jumlah Butir</label>
                    <input type="number" required id="jumlah_butir" name="jumlah_butir" class="form-control" min="0"
                        value="{{ $data_produksi->jumlah_butir }}">
                </div>
                <div class="form-group">
                    <label for="jumlah_pecah" class="form-label">Jumlah Pecah</label>
                    <input type="number" required name="jumlah_pecah" class="form-control" min="0"
                        value="{{ $data_produksi->jumlah_pecah }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="jumlah_gram" class="form-label">Jumlah Total Gram Telur</label>
                    <input type="number" required name="jumlah_gram" class="form-control" min="0"
                        value="{{ $data_produksi->jumlah_gram }}">
                </div>
                <div class="result-box">
                    <div class="result-box-label">📊 Persentase Produksi</div>
                    <div class="result-box-value" id="persentase_view">0.00%</div>
                    <input type="hidden" id="persentase_produksi" name="persentase_produksi">
                </div>
            </div>
        </div>

        <!-- 🌾 Perhitungan Pakan -->
        <div class="form-section">
            <div class="form-section-title">
                <span style="font-size: 1.8rem;">🌾</span>
                <h5>Pemberian Pakan</h5>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="berat_pakan_per_ayam" class="form-label">Berat Pakan Per Ayam (gr)</label>
                    <input type="number" step="0.01" id="berat_pakan_per_ayam" required name="berat_pakan_per_ayam"
                        class="form-control" min="0"
                        value="{{ $data_produksi->grower_per_ayam != 0 || $data_produksi->layer_per_ayam != 0 ? $data_produksi->grower_per_ayam + $data_produksi->layer_per_ayam : '' }}">
                </div>
                <div class="form-group">
                    <label for="persentase_grower" class="form-label">Persentase Grower (%)</label>
                    <input type="number" step="0.01" id="persentase_grower" required name="persentase_grower"
                        class="form-control" min="0"
                        value="{{ $data_produksi->grower_per_ayam > 0 ? ($data_produksi->grower_per_ayam / ($data_produksi->grower_per_ayam + $data_produksi->layer_per_ayam)) * 100 : '' }}">
                </div>
                <div class="form-group">
                    <label for="persentase_layer" class="form-label">Persentase Layer (%)</label>
                    <input type="number" step="0.01" id="persentase_layer" required name="persentase_layer"
                        class="form-control" min="0"
                        value="{{ $data_produksi->layer_per_ayam > 0 ? ($data_produksi->layer_per_ayam / ($data_produksi->grower_per_ayam + $data_produksi->layer_per_ayam)) * 100 : '' }}">
                </div>
            </div>

            <div class="form-row">
                <div class="result-box">
                    <div class="result-box-label">🌾 Pakan Grower (gr)</div>
                    <div class="result-box-value" id="pakan_A">{{ $data_produksi->pakan_A }}</div>
                    <input type="hidden" id="pakan_A_hidden" name="pakan_A">
                </div>
                <div class="result-box">
                    <div class="result-box-label">🌾 Pakan Layer (gr)</div>
                    <div class="result-box-value" id="pakan_B">{{ $data_produksi->pakan_B }}</div>
                    <input type="hidden" id="pakan_B_hidden" name="pakan_B">
                </div>
            </div>

            <div id="persentase_warning" class="warning-box">
                ⚠️ Total persentase Grower + Layer harus 100%
            </div>
        </div>

        <!-- 📝 Catatan -->
        <div class="form-section">
            <div class="form-section-title">
                <span style="font-size: 1.8rem;">📝</span>
                <h5>Catatan & Keterangan</h5>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="kegiatan" class="form-label">Kegiatan</label>
                <textarea name="kegiatan" id="kegiatan" class="form-control" required>{{ $data_produksi->kegiatan }}</textarea>
            </div>
            <div>
                <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" required>{{ $data_produksi->keterangan }}</textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="btn-container">
            <button class="btn-submit" type="submit" id="submitBtn">Update Data Produksi</button>
        </div>
    </form>

    <script>
        // Initialize on page load
        window.addEventListener('DOMContentLoaded', function() {
            validatePersentase();
            hitungPakan();
            hitungProduksi();
        });

        // ===============================
        // 🔢 HITUNG POPULASI AKTUAL
        // ===============================
        function getPopulasiAktual() {
            const populasiAwal = parseFloat(document.getElementById('populasi_ayam').value) || 0;
            const mati = parseFloat(document.querySelector('input[name="mati"]').value) || 0;
            const apkir = parseFloat(document.querySelector('input[name="apkir"]').value) || 0;

            const populasiAkhir = populasiAwal - mati - apkir;

            return populasiAkhir > 0 ? populasiAkhir : 0;
        }

        // ===============================
        // 🌾 HITUNG PAKAN
        // ===============================
        function hitungPakan() {
            const populasi = getPopulasiAktual();
            const beratPerAyam = parseFloat(document.getElementById('berat_pakan_per_ayam').value) || 0;
            const persentaseGrower = parseFloat(document.getElementById('persentase_grower').value) || 0;
            const persentaseLayer = parseFloat(document.getElementById('persentase_layer').value) || 0;

            const pakanGrower = (persentaseGrower / 100) * beratPerAyam * populasi;
            const pakanLayer = (persentaseLayer / 100) * beratPerAyam * populasi;

            document.getElementById('pakan_A').textContent = pakanGrower.toFixed(2);
            document.getElementById('pakan_A_hidden').value = pakanGrower.toFixed(2);

            document.getElementById('pakan_B').textContent = pakanLayer.toFixed(2);
            document.getElementById('pakan_B_hidden').value = pakanLayer.toFixed(2);
        }

        // ===============================
        // 📊 HITUNG PERSENTASE PRODUKSI
        // ===============================
        function hitungProduksi() {
            const populasi = getPopulasiAktual();
            const butir = parseFloat(document.getElementById('jumlah_butir').value) || 0;

            const view = document.getElementById('persentase_view');
            const hidden = document.getElementById('persentase_produksi');

            if (populasi > 0 && butir >= 0) {
                const hasil = (butir / populasi) * 100;

                view.textContent = hasil.toFixed(2) + '%';
                hidden.value = hasil.toFixed(2);
            } else {
                view.textContent = '0.00%';
                hidden.value = '';
            }
        }

        // ===============================
        // ✅ VALIDASI PERSENTASE 100%
        // ===============================
        function validatePersentase() {
            const g = parseFloat(document.getElementById('persentase_grower').value) || 0;
            const l = parseFloat(document.getElementById('persentase_layer').value) || 0;
            const total = g + l;

            const growerInput = document.getElementById('persentase_grower');
            const layerInput = document.getElementById('persentase_layer');
            const warning = document.getElementById('persentase_warning');
            const submitBtn = document.getElementById('submitBtn');

            if (total !== 100) {
                growerInput.style.borderColor = '#dc3545';
                layerInput.style.borderColor = '#dc3545';
                warning.textContent = `⚠️ Total persentase Grower + Layer harus 100% (saat ini ${total}%)`;
                warning.classList.add('show');
                submitBtn.disabled = true;
            } else {
                growerInput.style.borderColor = '';
                layerInput.style.borderColor = '';
                warning.classList.remove('show');
                submitBtn.disabled = false;
            }
        }

        // ===============================
        // 🎯 EVENT LISTENER
        // ===============================

        // Pakan
        document.getElementById('berat_pakan_per_ayam')
            .addEventListener('input', function() {
                validatePersentase();
                hitungPakan();
            });

        document.getElementById('persentase_grower')
            .addEventListener('input', function() {
                validatePersentase();
                hitungPakan();
            });

        document.getElementById('persentase_layer')
            .addEventListener('input', function() {
                validatePersentase();
                hitungPakan();
            });

        // Produksi
        document.getElementById('jumlah_butir')
            .addEventListener('input', hitungProduksi);

        // Mati & Apkir → update semua
        document.querySelector('input[name="mati"]')
            .addEventListener('input', function() {
                hitungPakan();
                hitungProduksi();
            });

        document.querySelector('input[name="apkir"]')
            .addEventListener('input', function() {
                hitungPakan();
                hitungProduksi();
            });

        // ===============================
        // 🏠 KETIKA PILIH KANDANG
        // ===============================
        const kandangSelect = document.getElementById('kandang_select');
        const populasiInput = document.getElementById('populasi_ayam');

        kandangSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const populasi = selectedOption.getAttribute('data-populasi');
            const lastAgeInfo = document.getElementById('last-age-info');

            if (populasi) {
                populasiInput.value = populasi;

                const lastAge = selectedOption.getAttribute('data-last-age');
                const lastDate = selectedOption.getAttribute('data-last-date');

                if (lastAge && lastDate) {
                    lastAgeInfo.innerHTML =
                        `Produksi terakhir : usia <strong>${lastAge}</strong> minggu pada ${lastDate}`;
                } else {
                    lastAgeInfo.innerHTML = 'Belum ada produksi sebelumnya';
                }

                validatePersentase();
                hitungPakan();
                hitungProduksi();
            } else {
                populasiInput.value = '';
                lastAgeInfo.innerHTML = '';
            }
        });

        // ===============================
        // 🚫 CEGAH SUBMIT JIKA ≠ 100%
        // ===============================
        document.getElementById('produksiForm').addEventListener('submit', function(e) {
            const g = parseFloat(document.getElementById('persentase_grower').value) || 0;
            const l = parseFloat(document.getElementById('persentase_layer').value) || 0;

            if ((g + l) !== 100) {
                e.preventDefault();
                alert('Total persentase Grower + Layer harus 100%');
            }
        });
    </script>
@endsection
