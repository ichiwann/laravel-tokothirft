<nav x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <span class="text-2xl font-black bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                    ThriftMalang.
                </span>
            </a>

            <!-- Desktop Navigation Links (Tampil di md ke atas) -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('home') }}"
                    class="text-sm font-medium {{ request()->routeIs('home') ? 'text-teal-600 font-bold' : 'text-gray-700 hover:text-teal-600' }} transition">
                    Beranda
                </a>
                <a href="{{ route('pakaian.index') }}"
                    class="text-sm font-medium {{ request()->routeIs('pakaian.*') ? 'text-teal-600 font-bold' : 'text-gray-700 hover:text-teal-600' }} transition">
                    Katalog Pakaian
                </a>

                @auth
                    @php
                        $totalKeranjang = \App\Models\Keranjang::where('keranjang_user_id', Auth::user()->user_id)->sum('keranjang_jumlah');
                    @endphp

                    <!-- Ikon Keranjang Desktop -->
                    <a href="{{ route('keranjang.index') }}"
                        class="relative p-2 text-gray-700 hover:text-teal-600 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        @if ($totalKeranjang > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full">
                                {{ $totalKeranjang }}
                            </span>
                        @endif
                    </a>

                    <!-- User Dropdown Desktop -->
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" @click.outside="open = false" type="button"
                            class="flex items-center gap-2 px-3.5 py-2 text-sm font-bold text-teal-700 hover:text-teal-800 bg-teal-50 hover:bg-teal-100/80 rounded-full transition focus:outline-none border border-teal-200/60">
                            <span>{{ Auth::user()->user_fullname ?? (Auth::user()->name ?? 'Akun Saya') }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50"
                            x-cloak>

                            <a href="{{ route('pembelian.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                                <span>🛍️</span> Riwayat Pembelian
                            </a>
                            <a href="{{ route('metode.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                                <span>💳</span> Metode Pembayaran
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                                <span>⚙️</span> Setting Akun
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition">
                                    <span>🚪</span> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-teal-600 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 rounded-full shadow-sm transition">Daftar</a>
                @endauth
            </div>

            <!-- Tombol Aksi Mobile (Keranjang & Hamburger) -->
            <div class="flex items-center space-x-2 md:hidden">
                @auth
                    @php
                        $totalKeranjangMobile = \App\Models\Keranjang::where('keranjang_user_id', Auth::user()->user_id)->sum('keranjang_jumlah');
                    @endphp
                    <!-- Quick Access Keranjang Mobile -->
                    <a href="{{ route('keranjang.index') }}" class="relative p-2 text-gray-700 hover:text-teal-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        @if ($totalKeranjangMobile > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full">
                                {{ $totalKeranjangMobile }}
                            </span>
                        @endif
                    </a>
                @endauth

                <!-- Hamburger Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-gray-600 hover:text-teal-600 hover:bg-gray-100 focus:outline-none transition">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-t border-gray-100 bg-white px-4 pt-2 pb-6 space-y-3 shadow-lg"
        x-cloak>
        
        <!-- Menu Navigasi Utama Mobile -->
        <div class="space-y-1 pt-1">
            <a href="{{ route('home') }}"
                class="block px-3 py-2 rounded-xl text-base font-medium {{ request()->routeIs('home') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                Beranda
            </a>
            <a href="{{ route('pakaian.index') }}"
                class="block px-3 py-2 rounded-xl text-base font-medium {{ request()->routeIs('pakaian.*') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                Katalog Pakaian
            </a>
        </div>

        <div class="border-t border-gray-100 my-2"></div>

        <!-- Menu User / Auth Mobile -->
        @auth
            <div class="px-3 py-2 bg-teal-50/60 rounded-xl mb-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                <span class="text-xs font-bold text-teal-800">
                    {{ Auth::user()->user_fullname ?? (Auth::user()->name ?? 'Akun Saya') }}
                </span>
            </div>

            <div class="space-y-1">
                <a href="{{ route('pembelian.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <span>🛍️</span> Riwayat Pembelian
                </a>
                <a href="{{ route('metode.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <span>💳</span> Metode Pembayaran
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    <span>⚙️</span> Setting Akun
                </a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-bold text-red-600 hover:bg-red-50 transition">
                        <span>🚪</span> Keluar
                    </button>
                </form>
            </div>
        @else
            <div class="flex flex-col gap-2 pt-1">
                <a href="{{ route('login') }}" class="w-full text-center py-2.5 text-sm font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 rounded-xl transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="w-full text-center py-2.5 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-xl shadow-sm transition">
                    Daftar
                </a>
            </div>
        @endauth
    </div>
</nav>