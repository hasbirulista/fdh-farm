<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Carbon\Carbon;

class RepeatOrderController extends Controller
{
    public function followUp()
    {
        $today = Carbon::now()->startOfDay();

        $data = Pelanggan::where('repeat_order_aktif', true)
            ->whereNotNull('repeat_order_hari')
            ->whereHas('transaksiTerakhir')
            ->with('transaksiTerakhir')
            ->get()
            ->map(function ($pelanggan) use ($today) {

                $transaksiTerakhir = $pelanggan->transaksiTerakhir;

                // Tanggal transaksi terakhir
                $tanggalTransaksi = Carbon::parse($transaksiTerakhir->tanggal_transaksi)->startOfDay();

                // Tanggal repeat
                $tanggalRepeat = $tanggalTransaksi->copy()->addDays($pelanggan->repeat_order_hari);

                // ⭐ Hitung sisa hari berbasis tanggal (FIX glitch)
                $sisaHari = $today->diffInDays($tanggalRepeat, false);

                // Status
                if ($sisaHari < 0) {
                    $status = 'danger';
                } elseif ($sisaHari <= 3) {
                    $status = 'warning';
                } else {
                    $status = 'normal';
                }

                // Label tampilan
                if ($sisaHari < 0) {
                    $labelHari = "Terlambat " . abs($sisaHari) . " hari";
                } elseif ($sisaHari == 0) {
                    $labelHari = "Hari H";
                } else {
                    $labelHari = "H-" . $sisaHari;
                }

                return [
                    'nama_pelanggan'        => $pelanggan->nama_pelanggan,
                    'no_hp'                 => $pelanggan->no_hp,
                    'tanggal_transaksi'     => $tanggalTransaksi->format('Y-m-d'),
                    'pembelian_terakhir_kg' => floor($transaksiTerakhir->total_berat / 1000),
                    'tanggal_repeat'        => $tanggalRepeat->format('Y-m-d'),
                    'sisa_hari'             => $sisaHari,
                    'label_hari'            => $labelHari,
                    'status'                => $status,
                ];
            });

        return view('egg-grow.followUp.followUp', compact('data'), [
            'page' => 'Egg Grow'
        ]);
    }
}
