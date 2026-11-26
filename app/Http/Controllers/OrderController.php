<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        abort_unless(auth()->id() === $order->user_id || (auth()->check() && auth()->user()->isAdmin()), 403);

        $order->load('items');
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|json',
        ]);

        $cartData = json_decode($request->cart, true);

        if (empty($cartData) || !is_array($cartData)) {
            return back()->with('error', 'El carrito está vacío.');
        }

        try {
            $total = 0;
            $itemsToInsert = [];

            foreach ($cartData as $item) {
                $productId = $item['id'] ?? null;
                $quantity = (int) ($item['quantity'] ?? 0);

                if (!$productId || $quantity < 1) {
                    throw new \Exception('Uno de los productos en el carrito tiene datos inválidos.');
                }

                $product = Product::find($productId);

                if (!$product || !$product->active) {
                    throw new \Exception('Uno de los productos ya no está disponible.');
                }

                if ((int) $product->stock < $quantity) {
                    throw new \Exception("No hay suficiente stock de \"{$product->name}\". Solo quedan {$product->stock} unidades.");
                }

                $price = $product->discount_price ?? $product->price;
                $total += (float) $price * $quantity;

                $itemsToInsert[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $price,
                    'quantity'     => $quantity,
                ];
            }

            $order = Order::create([
                'user_id'        => auth()->id(),
                'customer_name'  => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => auth()->user()->phone ?? null,
                'total'          => $total,
                'status'         => 'pending',
            ]);

            foreach ($itemsToInsert as $item) {
                $order->items()->create($item);

                DB::table('products')
                    ->where('id', $item['product_id'])
                    ->update([
                        'stock' => DB::raw('stock - ' . (int) $item['quantity']),
                    ]);
            }

            return redirect()->route('orders.show', $order->code)
                ->with('success', '¡Boleta generada! Presenta este código al retirar tus productos.')
                ->with('clear_cart', true);
        } catch (\Throwable $e) {
            Log::error('Checkout failed: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'previous' => $e->getPrevious()?->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }
}