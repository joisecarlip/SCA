<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;

class LoginController extends Controller
{
    public function inicio()
    {
        $user = Auth::guard('usuarios')->user();
        return view('inicio', compact('user'));
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        
        $credentials = $request->only('user_gmail', 'user_password');

        $user = Usuarios::where('user_gmail', $credentials['user_gmail'])->first();

        if ($user && Hash::check($credentials['user_password'], $user->user_password)) {
            Auth::guard('usuarios')->login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/inicio');
        }
        return back()->withErrors([
            'user_gmail' => 'Credenciales incorrectas'
        ])->withInput($request->only('user_gmail'));
    }

    public function logout(Request $request)
    {
        Auth::guard('usuarios')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
