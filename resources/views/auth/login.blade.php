<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Thrift Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-teal-100 min-h-screen flex items-center justify-center p-4 selection:bg-cyan-400 selection:text-slate-950">

    <div class="w-full max-w-md">
        <!-- Header / Logo -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-black text-white tracking-wider uppercase">
                Thrift<span class="text-cyan-400">Shop</span>
            </h1>
            <p class="text-xs text-cyan-400 font-medium mt-1">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Card Container -->
        <div class="bg-teal-900/20 backdrop-blur-md rounded-2xl border border-teal-800/50 shadow-2xl p-6 sm:p-8 space-y-5">

            <!-- Session Status / Alert -->
            @if (session('status'))
                <div class="p-3.5 bg-teal-900/60 border border-teal-500/50 rounded-xl text-teal-200 text-xs font-semibold flex items-center gap-2">
                    <span>✅ {{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Username Input -->
                <div>
                    <label for="username" class="block text-xs font-bold text-teal-300 mb-1">Username</label>
                    <input id="username" 
                           type="text" 
                           name="username" 
                           value="{{ old('username') }}" 
                           required 
                           autofocus 
                           autocomplete="username"
                           placeholder="Masukkan username..." 
                           class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                    @if ($errors->has('username'))
                        <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                            ⚠️ {{ $errors->first('username') }}
                        </p>
                    @endif
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-bold text-teal-300">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[11px] text-cyan-400 hover:text-cyan-300 transition">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="••••••••" 
                           class="w-full bg-slate-900 border border-teal-800/60 rounded-xl px-4 py-2.5 text-xs text-white placeholder-teal-600 focus:outline-none focus:border-cyan-400 transition">
                    @if ($errors->has('password'))
                        <p class="mt-1.5 text-xs text-red-400 font-semibold flex items-center gap-1">
                            ⚠️ {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember" 
                               class="rounded bg-slate-900 border-teal-800 text-cyan-500 focus:ring-cyan-400 focus:ring-offset-slate-950 transition">
                        <span class="ms-2 text-xs text-teal-400/80 font-medium">Ingat Saya</span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-cyan-900/40 hover:opacity-90 transition mt-2">
                    Log In
                </button>
            </form>

            <!-- Tautan ke Halaman Register -->
            <div class="pt-4 border-t border-teal-800/50 text-center">
                <p class="text-xs text-teal-400/80">
                    Belum punya akun?
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="font-bold text-cyan-400 hover:text-cyan-300 underline transition ms-1">
                            Daftar Sekarang
                        </a>
                    @endif
                </p>
            </div>

        </div>
    </div>

</body>
</html>