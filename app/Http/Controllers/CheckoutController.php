<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le formulaire de checkout
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $user = auth()->user();

        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    /**
     * Traitement et enregistrement de la commande
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|min:10',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:stripe,cash_on_delivery',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Panier vide.');
        }

        // Calcul du total
        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        // Création de la commande
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_address' => $request->address . ', ' . $request->postal_code . ' ' . $request->city,
            'payment_method' => $request->payment_method,
        ]);

        // Enregistrement des articles
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Mise à jour du stock
            $product = \App\Models\Product::find($productId);
            if ($product) {
                $product->decrement('stock_quantity', $item['quantity']);
            }
        }

        // Vider le panier session
        session()->forget('cart');

        // Redirection selon le mode de paiement
        if ($request->payment_method === 'cash_on_delivery') {
            $order->update(['status' => 'processing']);
            return redirect()->route('orders.show', $order)
                ->with('success', 'Commande enregistrée. Paiement à la livraison.');
        }

        // Pour Stripe
        if ($request->payment_method === 'stripe') {
            return redirect()->route('payment.process', $order);
        }

        // Fallback (ne devrait pas arriver)
        return redirect()->route('orders.show', $order)
            ->with('success', 'Commande enregistrée.');
    }
}