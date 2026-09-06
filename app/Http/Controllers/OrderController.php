<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\ChildProduct;

class OrderController extends Controller
{
    /**
     * Neteja completament el carretó i les dades de l'API de la sessió de PHP.
     */
    public function clearCartSession(Request $request)
    {
        // Esborrem en bloc les dues claus identificades al depurador
        $request->session()->forget([
            'current_order',
            'request_preview_data'
        ]);

        // Responem de forma asíncrona amb un codi d'èxit 200 OK
        return response()->json([
            'status' => 'success',
            'message' => 'El carretó i les dades de la sessió de PHP s\'han esborrat correctament.'
        ], 200);
    }

    public function updateQuantityInCurrentOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer',
        ]);

        $productId = (int) $request->input('product_id');
        $quantity  = (int) $request->input('quantity');

        $currentOrder = $request->session()->get('current_order', []);

        if (array_key_exists($productId, $currentOrder)) {
            $currentOrder[$productId]['quantity'] += $quantity;
        } else {
            $currentOrder[$productId] = [
                'quantity' => $quantity,
                'subtotal' => 0.00
            ];
        }

        $request->session()->put('current_order', $currentOrder);

        return response()->json([
            'status'  => 'success',
            'message' => 'Product successfully added to the current order.'
        ], 200);
    }

    public function removeFromCurrentOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $productId = (int) $request->input('product_id');
        $currentOrder = $request->session()->get('current_order', []);

        if (array_key_exists($productId, $currentOrder)) {
            unset($currentOrder[$productId]);
        }

        $request->session()->put('current_order', $currentOrder);

        return response()->json([
            'status'  => 'success',
            'message' => 'Product successfully removed.'
        ], 200);
    }

    public function showOrders(Request $request)
    {
        $customerId = \Illuminate\Support\Facades\Auth::user()->customer_id;
        $confirmedOrders = Order::with('status') 
            ->where('customer_id', $customerId)
            ->orderBy('date', 'desc')
            ->get();

        return view('orders', [
            'confirmedOrders' => $confirmedOrders
        ]);
    }

    public function showOrderDetails(Request $request, $id)
    {
        if ($id === 'current') {
            $currentOrder = $request->session()->get('current_order', []);
            $date = now()->format('d/m/Y H:i');

            if (empty($currentOrder)) {
                return view('invoice', [
                    'products' => [],
                    'isCurrent' => true,
                    'code' => '-',
                    'status' => 'En curs',
                    'date' => $date,
                    'taxable_basis' => 0.00,
                    'tax' => 0.00,
                    'total' => 0.00,
                    'error_message' => ""
                ]);
            }

            $transformedItems = [];
            foreach ($currentOrder as $productId => $item) {
                $transformedItems[] = [
                    'id'       => (int) $productId,
                    'quantity' => (int) $item['quantity']
                ];
            }

            $apiBase = config('services.api_serra.url');
            $response = Http::post("{$apiBase}/api/orders/previews", [
                'items' => $transformedItems
            ]);

            if ($response->failed()) {
                return view('invoice', [
                    'products' => [],
                    'isCurrent' => true,
                    'code' => '-',
                    'status' => 'En curs',
                    'date' => $date,
                    'taxable_basis' => 0.00,
                    'tax' => 0.00,
                    'total' => 0.00,
                    'error_message' => $response->json('message')
                ]);                
            }

            //$apiData = $response->json();
            $apiData = $response->object();

            $request->session()->put('request_preview_data', $apiData);

            return view('invoice', [
                'products'      => $apiData->order_lines,
                'isCurrent'     => true,
                'code'          => '-',
                'status'        => 'En curs',
                'date'          => $date,
                'taxable_basis'  => $apiData->taxable_basis,
                'tax'           => $apiData->tax,
                'total'         => $apiData->total,
                'error_message' => ""
            ]);
            /*return view('invoice', [
                'products'      => $products,
                'isCurrent' => true,
                'quantities'    => $currentOrder,
                'code'          => '-',
                'status'        => 'En curs',
                'date'          => $date,
                'taxableBasis'  => $taxableBasis,
                'tax'           => $tax,
                'total'         => $total,
                'orderAvailability'      => $orderAvailability, // es pot eliminar
                'conflicting_references' => $conflicting_references
            ]);*/

        }
    }

    public function confirmOrder(Request $request)
    {
        $currentOrder      = $request->session()->get('current_order', []);
        $orderAvailability = $request->session()->get('order_availability', '-');
        $totalAmount       = $request->session()->get('current_amount', 0.00);
        $currentDate       = $request->session()->get('current_date');

        if (empty($currentOrder)) {
            return redirect()->back()->with('error', 'No pots confirmar una comanda buida.');
        }

        $order = new \App\Models\Order();
        $order->customer_id        = \Illuminate\Support\Facades\Auth::user()->customer_id;;
        $order->status_id          = 1;
        $order->date               = $currentDate ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $currentDate) : now();
        $order->order_availability = $orderAvailability;
        $order->total_amount       = $totalAmount;
        $order->save();

        foreach ($currentOrder as $productId => $item) {
            $product = \App\Models\ChildProduct::find($productId);

            if ($product) {
                $order->childProducts()->attach($productId, [
                    'discount'        => 0,
                    'quantity'        => $item['quantity'],
                    'sale_unit_price' => $product->current_unit_price,
                    'subtotal'        => $item['subtotal']
                ]);
            }
        }

        $request->session()->forget(['current_order', 'order_availability', 'current_amount', 'current_date']);

        return redirect()->route('orders.showOrders');
    }
}