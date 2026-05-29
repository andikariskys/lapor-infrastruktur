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

    public function index(Request $request)
    {
        $token = Session::get('api_token');
        $status = $request->query('status');

        $url = $this->apiUrl . '/reports';
        if ($status) {
            $url .= '?status=' . $status;
        }

        $response = Http::withToken($token)->get($url);

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

        // Ambil detail laporan
        $response = Http::withToken($token)->get($this->apiUrl . '/reports/' . $id);

        // Ambil data pendukung untuk penugasan
        $officersResponse = Http::withToken($token)->get($this->apiUrl . '/users/officers');
        $categoriesResponse = Http::withToken($token)->get($this->apiUrl . '/categories');
        $institutionsResponse = Http::withToken($token)->get($this->apiUrl . '/institutions');

        $officers = $officersResponse->successful() ? $officersResponse->json() : [];
        $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];
        $institutions = $institutionsResponse->successful() ? $institutionsResponse->json() : [];

        if ($response->successful()) {
            $report = $response->json();
            return view('laporan-detail', compact('report', 'officers', 'categories', 'institutions'));
        }

        return redirect('/laporan')->withErrors(['message' => 'Detail laporan tidak ditemukan.']);
    }

    public function update(Request $request, $id)
    {
        $token = Session::get('api_token');

        $response = Http::withToken($token)->asForm()->patch($this->apiUrl . '/reports/' . $id, [
            'status' => $request->status,
            'category_id' => $request->category_id,
            'institution_id' => $request->institution_id,
            'officer_id' => $request->officer_id,
            'note' => $request->note,
        ]);

        if ($response->successful()) {
            return redirect('/laporan/' . $id)->with('success', 'Laporan berhasil diperbarui dan ditugaskan.');
        }

        return back()->withErrors(['message' => 'Gagal memperbarui laporan.']);
    }
}
