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
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .header-section h4 {
            margin: 0;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: -0.5px;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 30px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 5px solid #2d2d2d;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            color: white;
        }
    </style>

    <div class="py-3">
        {{-- HEADER --}}
        <div class="header-section">
            <h4>➕ Tambah Pelanggan</h4>
        </div>

        {{-- FORM --}}
        <div class="form-section">
            <form action="/dashboard/egg-grow/pelanggan" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Nama Pelanggan</label>
                            <input type="text" required name="nama_pelanggan" placeholder="Nama Pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}">
                            @error('nama_pelanggan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">No HP</label>
                            <input type="number" required name="no_hp" placeholder="No Hp" class="form-control" value="{{ old('no_hp') }}">
                            @error('no_hp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-bold">Alamat</label>
                    <textarea name="alamat" required class="form-control" placeholder="Alamat" style="height: 80px;">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Repeat Order</label>
                            <select id="repeat_order_aktif" name="repeat_order_aktif" required class="form-select">
                                <option value="" disabled selected>Pilih</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Repeat Order (Hari)</label>
                            <input type="number" id="repeat_order_hari" name="repeat_order_hari" class="form-control" placeholder="" disabled>
                        </div>
                    </div>
                </div>

                <button class="btn-submit" type="submit">💾 Simpan Pelanggan</button>
            </form>
        </div>
    </div>

    <script>
        const repeatSelect = document.getElementById('repeat_order_aktif');
        const repeatHari = document.getElementById('repeat_order_hari');

        repeatSelect.addEventListener('change', function() {
            if (this.value === '1') {
                repeatHari.disabled = false;
                repeatHari.required = true;
            } else {
                repeatHari.disabled = true;
                repeatHari.required = false;
                repeatHari.value = '';
            }
        });
    </script>
@endsection
