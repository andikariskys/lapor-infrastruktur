@extends('layouts.admin')

@section('title', 'Dashboard Admin - Lapor Infrastruktur')
@section('title_mobile', 'Dashboard Admin')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8">
    
    <!-- Page Header -->
    <div class="hidden lg:block">
        <h1 class="text-2xl font-bold text-blue-800 mb-1">Dashboard Admin</h1>
        <p class="text-sm text-gray-500">Selamat datang di sistem manajemen laporan infrastruktur</p>
    </div>

    <!-- Stats Grid - responsive: 2 cols mobile, 3 cols tablet, 5 cols desktop -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        
        <!-- Total Laporan -->
        <div class="bg-blue-700 rounded-2xl p-4 sm:p-5 text-white shadow-md col-span-2 sm:col-span-1 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="absolute -right-2 -bottom-6 w-16 h-16 bg-white/5 rounded-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-blue-200">Total Laporan</span>
                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-4xl font-extrabold">124</div>
            </div>
        </div>

        <!-- Diajukan -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-100 rounded-2xl p-4 sm:p-5 border border-orange-200/60 shadow-sm relative overflow-hidden">
            <div class="absolute -right-3 -top-3 w-14 h-14 bg-orange-200/30 rounded-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-orange-700">Diajukan</span>
                    <div class="w-8 h-8 rounded-lg bg-orange-200/50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-4xl font-extrabold text-orange-900">8</div>
            </div>
        </div>

        <!-- Diproses -->
        <div class="bg-gradient-to-br from-blue-50 to-sky-100 rounded-2xl p-4 sm:p-5 border border-blue-200/60 shadow-sm relative overflow-hidden">
            <div class="absolute -right-3 -top-3 w-14 h-14 bg-blue-200/30 rounded-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-blue-700">Diproses</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-200/50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-4xl font-extrabold text-blue-900">8</div>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="bg-gradient-to-br from-red-50 to-rose-100 rounded-2xl p-4 sm:p-5 border border-red-200/60 shadow-sm relative overflow-hidden">
            <div class="absolute -right-3 -top-3 w-14 h-14 bg-red-200/30 rounded-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-red-700">Ditolak</span>
                    <div class="w-8 h-8 rounded-lg bg-red-200/50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-4xl font-extrabold text-red-900">3</div>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-gradient-to-br from-emerald-100 to-green-200 rounded-2xl p-4 sm:p-5 border border-green-300/60 shadow-sm relative overflow-hidden">
            <div class="absolute -right-3 -top-3 w-14 h-14 bg-green-300/30 rounded-full"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-green-800">Selesai</span>
                    <div class="w-8 h-8 rounded-lg bg-green-300/50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-4xl font-extrabold text-green-900">105</div>
            </div>
        </div>

    </div>

    <!-- Recent Reports List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
            <h2 class="text-base sm:text-lg font-bold text-blue-800">Laporan Terbaru</h2>
        </div>
        
        <div class="divide-y divide-gray-100">
            <!-- Report Item 1 -->
            <a href="{{ url('/laporan/detail') }}" class="block px-4 sm:px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50/50 transition-colors">
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-gray-800 mb-0.5 truncate">Jalan Berlubang di Jl. Sudirman</h3>
                    <p class="text-xs text-gray-500">Jl. Sudirman No. 42</p>
                    <p class="text-xs text-gray-400 mt-0.5">Kerusakan Jalan • 2026-04-25</p>
                </div>
                <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                    Diajukan
                </span>
            </a>

            <!-- Report Item 2 -->
            <a href="{{ url('/laporan/detail?status=diproses') }}" class="block px-4 sm:px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50/50 transition-colors">
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-gray-800 mb-0.5 truncate">Lampu Jalan Mati</h3>
                    <p class="text-xs text-gray-500">Jl. Merdeka No. 15</p>
                    <p class="text-xs text-gray-400 mt-0.5">Penerangan Jalan • 2026-04-24</p>
                </div>
                <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                    Diproses
                </span>
            </a>

            <!-- Report Item 3 -->
            <a href="{{ url('/laporan/detail?status=selesai') }}" class="block px-4 sm:px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50/50 transition-colors">
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-gray-800 mb-0.5 truncate">Trotoar Rusak</h3>
                    <p class="text-xs text-gray-500">Jl. Gatot Subroto</p>
                    <p class="text-xs text-gray-400 mt-0.5">Kerusakan Jalan • 2026-04-23</p>
                </div>
                <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                    Selesai
                </span>
            </a>

            <!-- Report Item 4 -->
            <a href="{{ url('/laporan/detail?status=ditolak') }}" class="block px-4 sm:px-6 py-4 flex items-center justify-between gap-4 hover:bg-gray-50/50 transition-colors">
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-gray-800 mb-0.5 truncate">Saluran Air Tersumbat</h3>
                    <p class="text-xs text-gray-500">Jl. Ahmad Yani</p>
                    <p class="text-xs text-gray-400 mt-0.5">Drainase • 2026-04-23</p>
                </div>
                <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                    Ditolak
                </span>
            </a>
        </div>
    </div>

</div>
@endsection
