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
        .form-group select {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2d2d2d;
            box-shadow: 0 0 0 3px rgba(45, 45, 45, 0.1);
            outline: none;
        }

        .form-group input[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
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
        <h2>✏️ Edit Barang Keluar</h2>
    </div>

    {{-- ALERT ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="/dashboard/gudang/barang-keluar/{{ $data_barang_keluar->id }}" method="POST">
        @method('PUT')
        @csrf

        {{-- SECTION 1: INFORMASI DASAR --}}
        <div class="form-section">
            <h5>📋 Informasi Dasar</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_barang_keluar">Tanggal</label>
                    <input type="date" required name="tanggal_barang_keluar" id="tanggal_barang_keluar"
                        value="{{ $data_barang_keluar->tanggal_barang_keluar }}">
                </div>
                <div class="form-group">
                    <label for="nama_konsumen">Nama Konsumen</label>
                    <input type="text" required name="nama_konsumen" id="nama_konsumen"
                        placeholder="Nama Konsumen" value="{{ $data_barang_keluar->nama_konsumen }}">
                </div>
            </div>
        </div>

        {{-- SECTION 2: JENIS BARANG (INFO ONLY) --}}
        <div class="form-section">
            <h5>📦 Jenis Barang</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_barang_display">Jenis Barang</label>
                    <input type="text" id="jenis_barang_display" readonly
                        value="{{ $data_barang_keluar->jenis_barang === 'telur' ? '🥚 Telur' : '🐓 Ayam Apkir' }}">
                    <input type="hidden" id="jenis_barang" name="jenis_barang" 
                        value="{{ $data_barang_keluar->jenis_barang }}">
                </div>
            </div>
        </div>

        {{-- SECTION 3: TELUR --}}
        <div id="telur-section" class="form-section {{ $data_barang_keluar->jenis_barang === 'telur' ? 'visible' : 'hidden' }}">
            <h5>🥚 Detail Telur</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_telur">Jenis Telur</label>
                    <select name="jenis_telur" id="jenis_telur">
                        <option value="" disabled>Pilih Jenis Telur</option>
                        <option value="Omega" {{ $data_barang_keluar->jenis_telur == 'Omega' ? 'selected' : '' }}>Omega</option>
                        <option value="Biasa" {{ $data_barang_keluar->jenis_telur == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah_barang_keluar">Jumlah Telur (gram)</label>
                    <input type="number" name="jumlah_barang_keluar" id="jumlah_barang_keluar"
                        placeholder="Jumlah dalam gram" min="0" step="0.01"
                        value="{{ $data_barang_keluar->jumlah_barang_keluar ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="harga_kilo">Harga per Kg</label>
                    <input type="number" name="harga_kilo" id="harga_kilo"
                        placeholder="Harga per Kg" min="0" step="0.01"
                        value="{{ $data_barang_keluar->harga_kilo ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="total_harga">Total Harga</label>
                    <input type="number" readonly id="total_harga" name="total_harga"
                        placeholder="Otomatis terhitung" value="{{ $data_barang_keluar->total_harga ?? '' }}">
                </div>
            </div>
        </div>

        {{-- SECTION 4: AYAM APKIR --}}
        <div id="ayam_apkir-section" class="form-section {{ $data_barang_keluar->jenis_barang === 'ayam_apkir' ? 'visible' : 'hidden' }}">
            <h5>🐓 Detail Ayam Apkir</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="jumlah_ayam">Jumlah Ayam (ekor)</label>
                    <input type="number" name="jumlah_ayam" id="jumlah_ayam"
                        placeholder="Jumlah dalam ekor" min="0"
                        value="{{ $data_barang_keluar->jumlah_ayam ?? '' }}">
                </div>
                <div class="form-group">
                    <label for="total_harga_ayam">Total Harga</label>
                    <input type="number" name="total_harga_ayam" id="total_harga_ayam"
                        placeholder="Masukkan total harga" min="0" step="0.01"
                        value="{{ $data_barang_keluar->total_harga ?? '' }}">
                </div>
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div style="text-align: center;">
            <button type="submit" class="btn-submit">Update Barang Keluar</button>
        </div>
    </form>

    <canvas class="h-10"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisBrg = '{{ $data_barang_keluar->jenis_barang }}';
            const telurSection = document.getElementById('telur-section');
            const ayamApkirSection = document.getElementById('ayam_apkir-section');

            // Form elements
            const jenisTelurInput = document.getElementById('jenis_telur');
            const jumlahTelurInput = document.getElementById('jumlah_barang_keluar');
            const hargaKiloInput = document.getElementById('harga_kilo');
            const totalTelurInput = document.getElementById('total_harga');
            const jumlahAyamInput = document.getElementById('jumlah_ayam');
            const totalAyamInput = document.getElementById('total_harga_ayam');

            // Set required berdasarkan jenis barang
            function setRequiredFields() {
                if (jenisBrg === 'telur') {
                    jenisTelurInput.required = true;
                    jumlahTelurInput.required = true;
                    hargaKiloInput.required = true;
                    jumlahAyamInput.required = false;
                    totalAyamInput.required = false;
                } else {
                    jumlahAyamInput.required = true;
                    totalAyamInput.required = true;
                    jenisTelurInput.required = false;
                    jumlahTelurInput.required = false;
                    hargaKiloInput.required = false;
                }
            }

            setRequiredFields();

            // TELUR CALCULATIONS
            function hitungTotalTelur() {
                const gram = parseFloat(jumlahTelurInput.value) || 0;
                const hargaKg = parseFloat(hargaKiloInput.value) || 0;
                const total = (gram / 1000) * hargaKg;
                totalTelurInput.value = Math.round(total);
            }

            if (jenisBrg === 'telur') {
                jumlahTelurInput.addEventListener('input', hitungTotalTelur);
                hargaKiloInput.addEventListener('input', hitungTotalTelur);
            }
        });
    </script>
@endsection
