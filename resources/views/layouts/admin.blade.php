<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel - ThriftMalang' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 font-sans antialiased text-teal-100">

    <!-- Scope Alpine.js dipasang di pembungkus paling luar agar Header & Sidebar terhubung -->
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex relative">

        <!-- SIDEBAR ADMIN -->
        <x-admin.sidebar />

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-gradient-to-br from-slate-950 via-teal-950 to-slate-900">

            <!-- TOPBAR / HEADER ADMIN -->
            <header class="h-16 bg-teal-900/40 backdrop-blur-md border-b border-teal-800/50 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Tombol Toggle Sidebar Mobile -->
                    <button @click="sidebarOpen = !sidebarOpen" type="button"
                        class="md:hidden p-1.5 rounded-lg text-teal-200 hover:text-white hover:bg-teal-800/50 focus:outline-none transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Judul Header Responsive -->
                    <h1 class="text-sm sm:text-lg font-bold text-white truncate">
                        Panel Manajemen Toko
                    </h1>
                </div>

                <!-- Badge Admin Responsive -->
                <span class="text-[10px] sm:text-xs px-2.5 py-1 sm:px-3 sm:py-1 bg-gradient-to-r from-teal-500 to-cyan-500 text-slate-950 font-black rounded-full shadow-md shadow-cyan-900/40 whitespace-nowrap shrink-0">
                    Admin Malang
                </span>
            </header>

            <!-- PAGE CONTENT -->
            <main class="p-4 sm:p-6 flex-1">
                @yield('content')
            </main>
        </div>

    </div>

</body>

</html>