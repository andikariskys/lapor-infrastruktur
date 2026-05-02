<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.api_url');
    }

    public function index()
    {
        $token = Session::get('api_token');
        
        // Mengambil daftar kategori dari FastAPI
        $response = Http::withToken($token)->get($this->apiUrl . '/categories');

        if ($response->successful()) {
            $categories = $response->json();
            return view('kategori', compact('categories'));
        }

        return view('kategori', ['categories' => []])->withErrors(['message' => 'Gagal mengambil data kategori.']);
    }

    public function store(Request $request)
    {
        $token = Session::get('api_token');
        
        $response = Http::withToken($token)->post($this->apiUrl . '/categories', [
            'name' => $request->name,
            'description' => $request->description,
            'color_code' => $request->color_code,
        ]);

        if ($response->successful()) {
            return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan.');
        }

        return back()->withErrors(['message' => 'Gagal menambah kategori.']);
    }

    public function update(Request $request, $id)
    {
        $token = Session::get('api_token');
        
        $response = Http::withToken($token)->patch($this->apiUrl . '/categories/' . $id, [
            'name' => $request->name,
            'description' => $request->description,
            'color_code' => $request->color_code,
        ]);

        if ($response->successful()) {
            return redirect('/kategori')->with('success', 'Kategori berhasil diperbarui.');
        }

        return back()->withErrors(['message' => 'Gagal memperbarui kategori.']);
    }

    public function destroy($id)
    {
        $token = Session::get('api_token');
        
        $response = Http::withToken($token)->delete($this->apiUrl . '/categories/' . $id);

        if ($response->successful()) {
            return redirect('/kategori')->with('success', 'Kategori berhasil dihapus.');
        }

        return back()->withErrors(['message' => 'Gagal menghapus kategori.']);
    }
}
