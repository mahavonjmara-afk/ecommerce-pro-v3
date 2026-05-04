@extends('layouts.app')

@section('title', 'Commande n°' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Commande n°{{ $order->order_number }}</h1>
        <span class="px-3 py-1 rounded-full text-sm
            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
            @elseif($order->status == 'delivered') bg-green-100 text-green-800
            @else bg-red-100 text-red-800 @endif">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="font-semibold text-lg mb-3">Détails des articles</h2>
        <div class="space-y-3">
            @foreach($order->items as $item)
                <div class="flex justify-between items-center border-b pb-2">
                    <div>
                        <p class="font-medium">{{ $item->product->name }}</p>
                        <p class="text-sm text-gray-500">Quantité : {{ $item->quantity }}</p>
                    </div>
                    <p class="font-bold">{{ number_format($item->price * $item->quantity, 2) }} €</p>
                </div>
            @endforeach
        </div>
        <div class="mt-4 text-right">
            <p class="text-xl font-bold">Total : {{ number_format($order->total_amount, 2) }} €</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-lg mb-3">Adresse de livraison</h2>
            <p>{{ $order->shipping_address }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-lg mb-3">Paiement</h2>
            <p>Méthode : {{ $order->payment_method }}</p>
            @if($order->payment)
                <p>Statut : {{ ucfirst($order->payment->status) }}</p>
                @if($order->payment->paid_at)
                    <p>Payé le : {{ $order->payment->paid_at->format('d/m/Y H:i') }}</p>
                @endif
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">← Retour à mes commandes</a>
    </div>
</div>
@endsection