<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        // Solo el dueño de la orden (o un admin) puede verla
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

        if (empty($cartData)) {
            return back()->with('error', 'El carrito está vacío.');
        }

        try {
            $order = DB::transaction(function () use ($cartData) {
                $total = 0;
                $itemsToInsert = [];

                foreach ($cartData as $item) {
                    $product = Product::lockForUpdate()->find($item['id']);

                    if (!$product || !$product->active) {
                        throw new \Exception('Uno de los productos ya no está disponible.');
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("No hay suficiente stock de \"{$product->name}\". Solo quedan {$product->stock} unidades.");
                    }

                    $price = $product->discount_price ?? $product->price;
                    $total += $price * $item['quantity'];

                    $itemsToInsert[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'price'        => $price,
                        'quantity'     => $item['quantity'],
                    ];

                    // Descontar stock
                    $product->decrement('stock', $item['quantity']);
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
                }

                return $order;
            });

            return redirect()->route('orders.show', $order->code)
                ->with('success', '¡Boleta generada! Presenta este código al retirar tus productos.')
                ->with('clear_cart', true);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
