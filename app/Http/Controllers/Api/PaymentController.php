<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use App\Models\Payment;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $vnp_TmnCode = "41HRA5GR";
    private $vnp_HashSecret = "QPAZIFT09WWXXHSZTU1RZ6X8P2W95PJR";
    private $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    private $vnp_Returnurl = "http://localhost:8000/api/payment/return";

    public function createVnpayPayment(Request $request)
    {
        try {
            $package = Package::findOrFail($request->package_id);
            $vnp_TxnRef = time() . "_" . $package->id;
            $vnp_Amount = $package->price * 100;
            $vnp_Locale = 'vn';

            // Tạo payment trước
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $payment = Payment::create([
                'user_id' => $user->id,
                'subscription_id' => null,
                'amount' => $package->price,
                'payment_method' => 'vnpay',
                'payment_date' => now(),
                'status' => 'pending'
            ]);
            if (!$payment) {
                return response()->json(['error' => 'Payment creation failed'], 500);
            }

            // Lưu payment_id vào vnp_OrderInfo
            $vnp_OrderInfo = "Thanh toán gói cước {$package->name}|PaymentID:{$payment->id}";
            $vnp_IpAddr = request()->ip();
            $vnp_OrderType = 'billpayment';
            $vnp_BankCode = $request->bank_code ?? 'NCB';

            $inputData = [
                "vnp_Version" => "2.0.0",
                "vnp_Command" => "pay",
                "vnp_TmnCode" => $this->vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_BankCode" => $vnp_BankCode,
                "vnp_CurrCode" => 'VND',
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_Locale" => $vnp_Locale,
                "vnp_ReturnUrl" => $this->vnp_Returnurl,
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_CreateDate" => date('YmdHis'),
            ];

            if (!empty($vnp_BankCode)) {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            ksort($inputData);
            $query = http_build_query($inputData, '', '&');
            $hashData = urldecode($query);
            $vnp_SecureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
            $vnp_Url = $this->vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

            return response()->json([
                'status' => 'success',
                'url' => $vnp_Url,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment creation failed: ' . $e->getMessage()], 500);
        }
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_SecureHash = $request->get('vnp_SecureHash');
        if (!$vnp_SecureHash) {
            return response()->json(['error' => 'Missing vnp_SecureHash'], 400);
        }

        $inputData = $request->all();
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        // Tạo chuỗi hashData theo cách thủ công để đảm bảo đúng định dạng
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&';
            }
            $hashData .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        // Ghi log để debug
        Log::info('VNPay Return Data:', $inputData);
        Log::info('Hash Data:', [$hashData]);
        Log::info('Calculated Secure Hash:', [$secureHash]);
        Log::info('VNPay Secure Hash:', [$vnp_SecureHash]);

        if ($secureHash !== $vnp_SecureHash) {
            return response()->json(['error' => 'Invalid secure hash'], 400);
        }

        try {
            $txnRef = $request->get('vnp_TxnRef');
            $packageId = explode("_", $txnRef)[1];
            $orderInfo = $request->get('vnp_OrderInfo');
            $orderInfoParts = explode('|', $orderInfo);
            if (count($orderInfoParts) < 2) {
                return response()->json(['error' => 'Invalid vnp_OrderInfo format'], 400);
            }
            $paymentIdPart = explode(':', $orderInfoParts[1]);
            if (count($paymentIdPart) < 2) {
                return response()->json(['error' => 'Invalid PaymentID in vnp_OrderInfo'], 400);
            }
            $paymentId = $paymentIdPart[1];

            Log::info('Payment ID from vnp_OrderInfo:', [$paymentId]);

            if (!$paymentId) {
                return response()->json(['error' => 'Payment not found'], 404);
            }

            $payment = Payment::findOrFail($paymentId);
            if ($request->get('vnp_ResponseCode') == '00') {
                $package = Package::findOrFail($packageId);

                $subscription = Subscription::updateOrCreate(
                    ['user_id' => $payment->user_id],
                    [
                        'package_id' => $package->id,
                        'start_date' => now(),
                        'end_date' => now()->addDays($package->duration_days),
                        'status' => 'active'
                    ]
                );
                $payment->update([
                    'subscription_id' => $subscription->id,
                    'status' => 'success'
                ]);

                session()->forget('payment_id');
                return redirect('http://localhost:5173/payment-success?subscription_id=' . $subscription->id);
            } else {
                $payment->update([
                    'status' => 'failed'
                ]);
                session()->forget('payment_id');
                return redirect('http://localhost:5173/payment-failed?error=' . $request->get('vnp_ResponseCode'));
            }
        } catch (\Exception $e) {
            Log::error('VNPay return error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 500);
        }
    }
    public function getPackages()
    {
        $packages = Package::where('is_active', true)->get();
        $packages->transform(function ($package) {
            $isPopular = $package->name === 'VIP' ? true : false;
            return [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'price' => $package->price,
                'duration_days' => $package->duration_days,
                'features' => $package->features,
                'is_popular' => $isPopular,
            ];
        });
        return response()->json($packages);
    }

    public function getPayment($subscription_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $payment = Payment::where('user_id', $user->id)->where('subscription_id', $subscription_id)->with('subscription')->first();
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
        $payment->package_name = $payment->subscription->package->id;
        return response()->json($payment);
    }
}
