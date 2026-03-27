<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Tenant;
use Carbon\Carbon;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Tangkap Payload Midtrans
        $payload = $request->getContent();
        $notification = json_decode($payload);

        // 2. VERIFIKASI KEAMANAN (SHA512) - MENCEGAH HACKER!
        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if ($notification->signature_key !== $validSignatureKey) {
            return response()->json(['message' => 'Invalid Signature! Serangan ditolak.'], 403);
        }

        // 3. Cari Transaksi di Database
        $transaction = Transaction::where('order_id', $notification->order_id)->first();
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // 4. Update Status Pembayaran
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? '';

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'pending';
            } else {
                // PEMBAYARAN SUKSES!
                if ($transaction->status !== 'success') {
                    $transaction->status = 'success';
                    $transaction->payment_type = $notification->payment_type;
                    $transaction->paid_at = now();
                    $transaction->save();

                    // --- LOGIC PERPANJANG MASA AKTIF TENANT ---
                    $tenant = $transaction->tenant;
                    $package = $transaction->package;

                    // Cek: Apakah dia bayar saat trial sudah habis, atau bayar untuk numpuk sisa hari?
                    $currentExpiry = Carbon::parse($tenant->trial_until);
                    if ($currentExpiry->isPast()) {
                        // Jika sudah telat/expired, hitung dari hari ini
                        $newExpiry = now()->addDays($package->duration_days);
                    } else {
                        // Jika belum habis, tambahkan dari sisa masa aktifnya
                        $newExpiry = $currentExpiry->addDays($package->duration_days);
                    }

                    // Buka blokir otomatis (is_active = 1) dan set trial_until baru
                    $tenant->update([
                        'trial_until' => $newExpiry,
                        'is_active' => 1
                    ]);
                }
            }
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->status = 'failed';
            $transaction->save();
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
            $transaction->save();
        }

        return response()->json(['message' => 'Callback Handled']);
    }
}