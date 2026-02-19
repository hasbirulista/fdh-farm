@extends('partials.master')

@section('content')
    <style>
        :root {
            --primary: #2d2d2d;
            --secondary: #1a1a1a;
            --light-bg: #f5f5f5;
            --border-light: #e0e0e0;
            --success: #28a745;
            --danger: #dc3545;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .header-section h2 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            border-left: 5px solid var(--primary);
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-bg);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .form-section-title span {
            font-size: 1.8rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

        .form-control, .form-select {
            border: 2px solid var(--border-light);
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(45, 45, 45, 0.1);
        }

        .form-control:read-only {
            background-color: var(--light-bg) !important;
            cursor: not-allowed;
        }

        .form-text {
            color: #666;
            margin-top: 6px;
            font-size: 0.85rem;
        }

        textarea {
            resize: vertical;
            font-family: inherit;
            min-height: 100px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 45, 45, 0.3);
            color: white;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 25px;
        }

        .d-none {
            display: none;
        }
    </style>

    <div class="py-3">
        {{-- HEADER --}}
        <div class="header-section">
            <h2>💰 Tambah Pengeluaran</h2>
        </div>

        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('gudang.storePengeluaran') }}" method="POST">
            @csrf

            {{-- INFORMASI DASAR --}}
            <div class="form-section">
                <div class="form-section-title">
                    <span>📋</span>
                    <h5 style="margin: 0;">Informasi Dasar</h5>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jenis Pengeluaran</label>
                        <select id="jenis_pengeluaran" name="jenis_pengeluaran" class="form-select" required>
                            <option value="" selected disabled>Pilih Jenis Pengeluaran</option>
                            <option value="pakan">Pakan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                </div>
            </div>

            {{-- FORM PAKAN --}}
            <div id="formPakan" class="d-none">
                <div class="form-section">
                    <div class="form-section-title">
                        <span>🌾</span>
                        <h5 style="margin: 0;">Detail Pakan</h5>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Jenis Pakan</label>
                            <select id="jenis_pakan" name="jenis_pakan" class="form-select">
                                <option value="" selected disabled>Pilih Jenis Pakan</option>
                                <option value="Grower">Grower</option>
                                <option value="Layer">Layer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Sak/Karung</label>
                            <input type="number" id="jumlah_sak" class="form-control" placeholder="Contoh: 10" min="0">
                            <small class="form-text">1 sak = 50kg</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Berat Total (kg) - Otomatis</label>
                            <input type="number" id="berat_total" name="berat_total" class="form-control" placeholder="Akan terisi otomatis" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga / Kg</label>
                            <input type="number" id="harga_kilo" name="harga_kilo" class="form-control" placeholder="Contoh: 5000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Harga - Otomatis</label>
                            <input type="number" id="total_harga" name="total_harga" class="form-control" placeholder="Akan terisi otomatis" readonly>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM LAINNYA --}}
            <div id="formLainnya" class="d-none">
                <div class="form-section">
                    <div class="form-section-title">
                        <span>📌</span>
                        <h5 style="margin: 0;">Detail Pengeluaran Lainnya</h5>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Pengeluaran</label>
                            <input type="text" id="nama_pengeluaran" name="nama_pengeluaran" class="form-control"
                                placeholder="Contoh: Listrik, Obat, Perbaikan">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nominal</label>
                            <input type="number" id="nominal_lainnya" name="nominal_lainnya" class="form-control"
                                placeholder="Nominal pengeluaran">
                        </div>
                    </div>
                </div>
            </div>

            {{-- KETERANGAN --}}
            <div class="form-section">
                <div class="form-section-title">
                    <span>📝</span>
                    <h5 style="margin: 0;">Keterangan Tambahan</h5>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" placeholder="Masukkan keterangan atau catatan tambahan..."></textarea>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" class="btn-submit">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>

    {{-- SCRIPT --}}
    <script>
        const jenisPengeluaran = document.getElementById('jenis_pengeluaran');
        const formPakan = document.getElementById('formPakan');
        const formLainnya = document.getElementById('formLainnya');

        const jenisPakan = document.getElementById('jenis_pakan');
        const jumlahSak = document.getElementById('jumlah_sak');
        const berat = document.getElementById('berat_total');
        const harga = document.getElementById('harga_kilo');
        const total = document.getElementById('total_harga');

        const namaPengeluaran = document.getElementById('nama_pengeluaran');
        const nominalLainnya = document.getElementById('nominal_lainnya');

        function toggleForm() {
            if (jenisPengeluaran.value === 'pakan') {
                formPakan.classList.remove('d-none');
                formLainnya.classList.add('d-none');

                jenisPakan.required = true;
                berat.required = true;
                harga.required = true;

                namaPengeluaran.required = false;
                nominalLainnya.required = false;
                namaPengeluaran.value = '';
                nominalLainnya.value = '';
            } else {
                formPakan.classList.add('d-none');
                formLainnya.classList.remove('d-none');

                jenisPakan.required = false;
                berat.required = false;
                harga.required = false;

                jenisPakan.value = '';
                jumlahSak.value = '';
                berat.value = '';
                harga.value = '';
                total.value = '';

                namaPengeluaran.required = true;
                nominalLainnya.required = true;
            }
        }

        function hitungBeratDariSak() {
            const sak = parseFloat(jumlahSak.value) || 0;
            const beratTotal = sak * 50; // 1 sak = 50kg
            berat.value = beratTotal > 0 ? beratTotal : '';
            hitungTotal();
        }

        function hitungTotal() {
            const kilo = parseFloat(berat.value) || 0;
            const hargaKg = parseFloat(harga.value) || 0;
            total.value = Math.round(kilo * hargaKg);
        }

        jenisPengeluaran.addEventListener('change', toggleForm);
        jumlahSak.addEventListener('input', hitungBeratDariSak);
        berat.addEventListener('input', hitungTotal);
        harga.addEventListener('input', hitungTotal);
    </script>
@endsection
