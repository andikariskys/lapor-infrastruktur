@extends('layouts.admin')

@section('title', 'Daftar Laporan - Lapor Infrastruktur')
@section('title_mobile', 'Daftar Laporan')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8">
    
    <!-- Page Header -->
    <div class="hidden lg:block">
        <h1 class="text-2xl font-bold text-blue-800 mb-1">Daftar Laporan</h1>
        <p class="text-sm text-gray-500">Kelola dan pantau semua laporan infrastruktur</p>
    </div>

    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row gap-3 items-center w-full">
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-gray-800" placeholder="Cari laporan, lokasi, atau pelapor...">
        </div>
        <div class="relative w-full sm:w-auto shrink-0">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/></svg>
            </div>
            <select class="block w-full sm:w-48 pl-10 pr-8 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-gray-700 font-medium appearance-none bg-white cursor-pointer hover:bg-gray-50 transition-colors">
                <option value="">Semua Status</option>
                <option value="diajukan">Diajukan</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>
        </div>
    </div>

    <!-- Category Lists -->
    <div class="space-y-6 w-full">

        <!-- Cluster 1: Road Damage -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-base font-bold text-blue-700">Road Damage</h2>
                    <p class="text-xs text-gray-500 mt-0.5">3 laporan dalam kategori ini</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Cluster 1
                </span>
            </div>
            <div class="p-6 space-y-4">
                <!-- Report 1 -->
                <a href="{{ url('/laporan/detail') }}" class="block border border-gray-100 rounded-xl p-4 sm:p-5 hover:border-gray-200 transition-colors bg-white flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Pusat Cluster</span>
                            <h3 class="text-sm font-bold text-gray-900">Jalan Berlubang di Jl. Sudirman</h3>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Sudirman No. 42, Jakarta
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-25 • 14:30
                            </div>
                            <span>•</span>
                            <span>Pelapor: Ahmad Fauzi</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                            Diajukan
                        </span>
                    </div>
                </a>

                <!-- Report 2 -->
                <a href="{{ url('/laporan/detail') }}" class="block border border-gray-100 rounded-xl p-4 sm:p-5 hover:border-gray-200 transition-colors bg-white flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-gray-900">Aspal Jalan Retak</h3>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Sudirman No. 85, Jakarta <span class="text-gray-400">~0.12 km dari pusat</span>
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-25 • 10:10
                            </div>
                            <span>•</span>
                            <span>Pelapor: Joko Widodo</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                            Diajukan
                        </span>
                    </div>
                </a>

                <!-- Report 3 -->
                <a href="{{ url('/laporan/detail?status=selesai') }}" class="block border border-gray-100 rounded-xl p-4 sm:p-5 hover:border-gray-200 transition-colors bg-white flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-gray-900">Trotoar Rusak</h3>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Gatot Subroto, Jakarta <span class="text-gray-400">~0.31 km dari pusat</span>
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-23 • 16:45
                            </div>
                            <span>•</span>
                            <span>Pelapor: Budi Santoso</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                            Selesai
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Cluster 2: Street Lighting -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-base font-bold text-blue-700">Street Lighting</h2>
                    <p class="text-xs text-gray-500 mt-0.5">1 laporan dalam kategori ini • <span class="text-red-500 font-semibold">Ada laporan darurat</span></p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Cluster 2
                </span>
            </div>
            <div class="p-6 space-y-4">
                <!-- Report 1 -->
                <a href="{{ url('/laporan/detail?status=diproses') }}" class="block border border-red-100 rounded-xl p-4 sm:p-5 hover:border-red-200 transition-colors bg-red-50/20 flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Pusat Cluster</span>
                            <h3 class="text-sm font-bold text-gray-900">Lampu Jalan Mati</h3>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded border border-red-200 bg-red-50 text-[10px] font-bold text-red-600">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                DARURAT
                            </span>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Merdeka No. 15, Jakarta
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-24 • 09:15
                            </div>
                            <span>•</span>
                            <span>Pelapor: Siti Nurbaya</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                            Diproses
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Cluster 3: Drainage -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-base font-bold text-blue-700">Drainage</h2>
                    <p class="text-xs text-gray-500 mt-0.5">1 laporan dalam kategori ini</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Cluster 3
                </span>
            </div>
            <div class="p-6 space-y-4">
                <!-- Report 1 -->
                <a href="{{ url('/laporan/detail?status=ditolak') }}" class="block border border-gray-100 rounded-xl p-4 sm:p-5 hover:border-gray-200 transition-colors bg-white flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Pusat Cluster</span>
                            <h3 class="text-sm font-bold text-gray-900">Saluran Air Tersumbat</h3>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Ahmad Yani, Jakarta
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-23 • 10:20
                            </div>
                            <span>•</span>
                            <span>Pelapor: Dewi Lestari</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                            Ditolak
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Cluster 4: Traffic Signs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-base font-bold text-blue-700">Traffic Signs</h2>
                    <p class="text-xs text-gray-500 mt-0.5">1 laporan dalam kategori ini</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Cluster 4
                </span>
            </div>
            <div class="p-6 space-y-4">
                <a href="{{ url('/laporan/detail') }}" class="block border border-gray-100 rounded-xl p-4 sm:p-5 hover:border-gray-200 transition-colors bg-white flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Pusat Cluster</span>
                            <h3 class="text-sm font-bold text-gray-900">Rambu Lalu Lintas Hilang</h3>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Thamrin, Jakarta
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-22 • 13:00
                            </div>
                            <span>•</span>
                            <span>Pelapor: Eko Prasetyo</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                            Diajukan
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Cluster 5: Bridge & Overpass -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-base font-bold text-blue-700">Bridge & Overpass</h2>
                    <p class="text-xs text-gray-500 mt-0.5">1 laporan dalam kategori ini • <span class="text-red-500 font-semibold">Ada laporan darurat</span></p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Cluster 5
                </span>
            </div>
            <div class="p-6 space-y-4">
                <div class="border border-red-100 rounded-xl p-4 sm:p-5 hover:border-red-200 transition-colors bg-red-50/20 flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Pusat Cluster</span>
                            <h3 class="text-sm font-bold text-gray-900">Jembatan Penyeberangan Rusak</h3>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded border border-red-200 bg-red-50 text-[10px] font-bold text-red-600">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                DARURAT
                            </span>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Rasuna Said, Jakarta
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-22 • 08:30
                            </div>
                            <span>•</span>
                            <span>Pelapor: Fitri Handayani</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                            Diproses
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cluster 6: Road Marking -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-base font-bold text-blue-700">Road Marking</h2>
                    <p class="text-xs text-gray-500 mt-0.5">1 laporan dalam kategori ini</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Cluster 6
                </span>
            </div>
            <div class="p-6 space-y-4">
                <div class="border border-gray-100 rounded-xl p-4 sm:p-5 hover:border-gray-200 transition-colors bg-white flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Pusat Cluster</span>
                            <h3 class="text-sm font-bold text-gray-900">Marka Jalan Pudar</h3>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Kuningan, Jakarta
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-21 • 15:10
                            </div>
                            <span>•</span>
                            <span>Pelapor: Hendra Wijaya</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                            Selesai
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cluster 7: Others -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h2 class="text-base font-bold text-blue-700">Others</h2>
                    <p class="text-xs text-gray-500 mt-0.5">1 laporan dalam kategori ini • <span class="text-red-500 font-semibold">Ada laporan darurat</span></p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Cluster 7
                </span>
            </div>
            <div class="p-6 space-y-4">
                <div class="border border-red-100 rounded-xl p-4 sm:p-5 hover:border-red-200 transition-colors bg-red-50/20 flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                    <div class="space-y-2">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Pusat Cluster</span>
                            <h3 class="text-sm font-bold text-gray-900">Pohon Tumbang Menghalangi Jalan</h3>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded border border-red-200 bg-red-50 text-[10px] font-bold text-red-600">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                DARURAT
                            </span>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            Jl. Senopati, Jakarta
                        </div>
                        <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                2026-04-21 • 07:45
                            </div>
                            <span>•</span>
                            <span>Pelapor: Indah Permata</span>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                            Selesai
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
