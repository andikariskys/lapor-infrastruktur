<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('api_token')) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $response = Http::asForm()->post(config('app.api_url') . '/auth/login', [
            'username' => $request->email, // Ini sebenarnya email, tapi ngak tau kenapa katanya standar OAuth dia harus disi username
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            // Simpan token dan data user ke session
            Session::put('api_token', $data['access_token']);
            
            // Ambil data profil user untuk mendapatkan role
            $userResponse = Http::withToken($data['access_token'])->get(config('app.api_url') . '/auth/me');
            
            if ($userResponse->successful()) {
                $user = $userResponse->json();
                
                if ($user['role'] !== 'admin') {
                    Session::forget('api_token');
                    return back()->withErrors(['email' => 'Hanya Admin yang dapat mengakses dashboard ini.']);
                }
                
                Session::put('user', $user);
                return redirect('/dashboard');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout()
    {
        Session::forget(['api_token', 'user']);
        return redirect('/');
    }
}
