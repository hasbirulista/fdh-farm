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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
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
    </style>
    <div class="mt-2">
        <div class="header-section">
            <h4>Kredit</h4>
        </div>

        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark text-center">
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Berat</th>
                    <th>Harga/Kg</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->tanggal_transaksi }}</td>
                        <td>{{ $item->pelanggan->nama_pelanggan }}</td>
                        <td>{{ $item->total_berat / 1000 }} Kg</td>
                        <td>Rp {{ number_format($item->harga_jual_kilo) }}</td>
                        <td>Rp {{ number_format($item->total_harga) }}</td>
                        <td>
                            <span class="badge bg-danger">Belum Lunas</span>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalLunas{{ $item->id }}">
                                Lunas
                            </button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modalLunas{{ $item->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Pelunasan</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    Apakah anda akan mengupdate data ini menjadi <b>lunas</b>? <br>
                                    <br>
                                    <b>Data Transaksi</b> <br>
                                    Nama : {{ $item->pelanggan->nama_pelanggan }} <br>
                                    Total Harga : Rp {{ number_format($item->total_harga) }} <br>
                                    Pembayaran : {{ $item->pembayaran }} <br>


                                </div>

                                <div class="modal-footer">

                                    <form action="{{ route('kredit.lunas', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <br>
                                        <label for="pembayaran">Ubah Metode Pembayaran:</label>
                                        <select id="pembayaran" name="pembayaran" class="form-control" required>
                                            <option value="{{ $item->pembayaran }}" selected>{{ $item->pembayaran }}
                                            </option>
                                            @if ($item->pembayaran == 'Tunai')
                                                <option value="Transfer">Transfer</option>
                                                <option value="Kredit">Kredit</option>
                                            @elseif($item->pembayaran == 'Transfer')
                                                <option value="Tunai">Tunai</option>
                                                <option value="Kredit">Kredit</option>
                                            @else
                                                <option value="Tunai">Tunai</option>
                                                <option value="Transfer">Transfer</option>
                                            @endif
                                        </select>
                                        <button class="btn btn-success">
                                            Ya, Lunas
                                        </button>
                                    </form>

                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
