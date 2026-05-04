@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Créer une catégorie</h1>
    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div>
            <label class="block font-medium mb-1">Nom</label>
            <input type="text" name="name" class="w-full border-gray-300 rounded" required>
        </div>
        <div>
            <label class="block font-medium mb-1">Catégorie parente (optionnel)</label>
            <select name="parent_id" class="w-full border-gray-300 rounded">
                <option value="">Aucune (catégorie principale)</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1">Description (optionnelle)</label>
            <textarea name="description" rows="3" class="w-full border-gray-300 rounded"></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Enregistrer</button>
    </form>
</div>
@endsection