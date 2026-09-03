<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ChildProduct;

class OrderController extends Controller
{
    /**
     * Add a child product variant and its quantity to the current session order.
     */
    public function addToCurrentOrder(Request $request)
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

    /**
     * Remove a child product variant and its quantity from the current session order.
     */
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

    /**
     * Display the general list of orders
     */
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

    /**
     * Display the detailed items of a specific order (Wireframe 2)
     */
    public function showOrderDetails(Request $request, $id)
    {
        $date = now()->format('d/m/Y H:i');
        $taxableBasis = 0.00;
        $tax = 0.00;
        $total = 0.00;
        $conflicting_references = [];

        if ($id === 'current') {
            $currentOrder = $request->session()->get('current_order', []);
            
            if (empty($currentOrder)) {
                return view('invoice', [
                    'products' => collect(),
                    'isCurrent' => true,
                    'quantities' => [],
                    'code' => '-',
                    'status' => 'En curs',
                    'date' => $date,
                    'taxableBasis' => $taxableBasis,
                    'tax' => $tax,
                    'total' => $total,
                    'conflicting_references' => []
                ]);
            }

            $productIds = array_keys($currentOrder);
            $products = ChildProduct::with(['fatherProduct', 'unit', 'availability'])->whereIn('id', $productIds)->get();
            
            $orderAvailability = '-';
            $maxWeight = 0;

            foreach ($products as $product) {
                $weight = $product->availability->delay_weight ?? 0;
                if ($weight > $maxWeight) {
                    $maxWeight = $weight;
                    $orderAvailability = $product->availability->availability;
                }
            }
            $request->session()->put('order_availability', $orderAvailability);

            foreach ($products as $product) {
                $quantity = $currentOrder[$product->id]['quantity'];
                
                switch ($product->unit_id) {
                    case 1:
                    case 4:
                        $subtotalLinia = $product->current_unit_price * $quantity;
                        break;

                    case 2:
                        $metresTotals = ($product->length / 1000) * $quantity;
                        $subtotalLinia = $product->current_unit_price * $metresTotals;
                        break;

                    case 5:
                        $superficieM2 = ($product->width / 1000) * ($product->length / 1000);
                        $subtotalLinia = $product->current_unit_price * $superficieM2 * $quantity;
                        break;

                    case 3:
                        $volumM3 = ($product->width / 1000) * ($product->height / 1000) * ($product->length / 1000);
                        $subtotalLinia = $product->current_unit_price * $volumM3 * $quantity;
                        break;

                    default:
                        unset($currentOrder[$product->id]);
                        $request->session()->put('current_order', $currentOrder);
                        $conflicting_references[] = $product->reference;
                        continue 2;
                }
                
                $currentOrder[$product->id]['subtotal'] = round($subtotalLinia, 2);
                $taxableBasis += $subtotalLinia;
            }
            
            $request->session()->put('current_order', $currentOrder);

            $taxableBasis = round($taxableBasis, 2);
            $tax = round($taxableBasis * 0.21, 2);
            $total = round($taxableBasis * 1.21, 2);

            $request->session()->put('current_amount', $total);
            $request->session()->put('current_date', $date);

            return view('invoice', [
                'products'      => $products,
                'isCurrent' => true,
                'quantities'    => $currentOrder,
                'code'          => '-',
                'status'        => 'En curs',
                'date'          => $date,
                'taxableBasis'  => $taxableBasis,
                'tax'           => $tax,
                'total'         => $total,
                'orderAvailability'      => $orderAvailability,
                'conflicting_references' => $conflicting_references
            ]);
        }

        $order = Order::with(['childProducts.fatherProduct', 'childProducts.unit', 'childProducts.availability', 'status'])->findOrFail($id);

        $code = $order->code;
        $status = $order->status->status;
        $date = \Carbon\Carbon::parse($order->date)->format('d/m/Y H:i');

        $taxableBasis = round($order->childProducts->sum('pivot.subtotal'), 2);
        
        $tax = round($taxableBasis * 0.21, 2);
        
        $total = $order->total_amount;

        return view('invoice', [
            'order'                  => $order,
            'products'               => $order->childProducts,
            'isCurrent'          => false,
            'code'                   => $code,
            'status'                 => $status,
            'date'                   => $date,
            'taxableBasis'           => $taxableBasis,
            'tax'                    => $tax,
            'total'                  => $total,
            'conflicting_references' => $conflicting_references
        ]);

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