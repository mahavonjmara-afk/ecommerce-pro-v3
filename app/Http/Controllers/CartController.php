<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Afficher le panier
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Ajouter un produit au panier
     */
    public function add(Request $request, Product $product)
{
    $quantity = $request->input('quantity', 1);

    if ($product->stock_quantity < $quantity) {
        return redirect()->back()->with('error', 'Stock insuffisant.');
    }

    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $newQuantity = $cart[$product->id]['quantity'] + $quantity;
        if ($newQuantity > $product->stock_quantity) {
            return redirect()->back()->with('error', 'Quantité totale demandée non disponible.');
        }
        $cart[$product->id]['quantity'] = $newQuantity;
    } else {
        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->final_price,
            'quantity' => $quantity,
            'image' => $product->image,
            'slug' => $product->slug,
        ];
    }

    session()->put('cart', $cart);
    return redirect()->back()->with('success', 'Produit ajouté au panier.');
}

    /**
     * Mettre à jour la quantité d'un produit dans le panier
     */
    public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Produit introuvable.');
        }

        $quantity = (int) $request->quantity;

        if ($quantity <= 0) {
            // Supprimer l'article
            unset($cart[$productId]);
        } elseif ($quantity <= $product->stock_quantity) {
            $cart[$productId]['quantity'] = $quantity;
        } else {
            return redirect()->back()->with('error', 'Stock insuffisant.');
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Panier mis à jour.');
    }

    /**
     * Supprimer un produit du panier
     */
    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }

    /**
     * Vider complètement le panier
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Panier vidé.');
    }
}