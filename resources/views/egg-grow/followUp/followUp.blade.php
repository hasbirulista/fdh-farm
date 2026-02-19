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
        {{-- HEADER --}}
        <div class="header-section">
            <h4>Follow Up / Repeat Order</h4>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark text-center small">
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Transaksi Terakhir</th>
                            <th>Pembelian Terakhir</th>
                            <th>Tanggal Repeat</th>
                            <th>No Hp</th>
                            <th>Sisa Hari</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($data as $row)
                            <tr
                                class="
                {{ $row['status'] == 'danger' ? 'table-danger' : '' }}
                {{ $row['status'] == 'warning' ? 'table-warning' : '' }}
            ">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row['nama_pelanggan'] }}</td>
                                <td>{{ $row['tanggal_transaksi'] }}</td>
                                <td>{{ $row['pembelian_terakhir_kg'] }} Kg</td>
                                <td>{{ $row['tanggal_repeat'] }}</td>
                                <td>{{ $row['no_hp'] }}</td>
                                <td class="text-center fw-bold">
                                    {{ $row['label_hari'] }}
                                </td>
                                <td class="text-center">
                                    @if ($row['status'] == 'danger')
                                        <span class="badge bg-danger">Terlambat</span>
                                    @elseif ($row['status'] == 'warning')
                                        <span class="badge bg-warning text-dark">Follow Up</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
