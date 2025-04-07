<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    public function getAllPackages()
    {
        $packages = Package::all();
        return response()->json([
            'status' => 'success',
            'packages' => $packages
        ]);
    }
}
