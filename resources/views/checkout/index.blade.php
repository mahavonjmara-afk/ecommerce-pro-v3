@extends('layouts.app')

@section('title', 'Validation de la commande')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 fade-in-up">Finaliser ma commande</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-2/3 bg-white rounded-xl shadow p-6 fade-in-up">
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <h2 class="text-xl font-semibold mb-4">Informations de livraison</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Adresse complète</label>
                    <textarea name="address" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>{{ old('address', auth()->user()->address) }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Ville</label>
                        <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Code postal</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', auth()->user()->postal_code) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Téléphone</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full border-gray-300 rounded-lg focus:ring-blue-500" required>
                </div>

                <h2 class="text-xl font-semibold mt-6 mb-4">Mode de paiement</h2>
                <div class="space-y-2 mb-6">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="payment_method" value="cash_on_delivery" checked class="mr-3">
                        <span>Paiement à la livraison (espèces / carte)</span>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="payment_method" value="stripe" class="mr-3">
                        <span>Carte bancaire (Stripe)</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-lg">Confirmer la commande</button>
            </form>
        </div>

        <div class="lg:w-1/3">
            <div class="bg-gray-50 rounded-xl shadow p-6 sticky top-24 fade-in-up">
                <h2 class="text-xl font-semibold mb-4">Récapitulatif</h2>
                <div class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                    @foreach($cart as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                            <span>{{ number_format($item['price'] * $item['quantity'], 2) }} €</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t pt-3 mt-3">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-blue-600">{{ number_format($total, 2) }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection