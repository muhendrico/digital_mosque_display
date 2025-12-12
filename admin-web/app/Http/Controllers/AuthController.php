<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('api_token')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        try {
            $apiUrl = env('API_URL') . '/auth/login';
            
            $response = Http::post($apiUrl, [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['token'])) {
                    Session::put('api_token', $data['token']);
                    Session::put('user', $data['user'] ?? []);

                    return redirect()->route('admin.dashboard')->with('success', 'Login Berhasil!');
                }
            }

            $errorMsg = $response->json()['message'] ?? 'Email atau password salah.';
            
            return back()->withErrors(['email' => $errorMsg])->withInput($request->only('email'));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke server API. Pastikan Backend Lumen menyala.');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}