@extends('layouts.app')

@section('title', 'Nos produits - EcommercePro')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    {{-- Sidebar filtres responsive --}}
    <aside class="lg:w-1/4 bg-white p-6 rounded-xl shadow-sm h-fit sticky top-24 fade-in-up">
        <h3 class="font-bold text-lg mb-4 border-b pb-2">Filtrer</h3>
        <form method="GET" action="{{ route('products.index') }}" id="filterForm">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="mb-4">
                <label class="block font-medium mb-2">Catégories</label>
                <select name="category" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-2">Prix (€)</label>
                <div class="flex gap-2">
                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" 
                           class="w-1/2 rounded-lg border-gray-300 focus:ring-blue-500" onchange="this.form.submit()">
                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" 
                           class="w-1/2 rounded-lg border-gray-300 focus:ring-blue-500" onchange="this.form.submit()">
                </div>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-2">Trier par</label>
                <select name="sort" class="w-full rounded-lg border-gray-300" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récents</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                </select>
            </div>
            <button type="submit" class="btn-primary w-full">Appliquer</button>
            @if(request()->has('category') || request()->has('min_price') || request()->has('max_price') || request()->has('sort') || request()->has('search'))
                <a href="{{ route('products.index') }}" class="block text-center text-sm text-gray-500 mt-3 hover:underline">Réinitialiser</a>
            @endif
        </form>
    </aside>

    {{-- Grille produits --}}
    <div class="lg:w-3/4">
        <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Nos produits</h1>
            <span class="text-gray-500 text-sm">{{ $products->total() }} produit(s)</span>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl shadow">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-2 text-gray-500">Aucun produit trouvé.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="product-card fade-in-up" style="animation-delay: {{ $loop->iteration * 0.05 }}s;">
                        <a href="{{ route('products.show', $product->slug) }}">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover transition duration-500 hover:scale-105">
                            @else
                                <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-400">Image</div>
                            @endif
                        </a>
                        <div class="p-4">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-blue-600">
                                <h3 class="font-semibold text-lg line-clamp-1">{{ $product->name }}</h3>
                            </a>
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex justify-between items-center mt-3">
                                <div>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="text-gray-400 line-through text-sm">{{ number_format($product->price, 2) }}€</span>
                                        <span class="text-red-600 font-bold ml-1">{{ number_format($product->sale_price, 2) }}€</span>
                                    @else
                                        <span class="text-blue-600 font-bold">{{ number_format($product->price, 2) }}€</span>
                                    @endif
                                </div>
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1.5 rounded-lg transition transform hover:scale-105">Ajouter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection