@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Commande #{{ $order->order_number }}</h1>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" class="border rounded px-2 py-1" onchange="this.form.submit()">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Traitement</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
            </form>
        </div>

        <div><strong>Client :</strong> {{ $order->user->name }} ({{ $order->user->email }})</div>
        <div><strong>Adresse :</strong> {{ $order->shipping_address }}</div>
        <div><strong>Paiement :</strong> {{ $order->payment_method }}</div>
        <div><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>

        <h2 class="font-semibold text-lg mt-6 mb-2">Articles</h2>
        <table class="w-full border-t">
            @foreach($order->items as $item)
                <tr>
                    <td class="py-2">{{ $item->product->name }}</td>
                    <td class="py-2">x{{ $item->quantity }}</td>
                    <td class="py-2 text-right">{{ number_format($item->price * $item->quantity, 2) }} €</td>
                </tr>
            @endforeach
            <tr class="border-t font-bold">
                <td colspan="2" class="py-2">Total</td>
                <td class="py-2 text-right">{{ number_format($order->total_amount, 2) }} €</td>
            </tr>
        </table>
    </div>
</div>
@endsection