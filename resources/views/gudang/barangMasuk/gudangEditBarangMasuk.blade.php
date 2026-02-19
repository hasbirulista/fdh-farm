@extends('partials.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Edit Barang Masuk</h2>
    </div>
    <form action="/dashboard/gudang/barang-masuk/{{ $data_produksi->id }}" method="POST">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col">
                <label for="tanggal_produksi" class="form-label">Tanggal</label>
                <input type="date" required name="tanggal_produksi" class="form-control" placeholder="Tanggal"
                    aria-label="Tanggal" value="{{ $data_produksi->tanggal_produksi }}">
            </div>
            <div class="col">
                <label for="kandang" class="form-label">Kandang</label>
                <select id="kandang_select" required name="kandang_id" class="form-select">
                    @foreach ($data_kandang as $kandang)
                        <option value="{{ $kandang->nama_kandang }}" data-populasi="{{ $kandang->populasi_ayam }}"
                            {{ $data_produksi->nama_kandang == $kandang->nama_kandang ? 'selected' : '' }}>
                            {{ $kandang->nama_kandang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div hidden class="row mt-3">
            <div class="col">
                <label for="Usia" class="form-label">Usia (Minggu)</label>
                <input type="number" required name="usia" class="form-control" placeholder="Usia (Minggu)"
                    aria-label="Usia" value="{{ $data_produksi->usia }}">
            </div>
            <div class="col">
                <label for="Mati" class="form-label">Mati</label>
                <input type="number" required name="mati" class="form-control" placeholder="Mati" aria-label="Mati"
                    value="{{ $data_produksi->mati }}">
            </div>
            <div class="col">
                <label for="Apkir" class="form-label">Apkir</label>
                <input type="number" required name="apkir" class="form-control" placeholder="Apkir" aria-label="Apkir"
                    value="{{ $data_produksi->apkir }}">
            </div>
        </div>
        <div hidden class="row mt-3">
            <div class="col">
                <label for="PopulasiAyam" class="form-label">Populasi Ayam</label>
                <input type="number" required id="populasi_ayam" name="populasi_ayam" class="form-control"
                    placeholder="Populasi Ayam" aria-label="PopulasiAyam" value="{{ $data_produksi->populasi_ayam }}">
            </div>

        </div>
        <div class="row mt-3">
            <div class="col">
                <label for="JumlahGram" class="form-label">Jumlah Gram</label>
                <input type="number" required name="jumlah_gram" class="form-control" placeholder="Jumlah Gram"
                    aria-label="Jumlah Gram" value="{{ $data_produksi->jumlah_gram }}">
            </div>
            <div class="col">
                <label for="JumlahButir" class="form-label">Jumlah Butir</label>
                <input type="number" required id="jumlah_butir" name="jumlah_butir" class="form-control"
                    placeholder="Jumlah Butir" aria-label="Jumlah Butir" value="{{ $data_produksi->jumlah_butir }}">
            </div>
            <div class="col">
                <label for="jenis_telur" class="form-label">Jenis Telur</label>
                <select id="jenis_telur" required name="jenis_telur" class="form-select">
                    <option value="{{ $data_produksi->jenis_telur }}" selected>
                        {{ $data_produksi->jenis_telur }}
                    </option>

                    @if ($data_produksi->jenis_telur == 'Omega')
                        <option value="Biasa">Biasa</option>
                    @else
                        <option value="Omega">Omega</option>
                    @endif
                </select>
            </div>
            <div hidden class="col">
                <label for="JumlahPecah" class="form-label">Jumlah Pecah</label>
                <input type="number" required name="jumlah_pecah" class="form-control" placeholder="Jumlah Pecah"
                    aria-label="Jumlah Pecah" value="{{ $data_produksi->jumlah_pecah }}">
            </div>
            <div hidden class="col">
                <label for="Pakan Grower" class="form-label">Grower (gr/ayam)</label>
                <input type="number" id="grower_per_ayam" required name="grower_per_ayam" class="form-control"
                    placeholder="Pakan Grower" aria-label="Pakan Grower" value="{{ $data_produksi->grower_per_ayam }}">
            </div>
            <div hidden class="col">
                <label for="Pakan Layer" class="form-label">Layer (gr/ayam)</label>
                <input type="number" id="layer_per_ayam" required name="layer_per_ayam" class="form-control"
                    placeholder="Pakan Layer" aria-label="Pakan Layer" value="{{ $data_produksi->layer_per_ayam }}">
            </div>
        </div>
        <div hidden class="row mt-3">
            <div class="col">
                <label for="PakanA" class="form-label">Pakan Grower</label>
                <input type="number" readonly id="pakan_A" required name="pakan_A" class="form-control"
                    placeholder="Pakan (A)" aria-label="PakanA" value="{{ $data_produksi->pakan_A }}">
            </div>
            <div class="col">
                <label for="PakanB" class="form-label">Pakan Layer</label>
                <input type="number" readonly id="pakan_B" required name="pakan_B" class="form-control"
                    placeholder="Pakan (B)" aria-label="PakanB" value="{{ $data_produksi->pakan_B }}">
            </div>
            <div class="col">
                <label for="PersentaseProduksi" class="form-label">Persentase</label>
                <input type="text" id="persentase_view" class="form-control" readonly aria-label="PersentaseProduksi"
                    value="{{ $data_produksi->persentase_produksi }}%">
                <input type="hidden" id="persentase_produksi" name="persentase_produksi">
            </div>
        </div>
        <div hidden class="row mt-3">
            <div class="form-floating">
                <textarea name="kegiatan" value="{{ old('kegiatan') }}" required class="form-control" placeholder="Kegiatan"
                    id="floatingTextarea2" style="height: 70px">{{ $data_produksi->kegiatan }}</textarea>
                <label for="floatingTextarea2">Kegiatan</label>
            </div>
        </div>
        <div hidden class="row mt-3">
            <div class="form-floating">
                <textarea name="keterangan" value="{{ old('keterangan') }}" required class="form-control" placeholder="Keterangan"
                    id="floatingTextarea2" style="height: 70px"> {{ $data_produksi->keterangan }}</textarea>
                <label for="floatingTextarea2">Keterangan</label>
            </div>
        </div>
        <div class="d-grid gap-2 col-6 mx-auto mt-4 p-1">
            <button class="btn btn-dark" type="submit">Update</button>
        </div>
    </form>
    <canvas class="h-10"></canvas>
    <script>
        function hitungProduksi() {
            const populasi = parseFloat(document.getElementById('populasi_ayam').value) || 0;
            const butir = parseFloat(document.getElementById('jumlah_butir').value) || 0;

            const view = document.getElementById('persentase_view');
            const hidden = document.getElementById('persentase_produksi');

            if (populasi > 0 && butir >= 0) {
                const hasil = (butir / populasi) * 100;

                view.value = hasil.toFixed(2) + ' %';
                hidden.value = hasil.toFixed(2);
            } else {
                view.value = '';
                hidden.value = '';
            }
        }

        document.getElementById('populasi_ayam')
            .addEventListener('input', hitungProduksi);

        document.getElementById('jumlah_butir')
            .addEventListener('input', hitungProduksi);

        const kandangSelect = document.getElementById('kandang_select');
        const populasiInput = document.getElementById('populasi_ayam');

        kandangSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const populasi = selectedOption.getAttribute('data-populasi');

            if (populasi) {
                populasiInput.value = populasi;
                hitungProduksi(); // langsung update persentase
            } else {
                populasiInput.value = '';
            }
        });
    </script>
@endsection
