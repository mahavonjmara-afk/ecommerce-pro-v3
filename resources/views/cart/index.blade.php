@extends('layouts.app')

@section('title', 'Mon panier')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 fade-in-up">Mon panier</h1>

    @if(empty($cart))
        <div class="bg-white rounded-xl shadow p-8 text-center fade-in-up">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 21v-4"></path>
            </svg>
            <p class="mt-4 text-gray-600">Votre panier est vide.</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block btn-primary">Découvrir nos produits</a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden fade-in-up">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($cart as $id => $item)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" class="w-10 h-10 object-cover rounded">
                                        @endif
                                        <a href="{{ route('products.show', $item['slug']) }}" class="text-gray-800 hover:text-blue-600 font-medium">{{ $item['name'] }}</a>
                                    </div>
                                </td>
                                <td class="px-4 py-4">{{ number_format($item['price'], 2) }} €</td>
                                <td class="px-4 py-4">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-20 border-gray-300 rounded-md text-center">
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 transition">Mettre à jour</button>
                                    </form>
                                </td>
                                <td class="px-4 py-4 font-semibold">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                                <td class="px-4 py-4">
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-right font-bold">Total</td>
                            <td class="px-4 py-4 font-bold text-blue-600">{{ number_format($total, 2) }} €</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap justify-between gap-3">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn-secondary">Vider le panier</button>
            </form>
            <a href="{{ route('checkout.index') }}" class="btn-primary">Passer la commande</a>
        </div>
    @endif
</div>
@endsection