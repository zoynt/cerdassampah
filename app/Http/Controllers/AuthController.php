<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  
    public function showRegisterForm()
    {
        return view('auth.register'); 
    }

    
    public function storeUser(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        
        Auth::login($user);

        
        return redirect('/dashboard');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Fungsi untuk memproses login
    public function loginUser(Request $request)
    {
        // 1. Validasi data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba otentikasi user
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Jika berhasil, arahkan ke dashboard
            return redirect()->intended('/dashboard');
        }

        // 3. Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang diberikan tidak cocok.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout(); // Menghapus informasi otentikasi dari session

        $request->session()->invalidate(); // Membuat session yang lama tidak valid

        $request->session()->regenerateToken(); // Membuat token CSRF yang baru

        // Mengarahkan pengguna kembali ke halaman utama
        return redirect('/'); 
    }
}