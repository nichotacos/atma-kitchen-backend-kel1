<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\PenggunaanBahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\BahanBaku;
use DateTime;

class LaporanController extends Controller
{
    //Laporan Penjualan Bulanan
    public function generateLaporanPenjualanBulanan(Request $request)
    {
        try {
            $request->validate([
                'year' => 'required|integer|min:2000|max:' . date('Y')
            ]);

            $year = $request->year;
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])->where('id_status', 12)
                ->whereYear('tanggal_pemesanan', $year)
                ->get();

            if ($transaksis->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            $monthlyReport = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyReport[$month] = [
                    'Jumlah Transaksi' => 0,
                    'Jumlah Uang' => 0
                ];
            }

            foreach ($transaksis as $transaksi) {
                $month = Carbon::parse($transaksi->tanggal_pelunasan)->month;
                $monthlyReport[$month]['Jumlah Transaksi'] += 1;
                $monthlyReport[$month]['Jumlah Uang'] += $transaksi->total_harga_final;
            }

            $totalTransactions = 0;
            $totalAmount = 0;
            $reportData = [];

            foreach ($monthlyReport as $month => $data) {
                $totalTransactions += $data['Jumlah Transaksi'];
                $totalAmount += $data['Jumlah Uang'];
                $reportData[] = [
                    'Bulan' => Carbon::create()->month($month)->translatedFormat('F'),
                    'Jumlah Transaksi' => $data['Jumlah Transaksi'],
                    'Jumlah Uang' => number_format($data['Jumlah Uang'], 0, ',', '.')
                ];
            }

            $reportData[] = [
                'Bulan' => 'Total',
                'Jumlah Transaksi' => $totalTransactions,
                'Jumlah Uang' => number_format($totalAmount, 0, ',', '.')
            ];

            $currentDate = Carbon::now()->translatedFormat('j F Y');

            return response()->json([
                "status" => true,
                'message' => 'Berhasil menampilkan data transaksi',
                'data' => [
                    'header' => [
                        'title' => 'LAPORAN PENJUALAN BULANAN',
                        'tahun' => $year,
                        'tanggal_cetak' => $currentDate,
                        'address' => 'Atma Kitchen, Jl. Centralpark No. 10 Yogyakarta'
                    ],
                    'report' => $reportData
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    //Laporan Penggunaan Bahan Baku
    public function generateLaporanPenggunaanBahanBaku(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if (!$startDate || !$endDate) {
                throw new \Exception('Start date and end date are required');
            }

            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            $penggunaanBahanBakus = PenggunaanBahanBaku::with(['transaksi', 'unit', 'bahanBaku'])
                ->whereBetween('tanggal_penggunaan', [$startDate, $endDate])
                ->get();

            if ($penggunaanBahanBakus->isEmpty()) {
                throw new \Exception('Data Penggunaan Bahan Baku tidak ditemukan');
            }

            $reportData = [];
            foreach ($penggunaanBahanBakus as $penggunaan) {
                $bahanBakuId = $penggunaan->bahanBaku->id_bahan_baku;
                if (!isset($reportData[$bahanBakuId])) {
                    $reportData[$bahanBakuId] = [
                        'nama_bahan' => $penggunaan->bahanBaku->nama_bahan_baku,
                        'satuan' => $penggunaan->unit->nama_unit,
                        'digunakan' => 0,
                    ];
                }
                $reportData[$bahanBakuId]['digunakan'] += $penggunaan->jumlah_penggunaan;
            }

            $formattedReportData = array_values(array_map(function ($data) {
                return [
                    'Nama Bahan' => $data['nama_bahan'],
                    'Satuan' => $data['satuan'],
                    'Digunakan' => $data['digunakan'],
                ];
            }, $reportData));

            $reportDetails = [
                "title" => "LAPORAN Penggunaan Bahan Baku",
                "periode" => "Periode: " . $startDate->format('d F Y') . " – " . $endDate->format('d F Y'),
                "tanggal_cetak" => "Tanggal cetak: " . now()->format('d F Y'),
                "nama_usaha" => "Atma Kitchen",
                "alamat_usaha" => "Jl. Centralpark No. 10 Yogyakarta",
            ];

            $report = [
                "header" => $reportDetails,
                "data" => $formattedReportData,
            ];

            return response()->json([
                "status" => true,
                "message" => "Penggunaan Bahan Baku Ditemukan",
                "report" => $report
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    // Laporan Penjualan Bulanan per Produk
    public function generateLaporanPenjualanBulananPerProduk(Request $request)
    {
        try {
            $year = $request->year;
            $month = $request->month;

            $monthlySales = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])->where('id_status', 12)
                ->whereYear('tanggal_pelunasan', $year)
                ->whereMonth('tanggal_pelunasan', $month)
                ->get();

            if ($monthlySales->isEmpty()) {
                throw new \Exception('No Sales Found for the Selected Month and Year');
            }

            foreach ($monthlySales as $sale) {
                foreach ($sale->cart->detailCart as $detail) {
                    $i = 0;
                    // Check if produk relationship exists and is not null
                    if ($detail->produk) {
                        $productId = $detail->produk->id_produk;
                        $productName = $detail->produk->nama_produk;

                        // Increment product sales count
                        if (isset($productSales[$productId])) {
                            $productSales[$productId]['total_sales'] += $detail->harga_total_terkini;
                            $productSales[$productId]['quantity'] += $detail->jumlah_produk;
                        } else {
                            $productSales[$productName] = [
                                'product_id' => $productId,
                                'product_name' => $productName,
                                'quantity' => $detail->jumlah_produk,
                                'price' => $detail->harga_produk_terkini,
                                'total_sales' => $detail->harga_total_terkini,
                            ];
                        }
                    }
                    if ($detail->hampers && $detail->hampers->produk) {
                        $produk = $detail->hampers->produk; // Retrieve the first item from the collection
                        if ($produk) {
                            foreach ($produk as $produk) {
                                $productId = $produk->id_produk;
                                $productName = $produk->nama_produk;
                                // Increment product sales count
                                if (isset($productSales[$productId])) {
                                    $productSales[$productId]['total_sales'] += $detail->harga_total_terkini;
                                    $productSales[$productId]['quantity'] += $detail->jumlah_produk;
                                } else {
                                    $productSales[$productName] = [
                                        'product_id' => $productId,
                                        'product_name' => $productName,
                                        'quantity' => $detail->jumlah_produk,
                                        'price' => $detail->harga_produk_terkini,
                                        'total_sales' => $detail->harga_total_terkini,
                                    ];
                                }
                            }
                        }
                    }
                    $i++;
                }
            }

            $productSales = array_values($productSales);

            $monthNum  = $month;
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F'); // March

            return response()->json([
                "status" => true,
                'message' => 'Monthly Sales Report Per Product Generated Successfully',
                'data' => [$monthlySales, $productSales, $year, $monthName]
              ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function generateLaporanStokBahanBaku(Request $request)
    {
        try {
            $bahanBaku = BahanBaku::query()->with('unit')
                ->get();

            if ($bahanBaku->isEmpty()) {
                throw new \Exception('Data Penggunaan Bahan Baku tidak ditemukan');
            }

            $reportData = [];
            foreach ($bahanBaku as $bahanBakus) {
                $bahanBakuId = $bahanBakus->id_bahan_baku;
                if (!isset($reportData[$bahanBakuId])) {
                    $reportData[$bahanBakuId] = [
                        'nama_bahan' => $bahanBakus->nama_bahan_baku,
                        'satuan' => $bahanBakus->unit->nama_unit,
                        'stok' => $bahanBakus->stok_bahan_baku,
                    ];
                }
            }

            $formattedReportData = array_values(array_map(function($data) {
                return [
                    'Nama Bahan' => $data['nama_bahan'],
                    'Satuan' => $data['satuan'],
                    'Stok' => $data['stok'],
                ];
            }, $reportData));

            $reportDetails = [
                "title" => "LAPORAN Penggunaan Bahan Baku",
                "tanggal_cetak" => "Tanggal cetak: " . now()->format('d F Y'),
                "nama_usaha" => "Atma Kitchen",
                "alamat_usaha" => "Jl. Centralpark No. 10 Yogyakarta",
            ];

            $report = [
                "header" => $reportDetails,
                "data" => $formattedReportData,
            ];

            return response()->json([
                "status" => true,
                "message" => "Stok Bahan Baku Ditemukan",
                "report" => $report
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }
}
