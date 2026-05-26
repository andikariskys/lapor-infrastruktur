<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.api_url');
    }

    public function update(Request $request)
    {
        $token = Session::get('api_token');
        $postData = $request->only(['name', 'phone']);
        
        $request_http = Http::withToken($token);
        
        if ($request->hasFile('image')) {
            $request_http = $request_http->attach(
                'image', 
                file_get_contents($request->file('image')->getRealPath()), 
                $request->file('image')->getClientOriginalName()
            );
            $response = $request_http->patch($this->apiUrl . '/users/me', $postData);
        } else {
            $response = $request_http->asForm()->patch($this->apiUrl . '/users/me', $postData);
        }

        if ($response->successful()) {
            // Update session user data
            Session::put('user', $response->json());
            return back()->with('success', 'Profil berhasil diperbarui.');
        }

        return back()->withErrors(['message' => 'Gagal memperbarui profil.']);
    }

    public function changePassword(Request $request)
    {
        $token = Session::get('api_token');
        
        $response = Http::withToken($token)->asForm()->patch($this->apiUrl . '/users/me/password', [
            'old_password' => $request->old_password,
            'new_password' => $request->new_password,
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Password berhasil diperbarui.');
        }

        $detail = $response->json()['detail'] ?? 'Gagal mengganti password.';
        $errorMessage = is_array($detail) ? json_encode($detail) : $detail;

        return back()->withErrors(['message' => $errorMessage]);
    }
}
