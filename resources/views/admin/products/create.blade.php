@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Créer un produit</h1>
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div>
            <label class="block font-medium mb-1">Nom</label>
            <input type="text" name="name" class="w-full border-gray-300 rounded" required>
        </div>
        <div>
            <label class="block font-medium mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full border-gray-300 rounded" required></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Prix (€)</label>
                <input type="number" name="price" step="0.01" class="w-full border-gray-300 rounded" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Prix promo (€)</label>
                <input type="number" name="sale_price" step="0.01" class="w-full border-gray-300 rounded">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1">Stock</label>
                <input type="number" name="stock_quantity" class="w-full border-gray-300 rounded" required>
            </div>
            <div>
                <label class="block font-medium mb-1">SKU</label>
                <input type="text" name="sku" class="w-full border-gray-300 rounded" required>
            </div>
        </div>
        <div>
            <label class="block font-medium mb-1">Catégorie</label>
            <select name="category_id" class="w-full border-gray-300 rounded" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1">Image principale</label>
            <input type="file" name="image" accept="image/*" class="w-full">
        </div>
        <div class="flex space-x-2">
            <label><input type="checkbox" name="is_active" value="1"> Actif</label>
            <label><input type="checkbox" name="is_featured" value="1"> À la une</label>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Enregistrer</button>
    </form>
</div>
@endsection