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

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 30px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 5px solid #2d2d2d;
            margin-bottom: 20px;
        }

        .form-section h5 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d2d2d;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            margin-bottom: 8px;
            display: block;
        }

        .form-control, .form-select {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2d2d2d;
            box-shadow: 0 0 0 3px rgba(45, 45, 45, 0.1);
            outline: none;
        }

        .input-group-text {
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-left: none;
            color: #666;
            font-weight: 600;
        }

        .form-control.input-kg {
            border-right: none;
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

        .btn-cancel {
            background: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-cancel:hover {
            background: #e8e8e8;
            color: #333;
            text-decoration: none;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 576px) {
            .form-section {
                padding: 20px 15px;
            }

            .header-section h4 {
                font-size: 1.5rem;
            }

            .button-group {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="py-3">
        {{-- HEADER --}}
        <div class="header-section">
            <h4>✏️ Edit Distribusi Pakan</h4>
        </div>

        {{-- ERROR MESSAGE --}}
        @if ($errors->has('stok'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first('stok') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FORM --}}
        <form action="/dashboard/pakan/distribusi/{{ $data_distribusi->id }}" method="POST" id="form-distribusi">
            @csrf
            @method('PUT')

            {{-- SECTION DATA DISTRIBUSI --}}
            <div class="form-section">
                <h5>📋 Data Distribusi</h5>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="tanggal_distribusi">Tanggal Distribusi</label>
                            <input type="date" required id="tanggal_distribusi" name="tanggal_distribusi" 
                                class="form-control" value="{{ $data_distribusi->tanggal_distribusi }}">
                            @error('tanggal_distribusi')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="kandang_select">Kandang</label>
                            <select id="kandang_select" required name="kandang_id" class="form-select">
                                @foreach ($data_kandang as $kandang)
                                    <option value="{{ $kandang->id }}" {{ $data_distribusi->kandang_id == $kandang->id ? 'selected' : '' }}>
                                        {{ $kandang->nama_kandang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kandang_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION DETAIL PAKAN --}}
            <div class="form-section">
                <h5>🌾 Detail Pakan</h5>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="jenis_pakan">Jenis Pakan</label>
                            <select id="jenis_pakan" required name="stok_pakan_id" class="form-select">
                                @foreach ($data_pakan as $pakan)
                                    <option value="{{ $pakan->id }}" {{ $data_distribusi->stok_pakan_id == $pakan->id ? 'selected' : '' }}>
                                        {{ $pakan->jenis_pakan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('stok_pakan_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="jumlah_berat_kg">Jumlah Berat</label>
                            <div class="input-group">
                                <input type="number" required id="jumlah_berat_kg" name="jumlah_berat_kg" 
                                    class="form-control input-kg" placeholder="0.000" step="0.001" 
                                    value="{{ $data_distribusi->jumlah_berat / 1000 }}">
                                <span class="input-group-text">Kg</span>
                            </div>
                            @error('jumlah_berat')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- HIDDEN INPUT UNTUK GRAM --}}
            <input type="hidden" id="jumlah_berat" name="jumlah_berat">

            {{-- BUTTONS --}}
            <div class="button-group">
                <button class="btn-submit" type="submit">💾 Simpan Perubahan</button>
                <a href="/dashboard/pakan/distribusi" class="btn-cancel">❌ Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('form-distribusi').addEventListener('submit', function(e) {
            const kgInput = document.getElementById('jumlah_berat_kg').value;
            const gramInput = document.getElementById('jumlah_berat');
            
            // Konversi kg ke gram
            if (kgInput) {
                gramInput.value = Math.round(parseFloat(kgInput) * 1000);
            }
        });
    </script>
@endsection
