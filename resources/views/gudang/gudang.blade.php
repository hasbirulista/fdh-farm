@extends('partials.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gudang Telur</h1>
    </div>

    <div class="row">
        <div class="col card m-1 ">
            <div class="card-body">
                <center><span data-feather="truck" class="mb-1" style="width:30px;height:30px;"></span>
                    <h6 class="card-text text-center mt-1">{{ number_format($stok_telur_omega_gudang, 2, ',', '.') }} Kg</h6>
                    <p class="card-text">Stok Telur Omega</p>
                </center>
            </div>
        </div>
        <div class="col card m-1 ">
            <div class="card-body">
                <center><span data-feather="truck" class="mb-1" style="width:30px;height:30px;"></span>
                    <h6 class="card-text text-center mt-1">{{ number_format($stok_telur_biasa_gudang, 2, ',', '.') }} Kg</h6>
                    <p class="card-text">Stok Telur Biasa</p>
                </center>
            </div>
        </div>        
    </div>
    <div class="row">
        <a href="/dashboard/gudang/barang-masuk" class="col card m-1 text-decoration-none text-dark">
            <div class="card-body">
                <center><span data-feather="download" class="mb-1" style="width:30px;height:30px;"></span>
                    <h6 class="card-text text-center mt-1">Barang Masuk</h6>
                </center>
            </div>
        </a>
        <a href="/dashboard/gudang/barang-keluar" class="text-decoration-none text-dark col card m-1">
            <div class="card-body">
                <center><span data-feather="truck" class="mb-1" style="width:30px;height:30px;"></span>
                    <h6 class="card-text text-center mt-1">Barang Keluar</h6>
                </center>
            </div>
        </a>
    </div>
@endsection
