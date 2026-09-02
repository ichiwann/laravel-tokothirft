<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:user,user_username'], //[cite: 1]
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:50', 'unique:user,user_email'], //[cite: 1]
            'nohp'     => ['required', 'string', 'max:13'],
            'alamat'   => ['required', 'string', 'max:200'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'user_username'   => $request->username, // 👈 Ambil langsung dari inputan form
            'user_fullname'   => $request->name,
            'user_email'      => $request->email,
            'user_password'   => Hash::make($request->password),
            'user_nohp'       => $request->nohp,
            'user_alamat'     => $request->alamat,
            'user_profil_url' => 'url_placeholder_profil',
            'user_level'      => 'Pengguna',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
