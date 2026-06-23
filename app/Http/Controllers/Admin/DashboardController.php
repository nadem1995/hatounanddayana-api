<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $data = Cache::remember('admin.dashboard', now()->addMinutes(10), function () {
            return [
                'stats' => [
                    'customers' => User::where('role', 'customer')->count(),
                    'categories' => Category::count(),
                    'products' => Product::count(),
                ],
            ];
        });

        return response()->json($data);
    }
}
