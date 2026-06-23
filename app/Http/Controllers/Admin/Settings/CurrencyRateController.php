<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    /**
     * Show current rate
     */
    public function show()
    {
        $rate = CurrencyRate::latest()->first();

        return response()->json([
            'SYP_rate' => $rate?->SYP_rate ?? 0,
        ], 200);
    }

    /**
     * Set or update the rate
     */
    public function update(Request $request)
    {
        $request->validate([
            'SYP_rate' => 'required|numeric|min:0',
        ]);

        // Update or create a new rate
        $rate = CurrencyRate::updateOrCreate(
            ['id' => 1], // You can keep only one row
            ['SYP_rate' => $request->SYP_rate]
        );

        return response()->json([
            'message' => __('messages.updated_successfully'),
            'SYP_rate' => $rate->SYP_rate,
        ], 200);
    }
}
