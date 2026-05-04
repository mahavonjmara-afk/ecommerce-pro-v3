@extends('layouts.admin')

@section('content')
<div class="fade-in-up">
    <h1 class="text-2xl md:text-3xl font-bold mb-6">Tableau de bord</h1>

    <!-- Cartes statistiques responsive -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 p-4">
            <div class="text-blue-600 text-3xl mb-2">📦</div>
            <div class="text-2xl font-bold">{{ $totalProducts }}</div>
            <div class="text-gray-500">Produits</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 p-4">
            <div class="text-green-600 text-3xl mb-2">🛒</div>
            <div class="text-2xl font-bold">{{ $totalOrders }}</div>
            <div class="text-gray-500">Commandes</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 p-4">
            <div class="text-yellow-600 text-3xl mb-2">💰</div>
            <div class="text-2xl font-bold">{{ number_format($totalRevenue, 2) }} €</div>
            <div class="text-gray-500">Chiffre d'affaires</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 p-4">
            <div class="text-purple-600 text-3xl mb-2">👥</div>
            <div class="text-2xl font-bold">{{ $totalUsers }}</div>
            <div class="text-gray-500">Clients</div>
        </div>
    </div>

    <!-- Graphique des ventes mensuelles (responsive) -->
    <div class="bg-white rounded-xl shadow p-4 mb-8">
        <h2 class="text-xl font-semibold mb-4">Ventes mensuelles (€)</h2>
        <canvas id="salesChart" width="400" height="200" class="w-full h-auto" style="max-height: 300px;"></canvas>
    </div>

    <!-- Commandes récentes & Stock critique -->
    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Commandes récentes</h2>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                    <div class="flex flex-wrap justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium">#{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->name }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium">{{ number_format($order->total_amount, 2) }} €</span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Aucune commande récente.</p>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">Voir toutes les commandes →</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Stock critique</h2>
            <div class="space-y-3">
                @forelse($lowStockProducts as $product)
                    <div class="flex justify-between items-center">
                        <span>{{ $product->name }}</span>
                        <span class="text-red-600 font-bold">{{ $product->stock_quantity }} restant(s)</span>
                    </div>
                @empty
                    <p class="text-gray-500">Aucun produit en stock critique.</p>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">Gérer les stocks →</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const monthlySales = @json($monthlySales);
        const labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        const data = labels.map((_, index) => monthlySales[index + 1] || 0);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventes (€)',
                    data: data,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' },
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        title: { display: true, text: 'Montant (€)' }
                    }
                }
            }
        });
    });
</script>
@endpush