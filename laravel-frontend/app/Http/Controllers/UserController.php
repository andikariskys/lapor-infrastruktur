<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.api_url');
    }

    public function index(Request $request)
    {
        $token = Session::get('api_token');
        $role = $request->query('role');
        
        $url = $this->apiUrl . '/users';
        if ($role) {
            $url .= '?role=' . $role;
        }

        $response = Http::withToken($token)->get($url);
        $institutionsResponse = Http::withToken($token)->get($this->apiUrl . '/institutions');

        if ($response->successful()) {
            $users = $response->json();
            $institutions = $institutionsResponse->successful() ? $institutionsResponse->json() : [];
            return view('users', compact('users', 'institutions'));
        }

        return view('users', ['users' => [], 'institutions' => []])->withErrors(['message' => 'Gagal mengambil data user.']);
    }

    public function storeOfficer(Request $request)
    {
        $token = Session::get('api_token');
        
        $postData = $request->only(['name', 'email', 'phone', 'password', 'institution_id', 'role']);
        
        $request_http = Http::withToken($token);
        
        if ($request->hasFile('image')) {
            $request_http = $request_http->attach(
                'image', 
                file_get_contents($request->file('image')->getRealPath()), 
                $request->file('image')->getClientOriginalName()
            );
            $response = $request_http->post($this->apiUrl . '/users/officers', $postData);
        } else {
            $response = $request_http->asForm()->post($this->apiUrl . '/users/officers', $postData);
        }

        if ($response->successful()) {
            return redirect('/users')->with('success', 'Petugas berhasil didaftarkan.');
        }

        $detail = $response->json()['detail'] ?? 'Gagal mendaftarkan petugas.';
        $errorMessage = is_array($detail) ? json_encode($detail) : $detail;
        
        return back()->withErrors(['message' => $errorMessage]);
    }

    public function update(Request $request, $id)
    {
        $token = Session::get('api_token');
        
        $postData = $request->only(['name', 'email', 'phone', 'institution_id', 'role']);
        
        $request_http = Http::withToken($token);
        
        if ($request->hasFile('image')) {
            $request_http = $request_http->attach(
                'image', 
                file_get_contents($request->file('image')->getRealPath()), 
                $request->file('image')->getClientOriginalName()
            );
            $response = $request_http->patch($this->apiUrl . '/users/' . $id, $postData);
        } else {
            $response = $request_http->asForm()->patch($this->apiUrl . '/users/' . $id, $postData);
        }

        if ($response->successful()) {
            return redirect('/users')->with('success', 'Data user berhasil diperbarui.');
        }

        $detail = $response->json()['detail'] ?? 'Gagal memperbarui data user.';
        $errorMessage = is_array($detail) ? json_encode($detail) : $detail;

        return back()->withErrors(['message' => $errorMessage]);
    }

    public function resetPassword(Request $request, $id)
    {
        $token = Session::get('api_token');
        
        $response = Http::withToken($token)->asForm()->patch($this->apiUrl . '/users/' . $id . '/reset-password', [
            'new_password' => $request->new_password
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Password berhasil direset.']);
        }

        return response()->json(['message' => 'Gagal mereset password.'], 500);
    }

    public function destroy($id)
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->delete($this->apiUrl . '/users/' . $id);

        if ($response->successful()) {
            return redirect('/users')->with('success', 'User berhasil dihapus.');
        }

        return back()->withErrors(['message' => 'Gagal menghapus user.']);
    }
}
