<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class InstitutionController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.api_url');
    }

    public function index()
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->get($this->apiUrl . '/institutions');

        if ($response->successful()) {
            $institutions = $response->json();
            return view('lembaga', compact('institutions'));
        }

        return view('lembaga', ['institutions' => []])->withErrors(['message' => 'Gagal mengambil data lembaga.']);
    }

    public function store(Request $request)
    {
        $token = Session::get('api_token');
        
        $postData = $request->only(['name', 'description', 'address', 'phone', 'email']);
        
        $request_http = Http::withToken($token);
        
        if ($request->hasFile('image')) {
            $request_http = $request_http->attach(
                'image', 
                file_get_contents($request->file('image')->getRealPath()), 
                $request->file('image')->getClientOriginalName()
            );
        }

        $response = $request_http->post($this->apiUrl . '/institutions', $postData);

        if ($response->successful()) {
            return redirect('/lembaga')->with('success', 'Lembaga berhasil ditambahkan.');
        }

        return back()->withErrors(['message' => 'Gagal menambah lembaga.']);
    }

    public function update(Request $request, $id)
    {
        $token = Session::get('api_token');
        
        $postData = $request->only(['name', 'description', 'address', 'phone', 'email']);
        
        $request_http = Http::withToken($token);
        
        if ($request->hasFile('image')) {
            $request_http = $request_http->attach(
                'image', 
                file_get_contents($request->file('image')->getRealPath()), 
                $request->file('image')->getClientOriginalName()
            );
        }

        $response = $request_http->patch($this->apiUrl . '/institutions/' . $id, $postData);

        if ($response->successful()) {
            return redirect('/lembaga')->with('success', 'Lembaga berhasil diperbarui.');
        }

        return back()->withErrors(['message' => 'Gagal memperbarui lembaga.']);
    }

    public function destroy($id)
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->delete($this->apiUrl . '/institutions/' . $id);

        if ($response->successful()) {
            return redirect('/lembaga')->with('success', 'Lembaga berhasil dihapus.');
        }

        return back()->withErrors(['message' => 'Gagal menghapus lembaga.']);
    }
}
