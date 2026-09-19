<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return match ($user->role) {
                'admin'    => redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Administrator!'),
                'owner'    => redirect()->route('owner.dashboard')->with('success', 'Selamat datang di Dashboard Restoran Anda!'),
                'customer' => redirect()->route('home')->with('success', 'Selamat datang kembali, ' . $user->name . '!'),
                default    => redirect()->route('home'),
            };
        }

        return back()
            ->withErrors(['email' => 'Email atau password yang Anda masukkan tidak sesuai.'])
            ->withInput($request->only('email', 'remember'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role']     = 'customer';

        $user = User::create($data);

        // Auto login after successful registration
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang di aplikasi pemesanan makanan.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}
