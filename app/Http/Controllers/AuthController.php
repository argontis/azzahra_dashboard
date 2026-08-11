<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'kry_username' => $request->username,
            'password' => $request->pswd
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if ($user->kry_level == 'Admin') {
                return redirect()->intended('/Admin');
            } elseif ($user->kry_level == 'Kasir') {
                return redirect()->intended('/Kasir');
            } elseif ($user->kry_level == 'Customer Service') {
                return redirect()->intended('/Service');
            } elseif ($user->kry_level == 'Teknisi') {
                return redirect()->intended('/Teknisi');
            } elseif ($user->kry_level == 'HR') {
                return redirect()->intended('/HR');
            }
            
            return redirect()->intended('/dashboard');
        }

        return back()->with('gagal', 'Username atau Password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/Auth');
    }
}
