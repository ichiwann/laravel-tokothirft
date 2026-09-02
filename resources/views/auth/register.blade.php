<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Thrift Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-teal-100 min-h-screen flex items-center justify-center p-4 py-10 selection:bg-cyan-400 selection:text-slate-950">

    <div class="w-full max-w-xl">
        <!-- Header / Logo -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-black text-white tracking-wider uppercase">
                Thrift<span class="text-cyan-400">Shop</span>
            </h1>
            <p class="text-xs text-cyan-400 font-medium mt-1">Buat akun baru untuk mulai berbelanja</p>
        </div>

        <!-- Card Container -->
        <div class="bg-teal-900/20 backdrop-blur-md rounded-2xl border border-teal-800/50 shadow-2xl p-6 sm:p-8 space-y-5">

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Grid 2 Kolom: Nama Lengkap & Username -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-teal-300 mb-1">Nama Lengkap</label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               placeholder="Masukkan nama lengkap..." 
                               class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                        @if ($errors->has('name'))
                            <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                                ⚠️ {{ $errors->first('name') }}
                            </p>
                        @endif
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-xs font-bold text-teal-300 mb-1">Username</label>
                        <input id="username" 
                               type="text" 
                               name="username" 
                               value="{{ old('username') }}" 
                               required 
                               placeholder="Masukkan username..." 
                               class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                        @if ($errors->has('username'))
                            <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                                ⚠️ {{ $errors->first('username') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Grid 2 Kolom: Email & No. Telepon -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-teal-300 mb-1">Email</label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="contoh@email.com" 
                               class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                        @if ($errors->has('email'))
                            <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                                ⚠️ {{ $errors->first('email') }}
                            </p>
                        @endif
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="nohp" class="block text-xs font-bold text-teal-300 mb-1">No. Telepon</label>
                        <input id="nohp" 
                               type="text" 
                               name="nohp" 
                               value="{{ old('nohp') }}" 
                               required 
                               placeholder="081234567890" 
                               class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                        @if ($errors->has('nohp'))
                            <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                                ⚠️ {{ $errors->first('nohp') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="alamat" class="block text-xs font-bold text-teal-300 mb-1">Alamat Lengkap</label>
                    <input id="alamat" 
                           type="text" 
                           name="alamat" 
                           value="{{ old('alamat') }}" 
                           required 
                           placeholder="Masukkan alamat lengkap..." 
                           class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                    @if ($errors->has('alamat'))
                        <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                            ⚠️ {{ $errors->first('alamat') }}
                        </p>
                    @endif
                </div>

                <!-- Grid 2 Kolom: Password & Konfirmasi Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-teal-300 mb-1">Password</label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               placeholder="••••••••" 
                               class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                        @if ($errors->has('password'))
                            <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                                ⚠️ {{ $errors->first('password') }}
                            </p>
                        @endif
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-teal-300 mb-1">Konfirmasi Password</label>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               placeholder="••••••••" 
                               class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                        @if ($errors->has('password_confirmation'))
                            <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                                ⚠️ {{ $errors->first('password_confirmation') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-cyan-900/40 hover:opacity-90 transition mt-2">
                    Daftar Akun
                </button>
            </form>

            <!-- Tautan ke Halaman Login -->
            <div class="pt-4 border-t border-teal-800/50 text-center">
                <p class="text-xs text-teal-400/80">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-cyan-400 hover:text-cyan-300 underline transition ms-1">
                        Masuk Sekarang
                    </a>
                </p>
            </div>

        </div>
    </div>

</body>
</html>