<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getPackages(Request $request)
    {
        // Logic to get packages
        return response()->json([
            'status' => 'success',
            'data' => [
                // Example data
                ['id' => 1, 'name' => 'Basic Package', 'price' => 10],
                ['id' => 2, 'name' => 'Premium Package', 'price' => 20],
            ],
        ]);
    }
}
