<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'password' => $request->pswd,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->kry_level == 'Admin') {
                return redirect()->intended('/Admin')->with('show_curtain', true);
            } elseif ($user->kry_level == 'Kasir') {
                return redirect()->intended('/Kasir')->with('show_curtain', true);
            } elseif ($user->kry_level == 'Customer Service') {
                return redirect()->intended('/Service')->with('show_curtain', true);
            } elseif ($user->kry_level == 'Teknisi') {
                return redirect()->intended('/Teknisi')->with('show_curtain', true);
            } elseif ($user->kry_level == 'HR') {
                return redirect()->intended('/HR')->with('show_curtain', true);
            }

            return redirect()->intended('/dashboard')->with('show_curtain', true);
        }

        return back()->with('gagal', 'Username atau Password salah');
    }

    public function reset()
    {
        return view('auth.reset');
    }

    public function postReset(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'pswd' => 'required|min:4',
            'pswd_confirmation' => 'required|same:pswd',
        ], [
            'username.required' => 'Username wajib diisi',
            'pswd.required' => 'Password baru wajib diisi',
            'pswd.min' => 'Password minimal 4 karakter',
            'pswd_confirmation.required' => 'Konfirmasi password wajib diisi',
            'pswd_confirmation.same' => 'Konfirmasi password tidak cocok',
        ]);

        $karyawan = Karyawan::where('kry_username', $request->username)->first();

        if (! $karyawan) {
            return back()->withInput()->with('gagal', 'Username tidak ditemukan dalam sistem!');
        }

        $karyawan->kry_pswd = Hash::make($request->pswd);
        $karyawan->save();

        return redirect()->route('login')->with('sukses', 'Password berhasil diperbarui! Silakan masuk dengan kata sandi baru.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Auth');
    }
}
