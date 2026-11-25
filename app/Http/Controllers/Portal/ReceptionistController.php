<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionistController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('portal.receptionist.index', compact('orders', 'status'));
    }

    public function deliver(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Esta orden ya no está pendiente.');
        }

        try {
            $order->update([
                'status'       => 'delivered',
                'delivered_at' => now(),
                'delivered_by' => auth()->id(),
            ]);

            return back()->with('success', "Orden {$order->code} marcada como retirada correctamente.");
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al entregar la orden: ' . $e->getMessage());
        }
    }
}
