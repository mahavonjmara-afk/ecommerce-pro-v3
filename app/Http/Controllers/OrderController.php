<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    // Plus de constructeur avec middleware()

    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $order->load('items.product', 'payment');
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Cette commande ne peut plus être annulée.');
        }
        foreach ($order->items as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }
        $order->update(['status' => 'cancelled']);
        return redirect()->route('orders.index')->with('success', 'Commande annulée.');
    }
}