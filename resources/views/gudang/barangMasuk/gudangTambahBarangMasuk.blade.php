@extends('partials.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Tambah Barang Masuk</h2>
    </div>
    <form action="/dashboard/gudang/barang-masuk" method="POST">
        @csrf
        <div class="row">
            <div class="col">
                <label for="tanggal_barang_masuk" class="form-label">Tanggal</label>
                <input type="date" required name="tanggal_barang_masuk" class="form-control" placeholder="Tanggal"
                    aria-label="Tanggal" value="{{ old('tanggal_barang_masuk') }}">
            </div>
            <div class="col">
                <label for="kandang" class="form-label">Kandang</label>
                <select id="kandang_select" required name="kandang_id" class="form-select">
                    <option value="" selected disabled>Pilih Kandang</option>
                    @foreach ($data_kandang as $kandang)
                        <option value="{{ $kandang->nama_kandang }}" data-populasi="{{ $kandang->populasi_ayam }}">
                            {{ $kandang->nama_kandang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <label for="JumlahButir" class="form-label">Jumlah Butir</label>
                <input type="number" required id="jumlah_butir" name="jumlah_butir" class="form-control"
                    placeholder="JumlahButir" aria-label="Jumlah Butir" value="{{ old('jumlah_butir') }}">
            </div>
            <div class="col">
                <label for="JumlahPecah" class="form-label">Jumlah Pecah</label>
                <input type="number" required name="jumlah_pecah" class="form-control" placeholder="JumlahPecah"
                    aria-label="Jumlah Pecah" value="{{ old('jumlah_pecah') }}">
            </div>
            <div class="col">
                <label for="JumlahGram" class="form-label">Jumlah Gram</label>
                <input type="number" required name="jumlah_gram" class="form-control" placeholder="JumlahGram"
                    aria-label="Jumlah Gram" value="{{ old('jumlah_gram') }}">
            </div>
        </div>
        <div class="d-grid gap-2 col-6 mx-auto mt-4 p-1">
            <button class="btn btn-dark" type="submit">Simpan</button>
        </div>
    </form>
    <canvas class="h-10"></canvas>
@endsection
