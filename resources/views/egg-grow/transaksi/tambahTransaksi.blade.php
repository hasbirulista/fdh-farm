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
        <h2>➕ Tambah Transaksi</h2>
    </div>

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> Terjadi kesalahan saat menyimpan data
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="/dashboard/egg-grow/transaksi" method="POST">
        @csrf

        {{-- SECTION 1: PELANGGAN & TANGGAL --}}
        <div class="form-section">
            <h5>📋 Data Transaksi</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="pelanggan_id">Pelanggan</label>
                    <select id="pelanggan_id" name="pelanggan_id" required>
                        <option value="" disabled selected>Pilih Pelanggan</option>
                        @foreach ($pelanggans as $pelanggan)
                            <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                {{ $pelanggan->nama_pelanggan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal_transaksi">Tanggal Transaksi</label>
                    <input type="date" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi') }}" required>
                </div>
            </div>
        </div>

        {{-- SECTION 2: TELUR & BERAT --}}
        <div class="form-section">
            <h5>🥚 Detail Telur</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="jenis_telur">Jenis Telur</label>
                    <select id="jenis_telur" name="jenis_telur" required>
                        <option value="" disabled selected>Pilih Jenis Telur</option>
                        <option value="Omega" {{ old('jenis_telur') == 'Omega' ? 'selected' : '' }}>Omega</option>
                        <option value="Biasa" {{ old('jenis_telur') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="total_berat">Total Berat (gr)</label>
                    <input type="number" id="total_berat" name="total_berat" placeholder="0" value="{{ old('total_berat') }}" required>
                </div>
            </div>
        </div>

        {{-- SECTION 3: HARGA --}}
        <div class="form-section">
            <h5>💰 Harga</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="harga_beli_kilo">Harga Beli / Kg</label>
                    <input type="number" id="harga_beli_kilo" name="harga_beli_kilo" placeholder="0" value="{{ old('harga_beli_kilo') }}" required>
                </div>
                <div class="form-group">
                    <label for="harga_jual_kilo">Harga Jual / Kg</label>
                    <input type="number" id="harga_jual_kilo" name="harga_jual_kilo" placeholder="0" value="{{ old('harga_jual_kilo') }}" required>
                </div>
                <div class="form-group">
                    <label for="total_harga">Total Harga (Rp)</label>
                    <input type="number" id="total_harga" name="total_harga" placeholder="0" value="{{ old('total_harga') }}" readonly>
                </div>
            </div>
        </div>

        {{-- SECTION 4: PEMBAYARAN --}}
        <div class="form-section">
            <h5>💳 Pembayaran</h5>
            <div class="form-row">
                <div class="form-group">
                    <label for="pembayaran">Metode Pembayaran</label>
                    <select id="pembayaran" name="pembayaran" required>
                        <option value="" disabled selected>Pilih Metode Pembayaran</option>
                        <option value="Tunai" {{ old('pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Transfer" {{ old('pembayaran') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit">💾 Simpan Transaksi</button>
    </form>

    <script>
        const totalBeratInput = document.getElementById('total_berat');
        const hargaJualInput = document.getElementById('harga_jual_kilo');
        const totalHargaInput = document.getElementById('total_harga');

        function hitungTotalHarga() {
            const gram = parseFloat(totalBeratInput.value) || 0;
            const hargaKg = parseFloat(hargaJualInput.value) || 0;

            const total = (gram / 1000) * hargaKg;
            totalHargaInput.value = Math.round(total);
        }

        totalBeratInput.addEventListener('input', hitungTotalHarga);
        hargaJualInput.addEventListener('input', hitungTotalHarga);    </script>
@endsection