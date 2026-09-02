<!-- Backdrop Overlay (Gelap saat Sidebar Mobile Terbuka) -->
<div x-show="sidebarOpen" 
    @click="sidebarOpen = false" 
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-950/70 z-40 md:hidden"
    x-cloak>
</div>

<!-- Sidebar Main -->
<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-teal-950 via-teal-900 to-slate-900 text-teal-100 flex flex-col justify-between transition-transform duration-300 ease-in-out border-r border-teal-800/40 md:static md:translate-x-0 md:min-h-screen md:flex">
    
    <div>
        <!-- Brand Header (Menampilkan logo & tombol close di drawer mobile) -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-teal-800/50">
            <span class="text-xl font-black text-white tracking-wide">
                Thrift<span class="text-cyan-400">Admin</span>.
            </span>
            <!-- Tombol Tutup (X) khusus layar mobile -->
            <button @click="sidebarOpen = false" class="md:hidden text-teal-300 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Menu Navigation -->
        <nav class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-teal-600 to-cyan-600 text-white shadow-md shadow-teal-900/50' : 'text-teal-200 hover:bg-teal-800/50 hover:text-white' }}">
                <span>📊</span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.kategori.*') ? 'bg-gradient-to-r from-teal-600 to-cyan-600 text-white shadow-md shadow-teal-900/50' : 'text-teal-200 hover:bg-teal-800/50 hover:text-white' }}">
                <span>🏷️</span>
                <span>Kategori Pakaian</span>
            </a>

            <a href="{{ route('admin.pakaian.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.pakaian.*') ? 'bg-gradient-to-r from-teal-600 to-cyan-600 text-white shadow-md shadow-teal-900/50' : 'text-teal-200 hover:bg-teal-800/50 hover:text-white' }}">
                <span>🧥</span>
                <span>Data Pakaian</span>
            </a>

            <a href="{{ route('admin.pembelian.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition {{ request()->routeIs('admin.pembelian.*') ? 'bg-gradient-to-r from-teal-600 to-cyan-600 text-white shadow-md shadow-teal-900/50' : 'text-teal-200 hover:bg-teal-800/50 hover:text-white' }}">
                <span>🛒</span>
                <span>Data Pembelian</span>
            </a>
        </nav>
    </div>

    <!-- Profile / Logout Bottom -->
    <div class="p-4 border-t border-teal-800/50 bg-teal-950/50">
        <div class="flex items-center justify-between">
            <div class="text-xs">
                <p class="font-bold text-white truncate max-w-[110px]">{{ Auth::user()->user_fullname ?? 'Administrator' }}</p>
                <p class="text-teal-400 text-[10px]">Admin Mode</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-xs text-red-300 hover:text-red-200 font-semibold py-1 px-2.5 rounded-lg bg-red-950/40 hover:bg-red-900/50 border border-red-800/30 transition">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</aside>