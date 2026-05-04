@extends('layouts.app')

@section('title', $product->name . ' - EcommercePro')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">
            {{-- Colonne image --}}
            <div class="md:w-1/2 p-6">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg">
                        Image non disponible
                    </div>
                @endif
                
                {{-- Galerie d'images supplémentaires (optionnel) --}}
                @if($product->gallery && count(json_decode($product->gallery, true)) > 0)
                    <div class="flex gap-2 mt-4">
                        @foreach(json_decode($product->gallery, true) as $img)
                            <img src="{{ asset('storage/' . $img) }}" class="w-20 h-20 object-cover rounded cursor-pointer border hover:border-blue-500">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Colonne infos --}}
            <div class="md:w-1/2 p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                <div class="flex items-center mb-4">
                    {{-- Étoiles (moyenne des notes) --}}
                    <div class="flex text-yellow-400">
                        @php $avgRating = $product->reviews->avg('rating') ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($avgRating))
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-500 text-sm ml-2">({{ $product->reviews->count() }} avis)</span>
                </div>

                <p class="text-gray-700 mb-4">{{ $product->description }}</p>

                {{-- Prix --}}
                <div class="mb-4">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="text-3xl font-bold text-red-600">{{ number_format($product->sale_price, 2) }} €</span>
                        <span class="text-gray-400 line-through ml-2">{{ number_format($product->price, 2) }} €</span>
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 ml-2 rounded">Promotion</span>
                    @else
                        <span class="text-3xl font-bold text-blue-600">{{ number_format($product->price, 2) }} €</span>
                    @endif
                </div>

                {{-- Stock --}}
                <div class="mb-4">
                    @if($product->stock_quantity > 0)
                        <span class="text-green-600">✔ En stock ({{ $product->stock_quantity }} disponibles)</span>
                    @else
                        <span class="text-red-600">✘ Rupture de stock</span>
                    @endif
                </div>

                {{-- Formulaire ajout panier --}}
                @if($product->stock_quantity > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center gap-3 mb-6">
                        @csrf
                        <label class="font-medium">Quantité :</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-20 border-gray-300 rounded-md">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                            Ajouter au panier
                        </button>
                    </form>
                @else
                    <button disabled class="bg-gray-400 text-white px-6 py-2 rounded-lg cursor-not-allowed">Indisponible</button>
                @endif

                {{-- Catégorie --}}
                <div class="text-sm text-gray-500">
                    Catégorie : <a href="{{ route('categories.show', $product->category) }}" class="text-blue-600 hover:underline">{{ $product->category->name }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Produits similaires --}}
    @if($related->count())
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Produits similaires</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($related as $rel)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition">
                        <a href="{{ route('products.show', $rel->slug) }}">
                            <img src="{{ asset('storage/' . $rel->image) }}" class="w-full h-40 object-cover rounded-t-lg">
                            <div class="p-3">
                                <h3 class="font-semibold">{{ $rel->name }}</h3>
                                <p class="text-blue-600 font-bold">{{ number_format($rel->final_price, 2) }} €</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection