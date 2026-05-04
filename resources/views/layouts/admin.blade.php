<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - EcommercePro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div x-data="{ sidebarOpen: false }" class="flex flex-col md:flex-row min-h-screen">
        <!-- Mobile menu button -->
        <div class="md:hidden bg-gray-800 text-white p-4 flex justify-between items-center">
            <span class="text-xl font-bold">Admin</span>
            <button @click="sidebarOpen = !sidebarOpen" class="focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <!-- Sidebar (mobile: off-canvas) -->
        <aside class="bg-gray-800 text-white w-64 flex-shrink-0 fixed inset-y-0 left-0 z-30 transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="p-4 text-xl font-bold border-b border-gray-700">EcommercePro Admin</div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 hover:bg-gray-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="block py-2 px-4 hover:bg-gray-700 transition {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">Produits</a>
                <a href="{{ route('admin.categories.index') }}" class="block py-2 px-4 hover:bg-gray-700 transition {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">Catégories</a>
                <a href="{{ route('admin.orders.index') }}" class="block py-2 px-4 hover:bg-gray-700 transition {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">Commandes</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full text-left block py-2 px-4 hover:bg-gray-700 transition">Déconnexion</button>
                </form>
            </nav>
        </aside>

        <!-- Overlay pour fermer sidebar sur mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden" style="display: none;"></div>

        <!-- Main content -->
        <main class="flex-1 p-4 md:p-6 overflow-y-auto fade-in">
            @yield('content')
        </main>
    </div>

    <!-- Scripts : Alpine.js + Chart.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>
</html>