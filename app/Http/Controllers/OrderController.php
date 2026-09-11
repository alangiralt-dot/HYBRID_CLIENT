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
        $request->session()->forget('current_order');

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

    public function showOrders()
    {
        return view('orders');
    }

    public function showOrderDetails(Request $request, $id)
    {
        $date = now()->format('d/m/Y H:i');

        if ($id === 'current') {
            $currentOrder = $request->session()->get('current_order', []);

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
                    'error_message' => "",
                    'transformed_items' => [],
                    'id' => $id 
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
                    'error_message' => $response->json('message'),
                    'transformed_items' => [],
                    'id' => $id 
                ]);                
            }

            $apiData = $response->object();

            return view('invoice', [
                'products'      => $apiData->order_lines,
                'isCurrent'     => true,
                'code'          => '-',
                'status'        => 'En curs',
                'date'          => $date,
                'taxable_basis'  => $apiData->taxable_basis,
                'tax'           => $apiData->tax,
                'total'         => $apiData->total,
                'error_message' => "",
                'transformed_items' => $transformedItems,
                'id' => $id 
            ]);
        }
        return view('invoice', [
            'products' => [],
            'isCurrent' => false,
            'code' => '-',
            'status' => 'En curs',
            'date' => $date,
            'taxable_basis' => 0.00,
            'tax' => 0.00,
            'total' => 0.00,
            'error_message' => "",
            'transformed_items' => [],
            'id' => $id
        ]);
    }
    
    public function confirmOrder(Request $request)
    {
        // 1. Recollim el token enviat des de l'input ocult del formulari
        $accessToken = $request->input('access_token');
        
        // 2. Obtenim la comanda actual guardada a la sessió local de PHP
        $currentOrder = $request->session()->get('current_order', []);
        $request->session()->forget('current_order');
        // 3. Transformem l'array de la sessió al format JSON net de "order_lines" que demana l'API
        $orderLines = [];
        foreach ($currentOrder as $productId => $item) {
            $orderLines[] = [
                'id'       => (int) $productId,
                'quantity' => (int) $item['quantity']
            ];
        }

        // 4. Preparem l'URL base del teu backend central des de la configuració
        $apiBase = config('services.api_serra.url');

        // 5. Fem la petició POST cap a l'endpoint de l'API injectant el Bearer Token a la capçalera
        $response = Http::withToken($accessToken)
            ->post("{$apiBase}/api/orders", [
                'order_lines' => $orderLines
            ]);

        // 6. Si l'API respon amb un error (per token invàlid, falta de dades, etc.)
        if ($response->failed()) {
            return view('invoice', [
                'products' => [],
                'isCurrent' => true,
                'code' => '-',
                'status' => 'En curs',
                'date' => now()->format('d/m/Y H:i'),
                'taxable_basis' => 0.00,
                'tax' => 0.00,
                'total' => 0.00,
                'error_message' => $response->json('message') ?? 'Error amb el servidor central.'
            ]);                
        }

        // 7. S'HA CREAT AMB ÈXIT: Buidem completament la sessió del carretó local del client
        //$request->session()->forget('current_order');

        // 8. Redirigim finalment l'usuari cap al llistat de les seves comandes confirmades
        return $this->showOrders($request);
        // return redirect()->route('orders.showOrders');
        /*return view('invoice', [
                'products' => [],
                'isCurrent' => true,
                'code' => '-',
                'status' => 'En curs',
                'date' => now()->format('d/m/Y H:i'),
                'taxable_basis' => 0.00,
                'tax' => 0.00,
                'total' => 0.00,
                'error_message' => "S'ha creat la comanda amb id " . $response->json('order_id') . " en la base de dades de l'API."
            ]);*/
    }
}