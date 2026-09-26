<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\ChildProduct;

class OrderController extends Controller
{
    // ...
    public function showOrders(Request $request)
    {
        if ($request->query('clear_cart') === '1') session()->forget('current_order');

        $statusesList = [];
        $apiUrl = config('services.api_serra.url') . '/api/statuses';
        $response = Http::acceptJson()->get($apiUrl);
        if ($response->successful()) $statusesList = $response->json();
        
        return view('orders', compact('statusesList'));
    }
    // ...
}