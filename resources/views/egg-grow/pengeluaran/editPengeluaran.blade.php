@extends('partials.master')

@section('content')
    <style>
        .page-header {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            background: white;
            border-left: 5px solid #2d2d2d;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .form-section:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .form-section h5 {
            color: #2d2d2d;
            margin-bottom: 20px;
            font-weight: 600;
            display: block;
            font-size: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #2d2d2d;
            box-shadow: 0 0 0 3px rgba(45, 45, 45, 0.1);
            outline: none;
        }

        .form-group input[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .form-group input[disabled],
        .form-group select[disabled] {
            background-color: #f5f5f5;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .hidden {
            display: none !important;
        }

        .form-section.visible {
            display: flex !important;
        }

        .btn-submit {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(45, 45, 45, 0.2);
        }

        .alert {
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-section {
                padding: 15px;
            }

            .page-header {
                padding: 15px;
            }
        }
    </style>

    <div class="page-header mt-2">
        <h2>✏️ Edit Pengeluaran</h2>
    </div>

    {{-- ALERT ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('egg-grow.updatePengeluaran', $data_pengeluaran->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- SECTION 1: INFORMASI DASAR --}}
        <div class="form-section">
            <h5>📋 Informasi Dasar</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                    <input type="text" id="jenis_pengeluaran_display" class="form-control" readonly
                        value="{{ $data_pengeluaran->jenis_pengeluaran === 'telur pecah' ? '🥚 Telur Pecah' : '📌 Lainnya' }}">
                    <input type="hidden" name="jenis_pengeluaran" value="{{ $data_pengeluaran->jenis_pengeluaran }}">
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                        value="{{ old('tanggal', $data_pengeluaran->tanggal) }}" required>
                </div>
            </div>
        </div>

        {{-- SECTION 2: TELUR PECAH --}}
        <div id="formTelurPecah" class="form-section {{ $data_pengeluaran->jenis_pengeluaran === 'telur pecah' ? 'visible' : 'hidden' }}">
            <h5>🥚 Detail Telur Pecah</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_telur">Jenis Telur</label>
                    <select id="jenis_telur" name="jenis_telur" class="form-select">
                        <option value="Omega" {{ $data_pengeluaran->jenis_telur === 'Omega' ? 'selected' : '' }}>Omega</option>
                        <option value="Biasa" {{ $data_pengeluaran->jenis_telur === 'Biasa' ? 'selected' : '' }}>Biasa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="berat_total">Berat Total (gram)</label>
                    <input type="number" id="berat_total" name="berat_total" class="form-control" 
                        value="{{ old('berat_total', $data_pengeluaran->berat_total ?? '') }}" min="0">
                </div>
                <div class="form-group">
                    <label for="harga_kilo">Harga Beli Telur / KG</label>
                    <input type="number" id="harga_kilo" name="harga_kilo" class="form-control" 
                        placeholder="Harga per KG untuk kalkulasi" min="0" step="0.01">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nominal_telur">Nominal (Rp)</label>
                    <input type="number" id="nominal_telur" name="nominal_telur" class="form-control"
                        value="{{ old('nominal_telur', $data_pengeluaran->nominal ?? '') }}" readonly>
                </div>
            </div>
        </div>

        {{-- SECTION 3: LAINNYA --}}
        <div id="formLainnya" class="form-section {{ $data_pengeluaran->jenis_pengeluaran === 'lainnya' ? 'visible' : 'hidden' }}">
            <h5>📌 Detail Pengeluaran Lainnya</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_pengeluaran">Nama Pengeluaran</label>
                    <input type="text" id="nama_pengeluaran" name="nama_pengeluaran" class="form-control"
                        value="{{ old('nama_pengeluaran', $data_pengeluaran->nama_pengeluaran ?? '') }}"
                        placeholder="Contoh: Listrik, Obat, Perbaikan">
                </div>
                <div class="form-group">
                    <label for="nominal_lainnya">Nominal (Rp)</label>
                    <input type="number" id="nominal_lainnya" name="nominal_lainnya" class="form-control"
                        value="{{ old('nominal_lainnya', $data_pengeluaran->jenis_pengeluaran === 'lainnya' ? $data_pengeluaran->nominal : '') }}"
                        placeholder="Masukkan nominal pengeluaran" min="0" step="0.01">
                </div>
            </div>
        </div>

        {{-- SECTION 4: KETERANGAN --}}
        <div class="form-section">
            <h5>📝 Keterangan</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="keterangan">Keterangan (opsional)</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" 
                        rows="3" placeholder="Tambahkan catatan jika diperlukan">{{ old('keterangan', $data_pengeluaran->keterangan ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div style="text-align: center;">
            <button type="submit" class="btn-submit">💾 Update Pengeluaran</button>
        </div>
    </form>

    <canvas class="h-10"></canvas>

    {{-- SCRIPT --}}
    <script>
        const formTelurPecah = document.getElementById('formTelurPecah');
        const formLainnya = document.getElementById('formLainnya');

        const jenisTelur = document.getElementById('jenis_telur');
        const beratTotal = document.getElementById('berat_total');
        const hargaKilo = document.getElementById('harga_kilo');
        const nominalTelur = document.getElementById('nominal_telur');

        const namaPengeluaran = document.getElementById('nama_pengeluaran');
        const nominalLainnya = document.getElementById('nominal_lainnya');

        const jenisPengeluaran = '{{ $data_pengeluaran->jenis_pengeluaran }}';

        // Fungsi untuk menghitung nominal telur
        function hitungNominalTelur() {
            const berat = parseFloat(beratTotal.value) || 0;
            const harga = parseFloat(hargaKilo.value) || 0;
            const nominal = (berat / 1000) * harga;
            nominalTelur.value = Math.round(nominal);
        }

        // Event listener untuk auto-calculate hanya untuk telur pecah
        if (jenisPengeluaran === 'telur pecah') {
            beratTotal.addEventListener('input', hitungNominalTelur);
            hargaKilo.addEventListener('input', hitungNominalTelur);

            // Set required
            jenisTelur.required = true;
            beratTotal.required = true;
            nominalTelur.required = true;
        } else {
            namaPengeluaran.required = true;
            nominalLainnya.required = true;
        }
    </script>
@endsection
