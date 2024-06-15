<?php

namespace App\Console\Commands;

use App\Models\Transaksi;
use Illuminate\Console\Command;

class ProcessTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = date('Y-m-d 00:00:00', strtotime(now()));

        $tomorrow = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($today)));

        $transaksis = Transaksi::with([
            'cart.detailCart.produk.DetailResep.bahanBaku.unit',
            'cart.detailCart.hampers.produk.DetailResep',
            'alamat',
            'status',
            'jenisPengambilan',
            'customer'
        ])
            ->where('tanggal_ambil', $tomorrow)
            ->orderBy('id_transaksi', 'asc')
            ->get();

        foreach ($transaksis as $transaksi) {
            $transaksi->status_id = 8;
            $transaksi->save();
        }
    }
}
