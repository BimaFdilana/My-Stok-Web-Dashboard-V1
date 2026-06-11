<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // FORM untuk Web
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Registrasi via Web
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'nama_pemilik' => $request->nama_pemilik,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Login user yang baru dibuat
            Auth::login($user);

            // Redirect ke dashboard
            return redirect()->route('auth.dashboard')
                           ->with('success', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])
                        ->withInput();
        }
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $credentials = [
        'username' => $request->username,
        'password' => $request->password,
    ];

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        return redirect()->route('auth.dashboard');
    }

    return back()->withErrors([
        'username' => 'Username atau password salah.',
    ])->withInput();
}


    public function apiLogin(Request $request)
{
    $user = User::where(
        'username',
        $request->username
    )->first();

    if (!$user || !Hash::check(
        $request->password,
        $user->password
    )) {

        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah'
        ], 401);
    }

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'user' => $user
    ]);
}

    // Logout via Web
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }

    // ======================
    // API UNTUK MOBILE
    // ======================

    // Registrasi via API
    public function apiRegister(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'nama_pemilik' => $request->nama_pemilik,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'Registrasi berhasil!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat registrasi.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
       