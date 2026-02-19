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
            padding: 30px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border-left: 5px solid #2d2d2d;
            margin-bottom: 25px;
        }

        .form-section-title {
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 20px;
            font-size: 1.1rem;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 1.5px solid #ddd;
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
            border: 1.5px solid #ddd;
            border-left: none;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .input-group-text:hover {
            background: #e8e8e8;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            color: #dc3545;
            margin-top: 5px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            color: white;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        @media (max-width: 576px) {
            .form-section {
                padding: 20px;
            }

            .header-section h4 {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="mt-2">
        {{-- HEADER --}}
        <div class="header-section">
            <h4>➕ Tambah User</h4>
        </div>

        {{-- ALERT ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>🚨 Terjadi kesalahan!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST"
            onsubmit="return confirm('Yakin ingin menambahkan user ini?')">
            @csrf

            {{-- SECTION: USER INFORMATION --}}
            <div class="form-section">
                <h5 class="form-section-title">👤 Informasi User</h5>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" required placeholder="Username"
                                class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" required placeholder="Nama Lengkap" class="form-control"
                                value="{{ old('nama') }}">
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" required placeholder="No HP"
                                class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select name="role" id="roleSelect" required class="form-select">
                                <option value="">-- Pilih Role --</option>
                                <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                                <option value="anak_kandang" {{ old('role') == 'anak_kandang' ? 'selected' : '' }}>Anak Kandang</option>
                                <option value="kepala_kandang" {{ old('role') == 'kepala_kandang' ? 'selected' : '' }}>Kepala Kandang</option>
                                <option value="kepala_gudang" {{ old('role') == 'kepala_gudang' ? 'selected' : '' }}>Kepala Gudang</option>
                                <option value="admin_toko" {{ old('role') == 'admin_toko' ? 'selected' : '' }}>Admin Toko</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Kandang <span class="text-muted">(opsional, khusus Anak Kandang)</span></label>
                            <select name="kandang_id" id="kandangSelect" class="form-select" disabled>
                                <option value="">-- Pilih Kandang --</option>
                                @foreach ($kandangs as $kandang)
                                    <option value="{{ $kandang->id }}" {{ old('kandang_id') == $kandang->id ? 'selected' : '' }}>
                                        {{ $kandang->nama_kandang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION: PASSWORD --}}
            <div class="form-section">
                <h5 class="form-section-title">🔐 Password</h5>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" required
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password">
                                <button type="button" class="input-group-text"
                                    onclick="togglePassword('password')">👁️</button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="form-control" placeholder="Ulangi Password">
                                <button type="button" class="input-group-text"
                                    onclick="togglePassword('password_confirmation')">👁️</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTON --}}
            <div class="row g-2">
                <div class="col-12 col-md-6 offset-md-3">
                    <button type="submit" class="btn-submit">💾 Simpan User Baru</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        const roleSelect = document.getElementById('roleSelect');
        const kandangSelect = document.getElementById('kandangSelect');

        function updateKandangSelect() {
            if (roleSelect.value === 'anak_kandang') {
                kandangSelect.disabled = false;
                kandangSelect.required = true;
            } else {
                kandangSelect.disabled = true;
                kandangSelect.required = false;
                kandangSelect.value = '';
            }
        }

        updateKandangSelect();
        roleSelect.addEventListener('change', updateKandangSelect);
    </script>
@endsection
