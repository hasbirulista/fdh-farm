@extends('partials.master')

@section('content')
    <div class="d-flex  flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2 border-bottom">
        <a href="/dashboard/pakan" class="text-decoration-none text-dark h2">Stok Pakan</a>
        <a href="/dashboard/kandang" class="btn btn-secondary mb-2 ms-2 p-2"> Kandang</a>
    </div>

    <div class="row g-3">
        @foreach ($kandangs as $kandang)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="/dashboard/pakan/distribusi"
                    class="text-decoration-none text-dark">
                    <div class="card kandang-card h-100">
                        <div class="card-body text-center p-3">

                            <div class="icon-wrapper mb-2">
                                <span data-feather="home"></span>
                            </div>

                            <h6 class="fw-semibold mb-1">
                                {{ $kandang->nama_kandang }}
                            </h6>

                            <hr class="my-2">

                            @php
                                $grower = $kandang->kandangPakan->firstWhere('stokPakan.jenis_pakan', 'Grower');
                                $layer = $kandang->kandangPakan->firstWhere('stokPakan.jenis_pakan', 'Layer');

                                $growerKg = ($grower->stok ?? 0) / 1000;
                                $layerKg = ($layer->stok ?? 0) / 1000;
                            @endphp

                            <small class="d-block text-muted">
                                🟢 Grower: {{ number_format($growerKg, 2) }} Kg
                            </small>
                            <small class="d-block text-muted">
                                🔵 Layer: {{ number_format($layerKg, 2) }} Kg
                            </small>

                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
