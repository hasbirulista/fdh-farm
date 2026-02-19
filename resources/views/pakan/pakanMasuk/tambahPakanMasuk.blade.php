@extends('partials.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Tambah Pakan</h2>
    </div>
    {{-- NOTIF ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <form action="/dashboard/pakan/pakan-masuk" method="POST">
        @csrf
        <div class="row">
            <div class="col">
                <label for="tanggal_pakan_masuk" class="form-label">Tanggal</label>
                <input type="date" required name="tanggal_pakan_masuk" class="form-control" placeholder="Tanggal"
                    aria-label="Tanggal" value="{{ old('tanggal_pakan_masuk') }}">
            </div>
            <div class="col">
                <label for="Jenis Pakan" class="form-label">Jenis Pakan</label>
                <select id="jenis_pakan" required name="jenis_pakan" class="form-select">
                    <option value="" selected disabled>Pilih</option>
                    <option value="Grower">Pakan Grower</option>
                    <option value="Layer">Pakan Layer</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <label for="Berat Total" class="form-label">Berat Total (gr)</label>
                <input type="number" id="berat_total" required name="berat_total" class="form-control"
                    placeholder="Berat Total (gr)" aria-label="Berat Total" value="{{ old('berat_total') }}">
            </div>
            <div class="col">
                <label for="Harga / Kg" class="form-label">harga / Kg</label>
                <input type="number" required id="harga_kilo" name="harga_kilo" class="form-control"
                    placeholder="Harga / Kg" aria-label="Harga / Kg" value="{{ old('harga_kilo') }}">
            </div>
            <div class="col">
                <label for="Total Harga" class="form-label">Total Harga</label>
                <input type="number" readonly required id="total_harga" name="total_harga" class="form-control"
                    placeholder="Total Harga" aria-label="Total Harga" value="{{ old('total_harga') }}">
            </div>
        </div>
        <div class="d-grid gap-2 col-6 mx-auto mt-4 p-1">
            <button class="btn btn-dark" type="submit">Simpan</button>
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
