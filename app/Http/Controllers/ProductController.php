<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Affiche la liste des produits (catalogue)
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtre par prix min
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filtre par prix max
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Affiche un produit spécifique
     */
    public function show(Product $product)
    {
        // Vérifier si le produit est actif
        if (!$product->is_active) {
            abort(404);
        }

        // Produits similaires (même catégorie)
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    /**
     * Produits par catégorie
     */
    public function byCategory(Category $category)
    {
        $products = $category->products()->where('is_active', true)->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Recherche rapide (AJAX)
     */
    public function searchAjax(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->q . '%')
            ->where('is_active', true)
            ->limit(5)
            ->get(['id', 'name', 'price', 'image']);

        return response()->json($products);
    }
}