<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Ecommerce Pro'))</title>
    <!-- Google Fonts + fallback -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Animation personnalisée fade-in-up */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Transition douce pour tous les boutons et liens */
        .transition-smooth {
            transition: all 0.2s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased flex flex-col min-h-screen">
    <!-- Navbar responsive -->
    <nav class="bg-white shadow-md sticky top-0 z-50 transition-shadow duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 md:py-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl md:text-3xl font-extrabold text-blue-600 hover:text-blue-700 transition-colors">
                    EcommercePro
                </a>

                <!-- Barre de recherche (cachée sur très petit, visible sur tablette+) -->
                <div class="hidden md:block flex-1 max-w-md mx-4">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Rechercher..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
                                   value="{{ request('search') }}">
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </form>
                </div>

                <!-- Panier + menu utilisateur -->
                <div class="flex items-center space-x-3 md:space-x-4">
                    <!-- Panier avec badge -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 21v-4"></path>
                        </svg>
                        @php $cartCount = session()->has('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-3 py-1.5 md:px-4 md:py-2 rounded-full hover:bg-blue-700 transition transform hover:scale-105">Inscription</a>
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 focus:outline-none">
                                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 transform transition-all duration-200 origin-top-right">
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mes commandes</a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard Admin</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Déconnexion</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
            <!-- Barre de recherche mobile (visible seulement sur mobile) -->
            <div class="md:hidden pb-3">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-400" value="{{ request('search') }}">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- Messages flash animés -->
    <div class="container mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow transform transition-all duration-500 fade-in-up">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow transform transition-all duration-500 fade-in-up">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Main content -->
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-grow">
        @yield('content')
    </main>

    <!-- Footer simple -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-10">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} EcommercePro. Tous droits réservés.
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>