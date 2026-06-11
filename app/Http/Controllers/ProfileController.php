<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Menampilkan Profil Pengguna
    public function show()
    {
        $user = Auth::user();  // Mendapatkan data pengguna yang sedang login
        return view('profile.show', compact('user'));
    }

    // Menampilkan Form Edit Profil
    public function edit()
    {
        $user = Auth::user();  // Mendapatkan data pengguna yang sedang login
        return view('profile.edit', compact('user'));
    }

    // Memperbarui Profil Pengguna
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user = auth()->user();
            
            // Update data dasar
            $user->name = $request->name;
            $user->email = $request->email;

            // Handle upload foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($user->foto && Storage::exists('public/profile_photos/' . $user->foto)) {
                    Storage::delete('public/profile_photos/' . $user->foto);
                }

                // Upload foto baru
                $foto = $request->file('foto');
                $filename = time() . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/profile_photos', $filename);
                $user->foto = $filename;
            }

            $user->save();

            return redirect()->route('profile.show')
                           ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        try {
            $user = auth()->user();

            // Verifikasi password lama
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
            }

            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('profile.show')
                           ->with('success', 'Password berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna yang sedang login

        // Invalidasi sesi pengguna dan regenerasi token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect pengguna kembali ke halaman login
        return redirect('/login')->with('success', 'Anda berhasil logout!');
    }
}
