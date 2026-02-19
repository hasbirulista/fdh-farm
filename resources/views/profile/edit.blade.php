@extends('partials.master')

@section('content')
    <style>
        :root {
            --primary: #2d2d2d;
            --secondary: #1a1a1a;
            --warning: #ffc107;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-section h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .header-section p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 8px;
        }

        .form-section-title {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            font-size: 1.1rem;
        }

        .form-group-wrapper {
            margin-bottom: 2px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 1.5px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(45, 45, 45, 0.05);
            background-color: white;
            outline: none;
        }

        .form-control::placeholder {
            color: #999;
        }

        .input-group .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(45, 45, 45, 0.05);
        }

        .input-group .btn-outline-secondary {
            border-color: #ddd;
            color: #666;
            background-color: #fafafa;
            font-weight: 600;
        }

        .input-group .btn-outline-secondary:hover {
            background-color: #f0f0f0;
            color: var(--primary);
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 6px;
            display: block;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
        }

        .form-row-1 {
            display: block;
        }

        .divider-section {
            margin: 30px 0;
            padding: 20px 0;
            border-top: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
        }

        .divider-section h5 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 0;
            font-size: 1rem;
        }

        .alert {
            border: none;
            border-radius: 8px;
            border-left: 4px solid;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }

        .alert-danger ul {
            padding-left: 20px;
        }

        .alert-danger li {
            margin-bottom: 5px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 13px 35px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(45, 45, 45, 0.3);
            color: white;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .submission-hint {
            text-align: center;
            color: #999;
            font-size: 0.85rem;
            margin-top: 10px;
            font-style: italic;
        }

        .field-hint {
            color: #666;
            font-size: 0.85rem;
            margin-top: 2px;
            margin-bottom: 2px;
            font-weight: 400;
        }



        @media (max-width: 768px) {
            .form-card {
                padding: 16px;
                margin-bottom: 6px;
            }

            .header-section {
                padding: 14px;
                margin-bottom: 10px;
            }

            .header-section h3 {
                font-size: 1.4rem;
            }

            .form-section-title {
                font-size: 1rem;
                margin-bottom: 8px;
                padding-bottom: 6px;
            }

            .form-group-wrapper {
                margin-bottom: 2px;
            }

            .form-label {
                font-size: 0.9rem;
                margin-bottom: 3px;
            }

            .field-hint {
                margin-top: 1px;
                margin-bottom: 2px;
                font-size: 0.82rem;
            }

            .form-control {
                padding: 9px 11px;
                font-size: 0.9rem;
            }

            .form-row-2 {
                gap: 6px;
            }
        }

        @media (max-width: 576px) {
            .form-card {
                padding: 14px;
                margin-bottom: 8px;
            }

            .header-section {
                padding: 12px;
                margin-bottom: 10px;
            }

            .header-section h3 {
                font-size: 1.2rem;
            }

            .form-section-title {
                margin-bottom: 8px;
                padding-bottom: 6px;
                font-size: 0.95rem;
            }

            .form-group-wrapper {
                margin-bottom: 1px;
            }

            .form-label {
                margin-bottom: 3px;
                font-size: 0.85rem;
            }

            .field-hint {
                margin-top: 1px;
                font-size: 0.8rem;
            }

        @media (max-width: 576px) {
            .form-card {
                padding: 12px;
                margin-bottom: 4px;
            }

            .header-section {
                padding: 10px;
                margin-bottom: 8px;
            }

            .header-section h3 {
                font-size: 1.2rem;
            }

            .form-section-title {
                margin-bottom: 8px;
                padding-bottom: 6px;
                font-size: 0.95rem;
            }

            .form-group-wrapper {
                margin-bottom: 1px;
            }

            .form-label {
                margin-bottom: 3px;
                font-size: 0.85rem;
            }

            .field-hint {
                margin-top: 1px;
                margin-bottom: 2px;
                font-size: 0.8rem;
            }

            .form-control {
                padding: 8px 10px;
                font-size: 0.85rem;
            }

            .form-row-2 {
                gap: 5px;
            }

            .btn-submit {
                padding: 10px 20px;
                font-size: 0.85rem;
            }

            .submission-hint {
                margin-top: 6px;
                font-size: 0.8rem;
            }

            .alert {
                margin-bottom: 8px;
                font-size: 0.9rem;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="header-section mt-2">
        <h3>👤 Edit Profil</h3>
        <p>Kelola informasi akun dan keamanan Anda</p>
    </div>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✅ Berhasil!</strong>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>⚠️ Terjadi Kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}"
        onsubmit="return confirm('🔒 Yakin ingin menyimpan perubahan profil?')">
        @csrf
        @method('PATCH')

        {{-- INFORMASI DASAR SECTION --}}
        <div class="form-card">
            <h5 class="form-section-title">
                ℹ️ Informasi Dasar
            </h5>

            <div class="form-row-2">
                <div class="form-group-wrapper">
                    <label class="form-label">👤 Username</label>
                    <input type="text" name="username" 
                        class="form-control @error('username') is-invalid @enderror"
                        value="{{ old('username', $user->username) }}" required>
                    <p class="field-hint">Username unik untuk login ke sistem</p>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-wrapper">
                    <label class="form-label">📝 Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control"
                        value="{{ old('nama', $user->nama) }}" required>
                    <p class="field-hint">Nama yang akan tampil di sistem</p>
                </div>
            </div>

            <div class="form-row-1">
                <div class="form-group-wrapper">
                    <label class="form-label">📞 Nomor HP</label>
                    <input type="text" name="no_hp" 
                        class="form-control @error('no_hp') is-invalid @enderror"
                        value="{{ old('no_hp', $user->no_hp) }}" 
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')" 
                        placeholder="Contoh: 081234567890" required>
                    <p class="field-hint">Nomor telepon untuk kontak darurat</p>
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- KEAMANAN SECTION --}}
        <div class="form-card">
            <h5 class="form-section-title">
                🔒 Keamanan
            </h5>

            <div class="form-row-2">
                <div class="form-group-wrapper">
                    <label class="form-label">🔐 Password Baru <span style="color: #999;">(Opsional)</span></label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Kosongkan jika tidak diubah">
                        <button class="btn btn-outline-secondary" type="button" 
                            onclick="togglePassword()" title="Tampilkan/Sembunyikan">
                            👁️
                        </button>
                    </div>
                    <p class="field-hint">Minimal 8 karakter dengan kombinasi huruf dan angka</p>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-wrapper">
                    <label class="form-label">✓ Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Ulangi password baru Anda">
                    <p class="field-hint">Pastikan sama dengan password baru</p>
                </div>
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn-submit" type="submit">
                💾 Simpan Perubahan Profil
            </button>
            <p class="submission-hint">
                Anda akan diminta untuk mengkonfirmasi perubahan
            </p>
        </div>
    </form>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection
