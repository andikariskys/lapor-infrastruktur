<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.api_url');
    }

    public function index()
    {
        $token = Session::get('api_token');
        
        $response = Http::withToken($token)->get($this->apiUrl . '/reports');

        if ($response->successful()) {
            $reports = collect($response->json());
            return view('laporan', compact('reports'));
        }

        return back()->withErrors(['message' => 'Gagal mengambil data laporan.']);
    }

    public function dashboard()
    {
        $token = Session::get('api_token');
        
        $response = Http::withToken($token)->get($this->apiUrl . '/reports');

        if ($response->successful()) {
            $reports = collect($response->json());
            return view('dashboard', compact('reports'));
        }

        return view('dashboard', ['reports' => collect([])]);
    }

    public function show($id)
    {
        $token = Session::get('api_token');
        
        $response = Http::withToken($token)->get($this->apiUrl . '/reports/' . $id);

        if ($response->successful()) {
            $report = $response->json();
            return view('laporan-detail', compact('report'));
        }

        return redirect('/laporan')->withErrors(['message' => 'Detail laporan tidak ditemukan.']);
    }
}
