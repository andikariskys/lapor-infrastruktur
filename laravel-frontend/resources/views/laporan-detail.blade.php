@extends('layouts.admin')

@section('title', 'Detail Laporan - Lapor Infrastruktur')
@section('title_mobile', 'Detail Laporan')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8">
    
    <!-- Back Button -->
    <a href="{{ url('/laporan') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Kembali ke Daftar Laporan
    </a>

    <!-- Header Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-blue-900 mb-3">{{ $report['description'] }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 font-medium">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                        {{ \Carbon\Carbon::parse($report['created_at'])->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
            
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
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $color }} whitespace-nowrap self-start border">
                {{ ucfirst(str_replace('_', ' ', $report['status'])) }}
            </span>
        </div>

        <!-- Location & Media Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex flex-col justify-center">
                <div class="flex items-center gap-2 text-blue-800 font-bold mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    Lokasi
                </div>
                <p class="text-sm font-medium text-gray-800 mb-1">Koordinat: {{ $report['latitude'] }}, {{ $report['longitude'] }}</p>
            </div>

            <div class="bg-green-50 rounded-2xl border border-green-100 relative overflow-hidden h-28 md:h-auto flex items-center justify-center">
                <div class="relative bg-white px-3 py-2 rounded-lg shadow-sm border border-green-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <p class="text-[10px] font-bold text-green-700">GPS LOCATION ACTIVE</p>
                </div>
            </div>

            <div class="bg-gray-100 rounded-2xl border border-gray-200 relative overflow-hidden h-28 md:h-auto flex items-center justify-center group">
                <img src="{{ $report['photo_url'] ?? 'https://via.placeholder.com/400x200?text=No+Image' }}" alt="Capture" class="absolute inset-0 w-full h-full object-cover">
            </div>
        </div>

        <!-- Pelapor Info -->
        <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 mb-8">
            <div class="flex items-center gap-2 text-blue-800 font-bold mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                Informasi Laporan
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs text-gray-500 mb-1">ID Pelapor</p>
                    <p class="text-sm font-bold text-gray-900">{{ $report['user_id'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">ID Laporan</p>
                    <p class="text-sm font-bold text-gray-900">#{{ $report['id'] }}</p>
                </div>
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <h3 class="text-sm font-bold text-gray-900 mb-2">Deskripsi Lengkap</h3>
            <p class="text-sm text-gray-600 leading-relaxed">
                {{ $report['description'] }}
            </p>
        </div>
    </div>
</div>

<!-- Photo Lightbox Modal -->
<div id="photo-modal" class="fixed inset-0 z-50 bg-black/90 hidden flex flex-col items-center justify-center backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <!-- Top Bar -->
    <div class="absolute top-0 left-0 right-0 p-4 sm:p-6 flex justify-between items-center bg-gradient-to-b from-black/60 to-transparent">
        <h3 class="text-white font-medium text-sm sm:text-base">Foto Bukti Laporan</h3>
        <button onclick="closePhotoModal()" class="text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full p-2 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    
    <!-- Image Container -->
    <div class="w-full h-full max-w-5xl mx-auto p-4 sm:p-12 flex items-center justify-center pt-20">
        <img src="{{ $report['photo_url'] ?? 'https://via.placeholder.com/1200?text=No+Image' }}" alt="Bukti Foto" class="max-h-full max-w-full object-contain rounded-lg shadow-2xl">
    </div>
</div>

<script>
    const photoModal = document.getElementById('photo-modal');

    function openPhotoModal() {
        photoModal.classList.remove('hidden');
        // Small delay to allow display:block to apply before changing opacity for transition
        setTimeout(() => {
            photoModal.classList.remove('opacity-0');
            photoModal.classList.add('opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    function closePhotoModal() {
        photoModal.classList.remove('opacity-100');
        photoModal.classList.add('opacity-0');
        // Wait for transition to finish before hiding
        setTimeout(() => {
            photoModal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }, 300);
    }

    // Close on click outside image
    photoModal.addEventListener('click', function(e) {
        if (e.target === photoModal || e.target.closest('.flex.items-center.justify-center.pt-20') === e.target) {
            closePhotoModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !photoModal.classList.contains('hidden')) {
            closePhotoModal();
        }
    });
</script>

@endsection
