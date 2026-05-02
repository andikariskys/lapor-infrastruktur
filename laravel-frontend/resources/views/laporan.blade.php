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
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row gap-3 items-center w-full">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-gray-800"
                    placeholder="Cari laporan, lokasi, atau pelapor...">
            </div>
            <div class="relative w-full sm:w-auto shrink-0">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                </div>
                <select
                    name="status"
                    onchange="location.href = '?status=' + this.value"
                    class="block w-full sm:w-48 pl-10 pr-8 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-gray-700 font-medium appearance-none bg-white cursor-pointer hover:bg-gray-50 transition-colors">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Diajukan</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                    <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>Ditolak (Spam)</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Category Lists -->
        <div class="space-y-6 w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h2 class="text-base font-bold text-blue-700">Semua Laporan</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ count($reports) }} laporan ditemukan</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($reports as $report)
                        <a href="{{ url('/laporan/' . $report['id']) }}"
                            class="block border border-gray-100 rounded-xl p-4 sm:p-5 hover:border-gray-200 transition-colors bg-white flex flex-col sm:flex-row gap-4 sm:items-start justify-between shadow-sm">
                            <div class="flex gap-4 items-start">
                                <div
                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden bg-gray-100 border border-gray-100 flex-shrink-0">
                                    <img src="{{ isset($report['photo_url']) ? config('app.backend_url') . $report['photo_url'] : 'https://via.placeholder.com/200?text=No+Image' }}"
                                        alt="Laporan" class="w-full h-full object-cover">
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-gray-900">{{ $report['description'] }}</h3>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500 gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                        </svg>
                                        Lat: {{ $report['latitude'] }}, Long: {{ $report['longitude'] }}
                                    </div>
                                    <div class="flex flex-wrap items-center text-xs text-gray-400 gap-2">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($report['created_at'])->format('Y-m-d H:i') }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                            {{ $report['author']['name'] ?? 'Anonim' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="shrink-0">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-orange-100 text-orange-700 border-orange-200',
                                        'verified' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'in_progress' => 'bg-sky-100 text-sky-700 border-sky-200',
                                        'resolved' => 'bg-green-100 text-green-700 border-green-200',
                                        'spam' => 'bg-red-100 text-red-700 border-red-200',
                                    ];
                                    $color = $statusColors[$report['status']] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }} border">
                                    {{ ucfirst(str_replace('_', ' ', $report['status'])) }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-500">Belum ada laporan yang masuk.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection