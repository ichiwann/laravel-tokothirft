<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Toko Thrift Malang - Pakaian Preloved Quality' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 font-sans antialiased flex flex-col min-h-screen">

    <x-user.navbar />

    <!-- CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-50 border-t border-gray-100 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm text-gray-500">© {{ date('Y') }} Toko Thrift Kota Malang. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>