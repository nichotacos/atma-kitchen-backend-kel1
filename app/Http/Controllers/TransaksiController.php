<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Customer;
use App\Models\Hampers;
use App\Models\Kemasan;
use App\Models\PengadaanBahanBaku;
use App\Models\PenggunaanBahanBaku;
use App\Models\PenggunaanKemasan;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Notifications\PushNotifikasi;
use DateTime;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ]);

            if ($request->search) {
                $transaksis->where(function ($query) use ($request) {
                    $query->where('nomor_nota', 'like', '%' . $request->search . '%')
                        ->orWhereHas('customer', function ($query) use ($request) {
                            $query->where('nama_customer', 'like', '%' . $request->search . '%');
                        });
                });
            }

            if ($request->id_transaksi) {
                $transaksis->where('id_transaksi', $request->id_transaksi);
            }

            if ($request->id_status) {
                $transaksis->where('id_status', $request->id_status);
            }

            if ($request->tanggal_ambil) {
                $transaksis->where('tanggal_ambil', $request->tanggal_ambil);
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_transaksi', 'nomor_nota', 'total_harga_produk', 'total_harga_final', 'tanggal_pemesanan', 'tanggal_pelunasan', 'tanggal_ambil'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_transaksi';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $transaksis->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            return response()->json([
                "status" => true,
                'message' => 'Berhasil menampilkan data transaksi',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function storeTransaksi(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_customer' => 'required|numeric',
                'id_pengambilan' => 'required|numeric',
                'id_cart' => 'required|numeric',
                'id_status' => 'required|numeric',
                'id_alamat' => 'nullable|numeric',
                'tanggal_pemesanan' => 'required|date',
                'tanggal_pelunasan' => 'nullable|date',
                'tanggal_ambil' => 'nullable|date',
                'total_harga_produk' => 'required|numeric',
                'jarak_pengiriman' => 'required|numeric',
                'ongkos_kirim' => 'required|numeric',
                'total_setelah_ongkir' => 'required|numeric',
                'poin_sebelumnya' => 'required|numeric',
                'poin_digunakan' => 'required|numeric',
                'total_harga_final' => 'required|numeric',
                'perolehan_poin' => 'required|numeric',
                'nominal_tip' => 'required|numeric',
                'bukti_pembayaran' => 'nullable|string',
            ]);

            $transaction = new Transaksi($validatedData);
            $transaction->nomor_nota = '';
            $transaction->save();

            $transaction->nomor_nota = $transaction->generateNomorNota();
            $transaction->save();

            return response()->json([
                "status" => true,
                "message" => "Transaksi berhasil ditambahkan",
                "data" => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    //Coding 2
    public function showTransaksiInputJarak(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
                ->where('id_pengambilan', 1)
                ->where('jarak_pengiriman', 0)
                ->where('id_status', 1);

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

            if ($data->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            return response()->json([
                "status" => true,
                "message" => "Transaksi Ditemukan",
                "data" => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function updateJarakPengiriman(Request $request, $id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) throw new \Exception("Transaksi Not Found");

            $validatedData = $request->validate([
                'jarak_pengiriman' => 'required|numeric|min:0',
            ]);

            $transaksis->update([
                'jarak_pengiriman' => $request->jarak_pengiriman,
            ]);

            $jarak_pengiriman = $request->jarak_pengiriman;
            $total_harga_final = $transaksis->total_harga_final;

            if ($jarak_pengiriman > 15) {
                $total_setelah_ongkir = $total_harga_final + 25000;
                $transaksis->ongkos_kirim = 25000;
            } elseif ($jarak_pengiriman > 10) {
                $total_setelah_ongkir = $total_harga_final + 20000;
                $transaksis->ongkos_kirim = 20000;
            } elseif ($jarak_pengiriman > 5) {
                $total_setelah_ongkir = $total_harga_final + 15000;
                $transaksis->ongkos_kirim = 15000;
            } else {
                $total_setelah_ongkir = $total_harga_final + 10000;
                $transaksis->ongkos_kirim = 10000;
            }

            $transaksis->id_status = 2;
            $transaksis->total_setelah_ongkir = $total_setelah_ongkir;
            $transaksis->total_harga_final = $total_setelah_ongkir;
            $transaksis->save();

            $user = Customer::find($transaksis->id_customer);
            $user->notify(new PushNotifikasi);
            if (!$user) {
                throw new \Exception("Customer Not Found");
            }

            return response()->json([
                "status" => true,
                "message" => "Jarak Pengiriman berhasil diperbarui",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal memperbarui jarak pengiriman: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function showTransaksiBelumValid(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
                ->where('id_status', 3);

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

            if ($data->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            return response()->json([
                "status" => true,
                "message" => "Transaksi Ditemukan",
                "data" => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function updateJumlahPembayaran(Request $request, $id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $validatedData = $request->validate([
                'value' => 'required|numeric',
            ]);

            $inputValue = $validatedData['value'];
            $newNominalTip = $inputValue - $transaksis->total_harga_final;
            $transaksis->nominal_tip = $newNominalTip;
            $transaksis->id_status = 5;
            $transaksis->save();

            $user = Customer::find($transaksis->id_customer);
            $user->notify(new PushNotifikasi);
            if (!$user) {
                throw new \Exception("Customer Not Found");
            }

            return response()->json([
                "status" => true,
                "message" => "Nominal tip updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update nominal tip: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function getSisaKuota(Request $request)
    {
        try {
            $inputtedDate = $request->input('tanggal_ambil');
            $targettedProduct = $request->input('id_produk');

            $transaksis = Transaksi::where('tanggal_ambil', $inputtedDate)
                ->whereHas('cart', function ($query) use ($targettedProduct) {
                    $query->whereHas('detailCart', function ($subQuery) use ($targettedProduct) {
                        $subQuery->where('id_produk', $targettedProduct);
                    });
                })
                ->with('cart.detailCart')
                ->get();

            // echo $transaksis;

            $totalQuantity = $transaksis->sum(function ($transaksi) use ($targettedProduct) {
                return $transaksi->cart->detailCart
                    ->where('id_produk', $targettedProduct)
                    ->sum('jumlah_produk');
            });

            $totalKuota = 10 - $totalQuantity;

            return response()->json([
                "status" => true,
                "message" => "Sisa kuota berhasil didapatkan",
                "data" => $totalKuota
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to get sisa kuota: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function showTransaksiPembayaranValid(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
                ->whereIn('id_status', [5]);

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

            if ($data->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            return response()->json([
                "status" => true,
                "message" => "Transaksi Ditemukan",
                "data" => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Coding 3
    public function showTransaksiDiproses(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
                ->whereIn('id_status', [8, 9, 10, 11]);

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

            if ($data->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            return response()->json([
                "status" => true,
                "message" => "Transaksi Ditemukan",
                "data" => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function getSisaKuotaHampers(Request $request)
    {
        try {
            $inputtedDate = $request->input('tanggal_ambil');
            $hamperId = $request->input('id_hampers');

            $hamper = Hampers::with('produk')->findOrFail($hamperId);
            $products = $hamper->produk;

            $quotas = [];


            foreach ($products as $product) {
                $productId = $product->id_produk;

                $transaksis = Transaksi::where('tanggal_ambil', $inputtedDate)
                    ->whereHas('cart.detailCart', function ($query) use ($productId) {
                        $query->where('id_produk', $productId);
                    })
                    ->with('cart.detailCart')
                    ->get();

                $totalQuantity = $transaksis->sum(function ($transaksi) use ($productId) {
                    return $transaksi->cart->detailCart
                        ->where('id_produk', $productId)
                        ->sum('jumlah_produk');
                });

                $totalKuota = 10 - $totalQuantity;

                $quotas[] = $totalKuota;
            }

            $minQuota = min($quotas);

            return response()->json([
                "status" => true,
                "message" => "Sisa kuota berhasil didapatkan",
                "data" => $minQuota
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to get sisa kuota: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function updateTransaksiSiapDipickup($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            if ($transaksis->id_status != 8) {
                throw new \Exception("Transaksi tidak dalam status diproses");
            }

            if ($transaksis->id_pengambilan == 2) {
                $transaksis->id_status = 9;
            } else {
                $transaksis->id_status = 10;
            }
            $transaksis->save();

            $user = Customer::find($transaksis->id_customer);
            $user->notify(new PushNotifikasi);
            if (!$user) {
                throw new \Exception("Customer Not Found");
            }

            return response()->json([
                "status" => true,
                "message" => "Status updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update Status: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function updateTransaksiSudahDipickup($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            if ($transaksis->id_pengambilan == 2) {
                $transaksis->id_status = 11;
            } else {
                $transaksis->id_status = 10;
            }
            $transaksis->save();

            $user = Customer::find($transaksis->id_customer);
            $user->notify(new PushNotifikasi);
            if (!$user) {
                throw new \Exception("Customer Not Found");
            }

            return response()->json([
                "status" => true,
                "message" => "Status updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update status" . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function calculatePoint(Request $request)
    {
        try {
            $total_price = $request->input('total_price');
            $birth_date = $request->input('birth_date');

            $point = 0;

            while ($total_price >= 10000) {
                if ($total_price >= 1000000) {
                    $point += 200;
                    $total_price -= 1000000;
                } else if ($total_price >= 500000) {
                    $point += 75;
                    $total_price -= 500000;
                } else if ($total_price >= 100000) {
                    $point += 15;
                    $total_price -= 100000;
                } else if ($total_price >= 10000) {
                    $point += 1;
                    $total_price -= 10000;
                }
            }

            // Ngecek ultah user ga pake tahun
            if ($birth_date) {
                $birthDateWithoutYear = Carbon::parse($birth_date)->format('m-d');
                $todayWithoutYear = now()->format('m-d');

                if ($birthDateWithoutYear === $todayWithoutYear) {
                    $point += $point;
                }
            }

            return response()->json([
                "status" => true,
                "message" => "Point berhasil dihitung",
                "data" => $point
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal menghitung point: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function showTransaksiBatal(Request $request)
  {
      try {
          $transaksis = Transaksi::with([
              'cart.detailCart.produk',
              'cart.detailCart.hampers.produk',
              'alamat',
              'status',
              'jenisPengambilan',
              'cart.detailCart.hampers.kemasan',
              'customer'
          ])->whereNull('tanggal_pelunasan')
              ->whereRaw('DATE(tanggal_ambil) <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)');

          $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

          if ($data->isEmpty()) {
              throw new \Exception('Transaksi Tidak Ditemukan');
          }

          foreach ($data as $transaksi) {
              //1 == Ready Stock
              //2 == Pre Order
              $status_transaksi = 1;

              if ($transaksi->cart && $transaksi->cart->detailCart) {
                  foreach ($transaksi->cart->detailCart as $detailCart) {
                      if ($detailCart->id_jenis_ketersediaan == 2) {
                          $status_transaksi = 2;
                          break;
                      }
                  }

                  if ($transaksi->id_status != 4) {
                      if ($status_transaksi == 1) {
                          $tanggal_ambil = new DateTime($transaksi->tanggal_ambil);
                          $tanggal_sekarang = new DateTime(Carbon::now()->toDateString());
                          if ($tanggal_ambil < $tanggal_sekarang) {
                              foreach ($transaksi->cart->detailCart as $detailCart) {
                                  if ($detailCart->id_produk != null) {
                                      $produk = $detailCart->produk;
                                      $produk->stok += $detailCart->jumlah_produk;
                                      $produk->id_jenis_ketersediaan = 1;
                                      $produk->save();
                                  } else {
                                      foreach ($detailCart->hampers->produk as $hamperProduk) {
                                          $produk = Produk::find($hamperProduk->id_produk);
                                          if ($produk) {
                                              if ($produk->stok == 0 && $produk->id_jenis_ketersediaan == 2) {
                                                  $produk->id_jenis_ketersediaan = 1;
                                              }
                                              $produk->stok += $detailCart->jumlah_produk;
                                              $produk->save();
                                          } else {
                                              throw new \Exception('Produk not found for id_produk: ' . $hamperProduk->id_produk);
                                          }
                                      }
                                  }
                              }
                              $customer = Customer::find($transaksi->id_customer);
                              $customer->poin = $customer->poin + $transaksi->poin_digunakan;
                              $customer->save();
                              $transaksi->id_status = 4;
                              $transaksi->save();
                              $user = Customer::find($transaksis->id_customer);
                              $user->notify(new PushNotifikasi);
                              if (!$user) {
                                  throw new \Exception("Customer Not Found");
                              }
                          } else {
                              $data = $data->reject(function ($item) use ($transaksi) {
                                  return $item->id_transaksi === $transaksi->id_transaksi;
                              });
                          }
                      } else if ($status_transaksi == 2) {
                          foreach ($transaksi->cart->detailCart as $detailCart) {
                              if ($detailCart->id_jenis_ketersediaan == 1) {
                                      if ($detailCart->id_produk != null) {
                                          $produk = $detailCart->produk;
                                          $produk->stok += $detailCart->jumlah_produk;
                                          $produk->id_jenis_ketersediaan = 1;
                                          $produk->save();
                                      } else {
                                          foreach ($detailCart->hampers->produk as $hamperProduk) {
                                              $produk = Produk::find($hamperProduk->id_produk);
                                              if ($produk) {
                                                  if ($produk->stok == 0 && $produk->id_jenis_ketersediaan == 2) {
                                                      $produk->id_jenis_ketersediaan = 1;
                                                  }
                                                  $produk->stok += $detailCart->jumlah_produk;
                                                  $produk->save();
                                              } else {
                                                  throw new \Exception('Produk not found for id_produk: ' . $hamperProduk->id_produk);
                                              }
                                          }
                                      }
                              }
                          }
                          $customer = Customer::find($transaksi->id_customer);
                          $customer->poin = $customer->poin + $transaksi->poin_digunakan;
                          $customer->save();
                          $transaksi->id_status = 4;
                          $transaksi->save();
                          $user = Customer::find($transaksis->id_customer);
                            $user->notify(new PushNotifikasi);
                            if (!$user) {
                                throw new \Exception("Customer Not Found");
                            }
                      }
                  }
              } else {
                  \Log::error('Cart or detailCart is null for transaksi ID: ' . $transaksi->id_transaksi);
              }
          }

          return response()->json([
              "status" => true,
              "message" => "Transaksi Ditemukan",
              "data" => $data
          ], 200);
      } catch (\Exception $e) {
          return response()->json([
              "status" => false,
              "message" => $e->getMessage(),
              "data" => []
          ], 400);
      }
  }


    public function updateTransaksiBatal($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $transaksis->id_status = 4;
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Nominal tip updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update nominal tip: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function getProductsFromTransaksi(Request $request)
    {
        try {
            $transaksiId = $request->input('id_transaksi');
            $transaksi = Transaksi::find($transaksiId);

            if (!$transaksi) {
                throw new \Exception("Transaksi Not Found");
            }

            $products = $transaksi->cart->detailCart->map(function ($detailCart) {
                if ($detailCart->produk) {
                    return [
                        'id_produk' => $detailCart->id_produk,
                        'nama_produk' => $detailCart->produk->nama_produk,
                        'jumlah_produk' => $detailCart->jumlah_produk,
                        'harga_produk_terkini' => $detailCart->harga_produk_terkini,
                        'harga_total_terkini' => $detailCart->harga_total_terkini,
                    ];
                } else if ($detailCart->hampers) {
                    return [
                        'id_produk' => $detailCart->id_hampers,
                        'nama_produk' => $detailCart->hampers->nama_hampers,
                        'jumlah_produk' => $detailCart->jumlah_produk,
                        'harga_produk_terkini' => $detailCart->harga_produk_terkini,
                        'harga_total_terkini' => $detailCart->harga_total_terkini,
                    ];
                }
            });

            return response()->json([
                "status" => true,
                "message" => "Produk berhasil didapatkan",
                "data" => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal mendapatkan produk: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function terimaPesanan($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $transaksis->id_status = 6;
            $transaksis->save();

            $currentUser = Customer::find($transaksis->id_customer);
            $currentUser->poin += $transaksis->perolehan_poin - $transaksis->poin_digunakan;
            $currentUser->save();

            $transaksis->total_harga_final = $transaksis->total_harga_final - ($transaksis->poin_digunakan * 100);
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Nominal tip updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update nominal tip: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function tolakPesanan($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $transaksis->id_status = 7;
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Nominal tip updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update nominal tip: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function transferSaldo(Request $request)
    {
        try {
            $total_price = $request->input('total_price');
            $birth_date = $request->input('birth_date');

            $point = 0;

            while ($total_price >= 10000) {
                if ($total_price >= 1000000) {
                    $point += 200;
                    $total_price -= 1000000;
                } else if ($total_price >= 500000) {
                    $point += 75;
                    $total_price -= 500000;
                } else if ($total_price >= 100000) {
                    $point += 15;
                    $total_price -= 100000;
                } else if ($total_price >= 10000) {
                    $point += 1;
                    $total_price -= 10000;
                }
            }

            // Ngecek ultah user ga pake tahun
            if ($birth_date) {
                $birthDateWithoutYear = Carbon::parse($birth_date)->format('m-d');
                $todayWithoutYear = now()->format('m-d');

                if ($birthDateWithoutYear === $todayWithoutYear) {
                    $point += $point;
                }
            }

            return response()->json([
                "status" => true,
                "message" => "Point berhasil dihitung",
                "data" => $point
              , 200]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to process transaction: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function showPesananHariIni()
    {
        try {
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
                ->where('id_status', 8)
                ->orderBy('id_transaksi', 'asc')
                ->get();

            if ($transaksis->isEmpty()) {
                return response()->json([
                    "status" => true,
                    "message" => "Tidak ada pesanan hari ini",
                    "data" => []
                ], 200);
            }

            // Buat filter produk yang preorder
            foreach ($transaksis as $transaksi) {
                $transaksi->cart->detailCartFiltered = collect();

                foreach ($transaksi->cart->detailCart as $detailCart) {
                    if ($detailCart->produk && $detailCart->produk->id_jenis_ketersediaan != 1) {
                        $transaksi->cart->detailCartFiltered->push($detailCart);
                    } elseif ($detailCart->hampers) {
                        $hamperProducts = $detailCart->hampers->produk;
                        $newDetailCart = new \App\Models\DetailCart();
                        $newDetailCart->id_detail_cart = $detailCart->id_detail_cart;
                        foreach ($hamperProducts as $hamperProduct) {
                            if ($hamperProduct->id_jenis_ketersediaan != 1) {
                                $newDetailCart->jumlah_produk = $detailCart->jumlah_produk;
                                $newDetailCart->produk = $hamperProduct;

                                $transaksi->cart->detailCartFiltered->push($newDetailCart);
                            }
                        }
                    }
                }
            }

            // Hitung rekap jumlah produk
            $totalLapisLegitQty = 0;
            $totalLapisSurabayaQty = 0;
            $totalBrowniesQty = 0;
            $totalMandarinQty = 0;
            $totalSpikoeQty = 0;

            foreach ($transaksis as $transaksi) {
                $lapisLegitQty = 0;
                $lapisSurabayaQty = 0;
                $browniesQty = 0;
                $mandarinQty = 0;
                $spikoeQty = 0;

                foreach ($transaksi->cart->detailCartFiltered as $detailCart) {
                    if ($detailCart->produk) {
                        if ($detailCart->produk->id_produk === 1 || $detailCart->produk->id_produk === 2) {
                            if ($detailCart->produk->id_produk === 1) {
                                $lapisLegitQty += $detailCart->jumlah_produk * 1;
                            } else {
                                $lapisLegitQty += ($detailCart->jumlah_produk) / 2;
                            }
                        } else if ($detailCart->produk->id_produk === 3 || $detailCart->produk->id_produk === 4) {
                            if ($detailCart->produk->id_produk === 3) {
                                $lapisSurabayaQty += $detailCart->jumlah_produk * 1;
                            } else {
                                $lapisSurabayaQty += $detailCart->jumlah_produk * 1 / 2;
                            }
                        } else if ($detailCart->produk->id_produk === 5 || $detailCart->produk->id_produk === 6) {
                            if ($detailCart->produk->id_produk === 5) {
                                $browniesQty += $detailCart->jumlah_produk * 1;
                            } else {
                                $browniesQty += $detailCart->jumlah_produk * 1 / 2;
                            }
                        } else if ($detailCart->produk->id_produk === 7 || $detailCart->produk->id_produk === 8) {
                            if ($detailCart->produk->id_produk === 7) {
                                $mandarinQty += $detailCart->jumlah_produk * 1;
                            } else {
                                $mandarinQty += $detailCart->jumlah_produk * 1 / 2;
                            }
                        } else if ($detailCart->produk->id_produk === 9 || $detailCart->produk->id_produk === 10) {
                            if ($detailCart->produk->id_produk === 9) {
                                $spikoeQty += $detailCart->jumlah_produk * 1;
                            } else {
                                $spikoeQty += $detailCart->jumlah_produk * 1 / 2;
                            }
                        }
                    } else {
                        if ($detailCart->hampers->produk) {
                            foreach ($detailCart->hampers->produk as $produk) {
                                if ($produk->id_produk === 1 || $produk->id_produk === 2) {
                                    if ($produk->id_produk === 1) {
                                        $lapisLegitQty += $detailCart->jumlah_produk * 1;
                                    } else {
                                        $lapisLegitQty += ($detailCart->jumlah_produk) / 2;
                                    }
                                } else if ($produk->id_produk === 3 || $produk->id_produk === 4) {
                                    if ($produk->id_produk === 3) {
                                        $lapisSurabayaQty += $detailCart->jumlah_produk * 1;
                                    } else {
                                        $lapisSurabayaQty += $detailCart->jumlah_produk * 1 / 2;
                                    }
                                } else if ($produk->id_produk === 5 || $produk->id_produk === 6) {
                                    if ($produk->id_produk === 5) {
                                        $browniesQty += $detailCart->jumlah_produk * 1;
                                    } else {
                                        $browniesQty += $detailCart->jumlah_produk * 1 / 2;
                                    }
                                } else if ($produk->id_produk === 7 || $produk->id_produk === 8) {
                                    if ($produk->id_produk === 7) {
                                        $mandarinQty += $detailCart->jumlah_produk * 1;
                                    } else {
                                        $mandarinQty += $detailCart->jumlah_produk * 1 / 2;
                                    }
                                } else if ($produk->id_produk === 9 || $produk->id_produk === 10) {
                                    if ($produk->id_produk === 9) {
                                        $spikoeQty += $detailCart->jumlah_produk * 1;
                                    } else {
                                        $spikoeQty += $detailCart->jumlah_produk * 1 / 2;
                                    }
                                }
                            }
                        }
                    }
                }
                $transaksi->cart->recap = [
                    'lapis_legit' => $lapisLegitQty,
                    'lapis_surabaya' => $lapisSurabayaQty,
                    'brownies' => $browniesQty,
                    'mandarin' => $mandarinQty,
                    'spikoe' => $spikoeQty,
                ];

                if ($lapisLegitQty == 0.5) $lapisLegitQty = 1;
                if ($lapisSurabayaQty == 0.5) $lapisSurabayaQty = 1;
                if ($browniesQty == 0.5) $browniesQty = 1;
                if ($mandarinQty == 0.5) $mandarinQty = 1;
                if ($spikoeQty == 0.5) $spikoeQty = 1;

                $totalLapisLegitQty += $lapisLegitQty;
                $totalLapisSurabayaQty += $lapisSurabayaQty;
                $totalBrowniesQty += $browniesQty;
                $totalMandarinQty += $mandarinQty;
                $totalSpikoeQty += $spikoeQty;
            }

            $totalRecap = [
                [
                    'name' => 'Lapis Legit',
                    'quantity' => $totalLapisLegitQty
                ],
                [
                    'name' => 'Lapis Surabaya',
                    'quantity' => $totalLapisSurabayaQty
                ],
                [
                    'name' => 'Brownies',
                    'quantity' => $totalBrowniesQty
                ],
                [
                    'name' => 'Mandarin',
                    'quantity' => $totalMandarinQty
                ],
                [
                    'name' => 'Spikoe',
                    'quantity' => $totalSpikoeQty
                ]
            ];

            // Hitung bahan yang digunakan
            $productMapping = [
                'Lapis Legit' => 1,
                'Lapis Surabaya' => 3,
                'Brownies' => 5,
                'Mandarin' => 7,
                'Spikoe' => 9
            ];

            $recipes = [];

            foreach ($totalRecap as $recap) {
                if ($recap['quantity'] > 0) {
                    $productId = $productMapping[$recap['name']];
                    $product = Produk::with('DetailResep.bahanBaku.unit')->find($productId);

                    $recipeDetails = [];
                    foreach ($product->DetailResep as $resep) {
                        $recipeDetails[] = (object)[
                            'bahan' => $resep->bahanBaku->nama_bahan_baku,
                            'unit' => $resep->bahanBaku->unit->nama_unit,
                            'quantity' => $resep->jumlah * $recap['quantity'],
                        ];
                    }
                    $recipes[] = (object)[
                        'name' => $recap['name'],
                        'details' => $recipeDetails
                    ];
                }
            }

            // Hitung total bahan yang digunakan serta urut secara alfabetik dan ascending
            $totalRecipe = [];
            $bahanMapping = [];

            foreach ($recipes as $recipe) {
                foreach ($recipe->details as $detail) {
                    $bahan = $detail->bahan;
                    $unit = $detail->unit;
                    $quantity = $detail->quantity;

                    if (array_key_exists($bahan, $bahanMapping)) {
                        $index = $bahanMapping[$bahan];
                        $totalRecipe[$index]->quantity += $quantity;
                    } else {
                        $totalRecipe[] = (object)[
                            'bahan' => $bahan,
                            'unit' => $unit,
                            'quantity' => $quantity,
                            'current_stock' => BahanBaku::where('nama_bahan_baku', $bahan)->first()->stok_bahan_baku
                        ];
                        $bahanMapping[$bahan] = count($totalRecipe) - 1;
                    }
                }
            }

            // sort by bahan ascending
            usort($totalRecipe, function ($a, $b) {
                return strcmp($a->bahan, $b->bahan);
            });

            foreach ($transaksis as $transaksi) {
                $insufficientIngredients = []; // Moved inside the loop
                $transactionRecipes = [];

                $looper = 1;

                // Sum the required quantities for each ingredient
                foreach ($transaksi->cart->recap as $recap) {
                    if ($recap !== 0) {
                        $target = Produk::with('DetailResep.bahanBaku.unit')->find($looper);

                        foreach ($target->DetailResep as $resep) {
                            $bahan = $resep->bahanBaku->nama_bahan_baku;
                            $unit = $resep->bahanBaku->unit->nama_unit;
                            $quantity = $resep->jumlah * $recap;

                            if (array_key_exists($bahan, $transactionRecipes)) {
                                $transactionRecipes[$bahan]->quantity += $quantity;
                            } else {
                                $transactionRecipes[$bahan] = (object)[
                                    'bahan' => $bahan,
                                    'unit' => $unit,
                                    'quantity' => $quantity,
                                    'current_stock' => BahanBaku::where('nama_bahan_baku', $bahan)->first()->stok_bahan_baku
                                ];
                            }
                        }

                        $looper += 2;
                    }
                }

                foreach ($transactionRecipes as $transactionRecipe) {
                    $bahan = $transactionRecipe->bahan;
                    $quantity = $transactionRecipe->quantity;
                    $currentStock = $transactionRecipe->current_stock;

                    if ($currentStock < $quantity) {
                        $insufficientIngredients[] = (object)[
                            'bahan' => $bahan,
                            'quantity' => $quantity - $currentStock,
                            'unit' => $transactionRecipe->unit
                        ];
                    }
                }

                $transaksi->cart->insufficientIngredients = $insufficientIngredients;
            }

            return response()->json([
                "status" => true,
                "message" => "Transaksi Ditemukan",
                "data" => [$transaksis, $totalRecap, $recipes, $totalRecipe, $transactionRecipes]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //TODO: Kelola pesanan yang berlebihan
    public function prosesPesanan($id_transaksi)
    {
        try {
            $transaction = Transaksi::with([
                'cart.detailCart.produk.DetailResep.bahanBaku.unit',
                'cart.detailCart.hampers.produk.DetailResep',
                'alamat',
                'status',
                'jenisPengambilan',
                'customer'
            ])
                ->where('id_transaksi', $id_transaksi)
                ->first();

            if (!$transaction) {
                throw new \Exception("Transaksi Not Found");
            }

            foreach ($transaction as $transaksi) {
                $transaction->cart->detailCartFiltered = collect();

                foreach ($transaction->cart->detailCart as $detailCart) {
                    if ($detailCart->produk && $detailCart->produk->id_produk <= 10) {
                        $transaction->cart->detailCartFiltered->push($detailCart);
                    } elseif ($detailCart->hampers) {
                        $hamperProducts = $detailCart->hampers->produk;
                        $newDetailCart = new \App\Models\DetailCart();
                        $newDetailCart->id_detail_cart = $detailCart->id_detail_cart;
                        foreach ($hamperProducts as $hamperProduct) {
                            if ($hamperProduct->id_jenis_ketersediaan != 1) {
                                $newDetailCart->jumlah_produk = $detailCart->jumlah_produk;
                                $newDetailCart->produk = $hamperProduct;

                                $transaction->cart->detailCartFiltered->push($newDetailCart);
                            }
                        }
                    }
                }
            }

            foreach ($transaction as $transaksi) {
                $transaction->cart->detailCartFilteredReady = collect();

                foreach ($transaction->cart->detailCart as $detailCart) {
                    if ($detailCart->produk && $detailCart->produk->id_produk > 10) {
                        $transaction->cart->detailCartFiltered->push($detailCart);
                    } elseif ($detailCart->hampers) {
                        $hamperProducts = $detailCart->hampers->produk;
                        $newDetailCart = new \App\Models\DetailCart();
                        $newDetailCart->id_detail_cart = $detailCart->id_detail_cart;
                        foreach ($hamperProducts as $hamperProduct) {
                            if ($hamperProduct->id_jenis_ketersediaan != 1) {
                                $newDetailCart->jumlah_produk = $detailCart->jumlah_produk;
                                $newDetailCart->produk = $hamperProduct;

                                $transaction->cart->detailCartFiltered->push($newDetailCart);
                            }
                        }
                    }
                }
            }

            $lapisLegitQty = 0;
            $lapisSurabayaQty = 0;
            $browniesQty = 0;
            $mandarinQty = 0;
            $spikoeQty = 0;

            $box20qty = 0;
            $box10qty = 0;
            $premBoxqty = 0;

            foreach ($transaction->cart->detailCartFiltered as $detailCart) {
                if ($detailCart->produk) {
                    if ($detailCart->produk->id_produk === 1 || $detailCart->produk->id_produk === 2) {
                        if ($detailCart->produk->id_produk === 1) {
                            $lapisLegitQty += $detailCart->jumlah_produk * 1;
                            $box20qty += $lapisLegitQty;
                        } else {
                            $lapisLegitQty += ($detailCart->jumlah_produk) / 2;
                            $box10qty += $detailCart->jumlah_produk;
                        }
                    } else if ($detailCart->produk->id_produk === 3 || $detailCart->produk->id_produk === 4) {
                        if ($detailCart->produk->id_produk === 3) {
                            $lapisSurabayaQty += $detailCart->jumlah_produk * 1;
                            $box20qty += $lapisSurabayaQty;
                        } else {
                            $lapisSurabayaQty += $detailCart->jumlah_produk * 1 / 2;
                            $box10qty += $detailCart->jumlah_produk;
                        }
                    } else if ($detailCart->produk->id_produk === 5 || $detailCart->produk->id_produk === 6) {
                        if ($detailCart->produk->id_produk === 5) {
                            $browniesQty += $detailCart->jumlah_produk * 1;
                            $box20qty += $browniesQty;
                        } else {
                            $browniesQty += $detailCart->jumlah_produk * 1 / 2;
                            $box10qty += $detailCart->jumlah_produk;
                        }
                    } else if ($detailCart->produk->id_produk === 7 || $detailCart->produk->id_produk === 8) {
                        if ($detailCart->produk->id_produk === 7) {
                            $mandarinQty += $detailCart->jumlah_produk * 1;
                            $box20qty += $mandarinQty;
                        } else {
                            $mandarinQty += $detailCart->jumlah_produk * 1 / 2;
                            $box10qty += $detailCart->jumlah_produk;
                        }
                    } else if ($detailCart->produk->id_produk === 9 || $detailCart->produk->id_produk === 10) {
                        if ($detailCart->produk->id_produk === 9) {
                            $spikoeQty += $detailCart->jumlah_produk * 1;
                            $box20qty += $spikoeQty;
                        } else {
                            $spikoeQty += $detailCart->jumlah_produk * 1 / 2;
                            $box10qty += $detailCart->jumlah_produk;
                        }
                    }
                } else {
                    if ($detailCart->hampers->produk) {
                        foreach ($detailCart->hampers->produk as $produk) {
                            if ($produk->id_produk === 1 || $produk->id_produk === 2) {
                                if ($produk->id_produk === 1) {
                                    $lapisLegitQty += $detailCart->jumlah_produk * 1;
                                    $box20qty += $lapisLegitQty;
                                } else {
                                    $lapisLegitQty += ($detailCart->jumlah_produk) / 2;
                                    $box10qty += $detailCart->jumlah_produk;
                                }
                            } else if ($produk->id_produk === 3 || $produk->id_produk === 4) {
                                if ($produk->id_produk === 3) {
                                    $lapisSurabayaQty += $detailCart->jumlah_produk * 1;
                                    $box20qty += $lapisSurabayaQty;
                                } else {
                                    $lapisSurabayaQty += $detailCart->jumlah_produk * 1 / 2;
                                    $box10qty += $detailCart->jumlah_produk;
                                }
                            } else if ($produk->id_produk === 5 || $produk->id_produk === 6) {
                                if ($produk->id_produk === 5) {
                                    $browniesQty += $detailCart->jumlah_produk * 1;
                                    $box20qty += $browniesQty;
                                } else {
                                    $browniesQty += $detailCart->jumlah_produk * 1 / 2;
                                    $box10qty += $detailCart->jumlah_produk;
                                }
                            } else if ($produk->id_produk === 7 || $produk->id_produk === 8) {
                                if ($produk->id_produk === 7) {
                                    $mandarinQty += $detailCart->jumlah_produk * 1;
                                    $box20qty += $mandarinQty;
                                } else {
                                    $mandarinQty += $detailCart->jumlah_produk * 1 / 2;
                                    $box10qty += $detailCart->jumlah_produk;
                                }
                            } else if ($produk->id_produk === 9 || $produk->id_produk === 10) {
                                if ($produk->id_produk === 9) {
                                    $spikoeQty += $detailCart->jumlah_produk * 1;
                                    $box20qty += $spikoeQty;
                                } else {
                                    $spikoeQty += $detailCart->jumlah_produk * 1 / 2;
                                    $box10qty += $detailCart->jumlah_produk;
                                }
                            }
                            $premBoxqty++;
                        }
                    }
                }
            }

            if ($lapisLegitQty == 0.5) $lapisLegitQty = 1;
            if ($lapisSurabayaQty == 0.5) $lapisSurabayaQty = 1;
            if ($browniesQty == 0.5) $browniesQty = 1;
            if ($mandarinQty == 0.5) $mandarinQty = 1;
            if ($spikoeQty == 0.5) $spikoeQty = 1;

            $transaction->cart->recap = [
                'lapis_legit' => $lapisLegitQty,
                'lapis_surabaya' => $lapisSurabayaQty,
                'brownies' => $browniesQty,
                'mandarin' => $mandarinQty,
                'spikoe' => $spikoeQty,
            ];

            $botolQty = 0;

            foreach ($transaction->cart->detailCartFilteredReady as $detailCart) {
                if ($detailCart->produk) {
                    if ($detailCart->produk->id_produk === 11 || $detailCart->produk->id_produk === 12 || $detailCart->produk->id_produk === 13) {
                        $box10qty += $detailCart->jumlah_produk;
                    } else if ($detailCart->produk->id_produk === 14 || $detailCart->produk->id_produk === 15 || $detailCart->produk->id_produk === 16) {
                        $box10qty += $detailCart->jumlah_produk;
                    }
                } else {
                    if ($detailCart->hampers->produk) {
                        foreach ($detailCart->hampers->produk as $produk) {
                            if ($detailCart->produk->id_produk === 11 || $detailCart->produk->id_produk === 12 || $detailCart->produk->id_produk === 13) {
                                $box10qty += $detailCart->jumlah_produk;
                            } else if ($detailCart->produk->id_produk === 14 || $detailCart->produk->id_produk === 15 || $detailCart->produk->id_produk === 16) {
                                $box10qty += $detailCart->jumlah_produk;
                            }
                        }
                    }
                }
            }

            foreach ($transaction as $transaksi) {
                $insufficientIngredients = []; // Moved inside the loop
                $transactionRecipes = [];

                $looper = 1;

                // Sum the required quantities for each ingredient
                foreach ($transaction->cart->recap as $recap) {
                    if ($recap !== 0) {
                        $target = Produk::with('DetailResep.bahanBaku.unit')->find($looper);

                        foreach ($target->DetailResep as $resep) {
                            $bahan = $resep->bahanBaku->nama_bahan_baku;
                            $unit = $resep->bahanBaku->unit->nama_unit;
                            $quantity = $resep->jumlah * $recap;

                            if (array_key_exists($bahan, $transactionRecipes)) {
                                $transactionRecipes[$bahan]->quantity += $quantity;
                            } else {
                                $transactionRecipes[$bahan] = (object)[
                                    'bahan' => $bahan,
                                    'unit' => $unit,
                                    'quantity' => $quantity,
                                ];
                            }
                        }

                        $looper += 2;
                    }
                }
            }

            foreach ($transactionRecipes as $transactionRecipe) {
                $bahan = $transactionRecipe->bahan;
                $quantity = $transactionRecipe->quantity;
                $today = date('Y-m-d 00:00:00', strtotime(now()));

                $target_bahan = BahanBaku::where('nama_bahan_baku', $bahan)->first();
                $target_bahan->stok_bahan_baku -= $quantity;

                // echo "$target_bahan \n";

                $pengadaan_bahan_baku = PenggunaanBahanBaku::create([
                    'id_unit' => $target_bahan->id_unit,
                    'id_bahan_baku' => $target_bahan->id_bahan_baku,
                    'id_transaksi' => $transaction->id_transaksi,
                    'jumlah_penggunaan' => $quantity,
                    'tanggal_penggunaan' => $today,
                ]);

                $target_bahan->save();
            }

            $tasSpundbound = 1;

            $kemasanUsed = [
                ['type' => 'Box 20x20 cm', 'quantity' => $box20qty],
                ['type' => 'Box 20x10 cm', 'quantity' => $box10qty],
                ['type' => 'Box premium & kartu ucapan', 'quantity' => $premBoxqty],
                ['type' => 'Botol 1 Liter', 'quantity' => $botolQty],
                ['type' => 'Tas spundbound', 'quantity' => $tasSpundbound]
            ];

            foreach ($kemasanUsed as $kemasan) {
                $kemasan_bahan_baku = Kemasan::where('detail_kemasan', $kemasan['type'])->first();
                $kemasan_bahan_baku->stok_kemasan -= $kemasan['quantity'];
                $kemasan_bahan_baku->save();

                $penggunaan_kemasan = PenggunaanKemasan::create([
                    'id_kemasan' => $kemasan_bahan_baku->id_kemasan,
                    'jumlah_penggunaan' => $kemasan['quantity'],
                    'tanggal_penggunaan' => $today,
                ]);
            }

            // $transaction->id_status = 8;
            if ($transaction->id_pengambilan == 2) {
                $transaction->id_status = 10;
            } else {
                $transaction->id_status = 9;
            }
            $transaction->save();

            return response()->json([
                "status" => true,
                "message" => "Transaksi berhasil diproses",
                "data" => [$transaction, $transactionRecipes, $kemasanUsed]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal menghitung point: " . $e->getMessage(),
                "message" => "Failed to process transaction: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }
}
