@extends('partials.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Edit Pakan Masuk</h2>
    </div>
    @if ($errors->has('stok'))
        <div class="alert alert-danger">
            {{ $errors->first('stok') }}
        </div>
    @endif
    {{-- NOTIF ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <form action="/dashboard/pakan/pakan-masuk/{{ $data_pakan_masuk->id }}" method="POST">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col">
                <label for="tanggal_pakan_masuk" class="form-label">Tanggal</label>
                <input type="date" required name="tanggal_pakan_masuk" class="form-control" placeholder="Tanggal"
                    aria-label="Tanggal" value="{{ $data_pakan_masuk->tanggal_pakan_masuk }}">
            </div>
            <div class="col">
                <label for="Jenis Pakan" class="form-label">Jenis Pakan</label>
                <select id="jenis_pakan" required name="jenis_pakan" class="form-select">
                    <option value="{{ $data_pakan_masuk->jenis_pakan }}" selected>
                        {{ $data_pakan_masuk->jenis_pakan }}
                    </option>

                    @if ($data_pakan_masuk->jenis_pakan == 'Grower')
                        <option value="Layer">Layer</option>
                    @else
                        <option value="Grower">Grower</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <label for="Berat Total" class="form-label">Berat Total (gr)</label>
                <input type="number" id="berat_total" required name="berat_total" class="form-control" placeholder="Berat Total (gram)"
                    aria-label="Berat Total" value="{{ $data_pakan_masuk->berat_total }}">
            </div>
            <div class="col">
                <label for="HargaKilo" class="form-label">harga / Kg</label>
                <input type="number" required id="harga_kilo" name="harga_kilo" class="form-control"
                    placeholder="Harga / Kg" aria-label="Harga / Kg" value="{{ $data_pakan_masuk->harga_kilo }}">
            </div>
            <div class="col">
                <label for="TotalHarga" class="form-label">Total Harga</label>
                <input type="number" readonly required id="total_harga" name="total_harga" class="form-control" placeholder="Total Harga"
                    aria-label="Total Harga" value="{{ $data_pakan_masuk->total_harga }}">
            </div>
        </div>
        <div class="d-grid gap-2 col-6 mx-auto mt-4 p-1">
            <button class="btn btn-dark" type="submit">Update</button>
        </div>
    </form>
    <canvas class="h-10"></canvas>
    <script>
        const jumlahInput = document.getElementById('berat_total');
        const hargaInput = document.getElementById('harga_kilo');
        const totalInput = document.getElementById('total_harga');

        function hitungTotal() {
            const gram = parseFloat(jumlahInput.value) || 0;
            const hargaKg = parseFloat(hargaInput.value) || 0;

            const total = (gram / 1000) * hargaKg;
            totalInput.value = Math.round(total); // bulatkan, bisa dihapus jika tidak perlu
        }

        jumlahInput.addEventListener('input', hitungTotal);
        hargaInput.addEventListener('input', hitungTotal);
    </script>
@endsection
