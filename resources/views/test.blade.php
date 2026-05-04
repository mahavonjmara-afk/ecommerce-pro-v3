<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test TailwindCSS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-blue-600">
            ✅ TailwindCSS fonctionne !
        </h1>
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <p class="text-gray-700">
                Si vous voyez ce message avec du style, c'est que tout est bon.
            </p>
            <button class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Bouton stylisé
            </button>
        </div>
    </div>
</body>
</html>