<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Package;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'subscription.package'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.payment.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with(['user', 'subscription.package'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'payment' => $payment
        ]);
    }

    public function edit($id)
    {
        $payment = Payment::with(['user', 'subscription.package'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'payment' => $payment
        ]);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:success,pending,failed,refunded',
        ]);

        $payment->update($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật trạng thái thanh toán thành công'
        ]);
    }

    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa thanh toán thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xóa thanh toán: ' . $e->getMessage()
            ]);
        }
    }
}
