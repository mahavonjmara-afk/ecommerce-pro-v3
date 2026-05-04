<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon E-commerce</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-blue-600 mb-4">
            🎉 Bienvenue sur mon E-commerce
        </h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <p class="text-gray-700">
                TailwindCSS est installé avec succès !
            </p>
            <button class="btn-primary mt-4">
                Bouton personnalisé
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-lg">Produit 1</h3>
                <p class="text-gray-600">Description</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-lg">Produit 2</h3>
                <p class="text-gray-600">Description</p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-lg">Produit 3</h3>
                <p class="text-gray-600">Description</p>
            </div>
        </div>
    </div>
</body>
</html>