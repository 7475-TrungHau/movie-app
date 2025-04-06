<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use Exception;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'package', 'payment'])
            ->orderBy("created_at", "desc")->paginate(10);
        $users = User::all();
        $packages = Package::all();
        $payments = Payment::all();
        return view('admin.subscription.index', compact('subscriptions', 'users', 'packages', 'payments'));
    }

    public function show($id)
    {
        $subscription = Subscription::with(['user', 'package', 'payment'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'subscription' => $subscription
        ]);
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:active,expired,canceled',
            'end_date' => 'required|date',
        ]);
        $subscription->update($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật trạng thái đăng ký thành công'
        ]);
    }

    public function create()
    {
        $users = User::all();
        $packages = Package::where('status', 'active')->get();
        return response()->json([
            'status' => 'success',
            'users' => $users,
            'packages' => $packages
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'package_id' => 'required|exists:packages,id',

            ]);
            $validated['status'] = 'active';
            $validated['start_date'] = now();
            $package = Package::findOrFail($validated['package_id']);
            $validated['end_date'] = now()->addDays($package->duration_days);


            Subscription::create($validated);
            return redirect()->route('admin.subscription.index')->with('success', 'Tạo mới đăng ký thành công');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra khi tạo mới đăng ký: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $subscription = Subscription::with(['user', 'package', 'payment'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'subscription' => $subscription
        ]);
    }


    public function destroy($id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa đăng ký thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xóa đăng ký: ' . $e->getMessage()
            ]);
        }
    }
}
