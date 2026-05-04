<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $item->product->name],
                    'unit_amount' => $item->price * 100, // en centimes
                ],
                'quantity' => $item->quantity,
            ];
        }

        $checkout = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success', $order) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel', $order),
        ]);

        return redirect($checkout->url);
    }

    public function success(Request $request, Order $order)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = Session::retrieve($request->get('session_id'));

        if ($session->payment_status === 'paid') {
            // Enregistrer le paiement
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'method' => 'stripe',
                'status' => 'completed',
                'transaction_id' => $session->payment_intent,
                'paid_at' => now(),
            ]);

            $order->update(['status' => 'processing']);

            // Vider le panier session si nécessaire
            session()->forget('cart');

            return redirect()->route('orders.show', $order)->with('success', 'Paiement accepté. Merci pour votre commande !');
        }

        return redirect()->route('checkout.index')->with('error', 'Le paiement a échoué. Veuillez réessayer.');
    }

    public function cancel(Order $order)
    {
        return redirect()->route('checkout.index')->with('error', 'Paiement annulé.');
    }
}