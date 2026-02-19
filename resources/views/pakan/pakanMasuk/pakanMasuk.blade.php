@extends('partials.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2 border-bottom">
        <h2><a href="/dashboard/gudang" class="text-decoration-none text-dark">FDH Farm</a> / Stok Pakan</h2>
        <a href="/dashboard/pakan/pakan-masuk/tambah" type="button" class="btn btn-primary mb-2 ms-3">Tambah</a>
    </div>
    @if (session('messageTambahPakanMasuk'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil Menambahkan Pakan !</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('messageUpdatePakanMasuk'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil Update Pakan !</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('messageDeletePakanMasuk'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil Menghapus Pakan !</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Pakan</th>
                    <th>Berat Total</th>
                    <th>Harga / Kg</th>
                    <th>Total Harga</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_pakan as $pakan)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($pakan->tanggal_pakan_masuk)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $pakan->jenis_pakan }}</td>
                        <td class="text-center">{{ number_format($pakan->berat_total, 0, ',', '.') }} gr</td>
                        <td class="text-center">Rp.{{ number_format($pakan->harga_kilo, 0, ',', '.') }},-</td>
                        <td class="text-center">Rp.{{ number_format($pakan->total_harga, 0, ',', '.') }},-</td>
                        <td class="text-center">
                            <button ype="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                                data-tanggal="{{ \Carbon\Carbon::parse($pakan->tanggal_pakan)->format('d/m/Y') }}"
                                data-jenispakan="{{ $pakan->jenis_pakan }}"
                                data-berattotal="{{ number_format($pakan->berat_total, 0, ',', '.') }}"
                                data-hargakilo="{{ number_format($pakan->harga_kilo, 0, ',', '.') }}"                                
                                data-totalharga="{{ number_format($pakan->total_harga, 0, ',', '.') }}">Detail</button>
                            <a href="/dashboard/pakan/pakan-masuk/{{ $pakan->id }}/edit" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#hapus{{ $pakan->id }}" >
                                Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Modal Hapus Data -->
        @foreach ($data_pakan as $pakan )
            <div class="modal fade" id="hapus{{ $pakan->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="/dashboard/pakan/pakan-masuk/{{ $pakan->id }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi !</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Data</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
        <!-- POP UP DETAIL TABEL DATA -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Pakan Masuk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Tanggal</th>
                                <td id="detailTanggal"></td>
                            </tr>
                            <tr>
                                <th>Jenis Pakan</th>
                                <td id="detailPakan"></td>
                            </tr>                            
                            <tr>
                                <th>Berat Total (gr)</th>
                                <td id="detailBeratTotal"></td>
                            </tr>
                            <tr>
                                <th>Harga / Kg</th>
                                <td id="detailHargaKilo"></td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td id="detailTotalHarga"></td>
                            </tr>                                         
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('exampleModal');

            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                document.getElementById('detailTanggal').textContent = button.dataset.tanggal;
                document.getElementById('detailPakan').textContent = button.dataset.jenispakan;               
                document.getElementById('detailBeratTotal').textContent = button.dataset.berattotal + ' gr';
                document.getElementById('detailHargaKilo').textContent = 'Rp.' + button.dataset.hargakilo;
                document.getElementById('detailTotalHarga').textContent = 'Rp.' + button.dataset.totalharga;                              
            });
        });
    </script>
@endsection
