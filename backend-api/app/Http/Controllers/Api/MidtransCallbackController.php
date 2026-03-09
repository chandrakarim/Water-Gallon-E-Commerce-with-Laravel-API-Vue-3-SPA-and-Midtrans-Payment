<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MidtransCallbackService;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request, MidtransCallbackService $service)
    {
        try {
            $service->handle($request->all());

            return response()->json([
                'message' => 'Callback received'
            ]);
        } catch (\Exception $e) {

            \Log::error('MIDTRANS ERROR: ' . $e->getMessage());

            return response()->json([
                'error' => 'Callback failed'
            ], 500);
        }
    }
}
