<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('pages.user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $user = User::findOrFail($authUser->user_id);

        $request->validate([
            'user_fullname'   => ['required', 'string', 'max:100'],
            'user_username'   => ['required', 'string', 'max:50', Rule::unique('user', 'user_username')->ignore($user->user_id, 'user_id')],
            'user_email'      => ['required', 'email', 'max:50', Rule::unique('user', 'user_email')->ignore($user->user_id, 'user_id')],
            'user_nohp'       => ['required', 'string', 'max:13'],
            'user_alamat'     => ['required', 'string', 'max:200'],
            'user_profil_url' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'user_fullname' => $request->user_fullname,
            'user_username' => $request->user_username,
            'user_email'    => $request->user_email,
            'user_nohp'     => $request->user_nohp,
            'user_alamat'   => $request->user_alamat,
        ];

        // Jika user mengunggah foto baru
        if ($request->hasFile('user_profil_url')) {
            // Hapus foto lama jika ada dan bukan placeholder default
            if ($user->user_profil_url && $user->user_profil_url !== 'url_placeholder_profil' && Storage::disk('public')->exists($user->user_profil_url)) {
                Storage::disk('public')->delete($user->user_profil_url);
            }

            // Simpan foto baru ke folder storage/app/public/profile_pictures
            $path = $request->file('user_profil_url')->store('profile_pictures', 'public');
            $data['user_profil_url'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
