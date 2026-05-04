<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Pas de constructeur avec middleware()
    // La protection par le middleware 'admin' est déjà assurée dans les routes (web.php)

    public function index()
    {
        // Statistiques
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Commandes récentes
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        // Produits en rupture de stock (stock < 5)
        $lowStockProducts = Product::where('stock_quantity', '<', 5)->get();

        // Ventes mensuelles pour graphique
        $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', date('Y'))
            ->where('status', 'delivered')
            ->groupBy('month')
            ->pluck('total', 'month');

        return view('admin.dashboard.index', compact(
            'totalOrders', 'totalRevenue', 'totalProducts', 'totalUsers',
            'pendingOrders', 'recentOrders', 'lowStockProducts', 'monthlySales'
        ));
    }
}