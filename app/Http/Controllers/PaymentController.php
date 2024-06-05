<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi; // Make sure to use the correct model

class PaymentController extends Controller
{
    /**
     * Uploads proof of payment for a transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadProof(Request $request)
    {
        $validatedData = $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Limit file types and size
            'id_transaksi' => 'required|integer',
        ]);

        $file = $request->file('payment_proof');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads');
        $file->move($destinationPath, $filename);

        // Update the transaction with the file path or handle accordingly
        $transaksi = Transaksi::find($validatedData['id_transaksi']);
        if ($transaksi) {
            $transaksi->bukti_pembayaran = 'uploads/' . $filename; // Adjust path according to your needs
            $transaksi->save();

            return response()->json([
                'message' => 'Proof of payment uploaded successfully',
                'filename' => $filename
            ], 200);
        } else {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    }
}