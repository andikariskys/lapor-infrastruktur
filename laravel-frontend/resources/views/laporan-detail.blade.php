@extends('layouts.admin')

@section('title', 'Detail Laporan - Lapor Infrastruktur')
@section('title_mobile', 'Detail Laporan')

@section('content')
@php
    $status = request()->query('status', 'diajukan');
@endphp
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
                <h1 class="text-2xl sm:text-3xl font-bold text-blue-900 mb-3">Jalan Berlubang di Jl. Sudirman</h1>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 font-medium">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                        2026-04-25 • 14:30
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                        Kerusakan Jalan
                    </div>
                </div>
            </div>
            
            @if($status == 'diajukan')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-orange-100 text-orange-700 whitespace-nowrap self-start">
                Diajukan
            </span>
            @elseif($status == 'diproses')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-blue-100 text-blue-700 whitespace-nowrap self-start border border-blue-200">
                Diproses
            </span>
            @elseif($status == 'selesai')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700 whitespace-nowrap self-start border border-green-200">
                Selesai
            </span>
            @elseif($status == 'ditolak')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-700 whitespace-nowrap self-start border border-red-200">
                Ditolak
            </span>
            @endif
        </div>

        <!-- Location & Media Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Lokasi Text -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex flex-col justify-center">
                <div class="flex items-center gap-2 text-blue-800 font-bold mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    Lokasi
                </div>
                <p class="text-sm font-medium text-gray-800 mb-1">Jl. Sudirman No. 42, Jakarta</p>
                <p class="text-xs text-gray-500">Koordinat: -7.534587, 110.838543</p>
            </div>

            <!-- GPS Map Placeholder -->
            <div class="bg-green-50 rounded-2xl border border-green-100 relative overflow-hidden h-28 md:h-auto flex items-center justify-center">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#16a34a 1px, transparent 1px); background-size: 10px 10px;"></div>
                <div class="relative bg-white px-3 py-2 rounded-lg shadow-sm border border-green-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <div>
                        <p class="text-[10px] font-bold text-green-700 leading-tight">GPS ACTIVE</p>
                        <p class="text-[10px] text-gray-600 leading-tight">Sudirman St. No. 42, Jakarta</p>
                    </div>
                </div>
            </div>

            <!-- Photo/Video Placeholder -->
            <div onclick="openPhotoModal()" class="bg-gray-100 rounded-2xl border border-gray-200 relative overflow-hidden h-28 md:h-auto flex items-center justify-center group cursor-pointer hover:bg-gray-200 transition-colors">
                <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=400&h=200&q=80" alt="Capture" class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-multiply group-hover:opacity-40 transition-opacity">
                <div class="relative text-center">
                    <svg class="w-8 h-8 text-orange-500 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg>
                    <p class="text-xs font-bold text-white shadow-sm">Lihat Foto / <br> Video</p>
                </div>
            </div>
        </div>

        <!-- Pelapor Info -->
        <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 mb-8">
            <div class="flex items-center gap-2 text-blue-800 font-bold mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                Informasi Pelapor
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Nama</p>
                    <p class="text-sm font-bold text-gray-900">Ahmad Fauzi</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Email</p>
                    <p class="text-sm font-bold text-gray-900">ahmad.fauzi@email.com</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Telepon</p>
                    <p class="text-sm font-bold text-gray-900">0812-3456-7890</p>
                </div>
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <h3 class="text-sm font-bold text-gray-900 mb-2">Deskripsi Masalah</h3>
            <p class="text-sm text-gray-600 leading-relaxed">
                Terdapat lubang besar di tengah jalan yang sangat berbahaya bagi pengendara motor. Lubang berdiameter sekitar 50cm dengan kedalaman 20cm. Sudah ada beberapa pengendara yang terjatuh akibat lubang ini.
            </p>
        </div>
    </div>

    @if($status == 'diajukan')
    <!-- Verifikasi Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <div class="flex items-center gap-2 text-blue-800 font-bold mb-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <h2 class="text-xl">Verifikasi Laporan</h2>
        </div>
        <p class="text-sm text-gray-500 mb-6">Periksa dan verifikasi apakah laporan ini valid atau spam</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Valid Option -->
            <label class="relative cursor-pointer">
                <input type="radio" name="verifikasi" class="peer sr-only" value="valid">
                <div class="p-6 rounded-2xl border-2 border-gray-200 hover:border-blue-200 hover:bg-blue-50/50 transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center">
                    <svg class="w-8 h-8 mx-auto mb-3 text-gray-400 peer-checked:text-blue-600 transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                    <h3 class="font-bold text-gray-900 mb-1">Laporan Valid</h3>
                    <p class="text-xs text-gray-500">Laporan ini asli dan perlu ditindaklanjuti</p>
                </div>
            </label>

            <!-- Spam Option -->
            <label class="relative cursor-pointer">
                <input type="radio" name="verifikasi" class="peer sr-only" value="spam">
                <div class="p-6 rounded-2xl border-2 border-gray-200 hover:border-red-200 hover:bg-red-50/50 transition-all peer-checked:border-red-600 peer-checked:bg-red-50 text-center">
                    <svg class="w-8 h-8 mx-auto mb-3 text-gray-400 peer-checked:text-red-600 transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z"/></svg>
                    <h3 class="font-bold text-gray-900 mb-1">Laporan Spam</h3>
                    <p class="text-xs text-gray-500">Laporan ini palsu atau tidak relevan</p>
                </div>
            </label>
        </div>
    </div>

    <!-- Teruskan Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <div class="flex items-center gap-2 text-blue-800 font-bold mb-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
            <h2 class="text-xl">Teruskan ke Petugas</h2>
        </div>
        <p class="text-sm text-gray-500 mb-6">Pilih petugas yang akan menangani laporan ini</p>

        <form class="space-y-6">
            <!-- Pilih Petugas -->
            <div>
                <label class="block text-sm font-bold text-gray-900 mb-2">Pilih Petugas</label>
                <select class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    <option value="" disabled selected>Pilih petugas...</option>
                    <option value="1">Petugas Jalan (Dinas PU)</option>
                    <option value="2">Petugas Listrik (PLN)</option>
                </select>
            </div>

            <!-- Catatan Admin -->
            <div>
                <label class="block text-sm font-bold text-gray-900 mb-2">Catatan Admin (Opsional)</label>
                <textarea rows="4" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none" placeholder="Tambahkan catatan untuk petugas..."></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                <button type="button" class="w-full sm:flex-1 py-3 bg-blue-700 text-white rounded-xl text-sm font-bold hover:bg-blue-800 transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ url('/laporan') }}" class="w-full sm:w-auto px-8 py-3 bg-white border border-gray-300 text-blue-700 rounded-xl text-sm font-bold hover:bg-blue-50 transition-colors shadow-sm text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
    @elseif($status == 'diproses')
    <!-- Status Pengerjaan Section -->
    <div class="bg-blue-50 rounded-3xl border border-blue-100 p-6 sm:p-8">
        <div class="flex items-center gap-2 text-blue-800 font-bold mb-6">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <h2 class="text-xl">Sedang Dalam Penanganan</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-white p-6 rounded-2xl border border-blue-100 shadow-sm">
            <div>
                <p class="text-xs text-gray-500 mb-1">Diteruskan Kepada</p>
                <p class="text-sm font-bold text-gray-900">Petugas Jalan (Dinas PU)</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Waktu Diteruskan</p>
                <p class="text-sm font-bold text-gray-900">2026-04-25 15:00 WIB</p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs text-gray-500 mb-1">Catatan Admin</p>
                <p class="text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100">"Mohon segera ditindaklanjuti, mengingat jalan ini merupakan jalur arteri yang padat."</p>
            </div>
        </div>
    </div>
    @elseif($status == 'selesai')
    <!-- Hasil Penanganan Section -->
    <div class="bg-green-50 rounded-3xl border border-green-100 p-6 sm:p-8">
        <div class="flex items-center gap-2 text-green-800 font-bold mb-6">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <h2 class="text-xl">Laporan Telah Diselesaikan</h2>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-green-100 shadow-sm space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Ditangani Oleh</p>
                    <p class="text-sm font-bold text-gray-900">Budi (Dinas PU)</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Waktu Penyelesaian</p>
                    <p class="text-sm font-bold text-gray-900">2026-04-26 09:30 WIB</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-2">Foto Hasil Perbaikan</p>
                <div class="w-40 h-28 rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=400&h=200&q=80" alt="Hasil Perbaikan" class="w-full h-full object-cover">
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Catatan Petugas</p>
                <p class="text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100">"Lubang telah ditambal dengan aspal baru dan diratakan dengan *roller*. Jalan sudah aman untuk dilalui kendaraan kembali."</p>
            </div>
        </div>
    </div>
    @elseif($status == 'ditolak')
    <!-- Penolakan Section -->
    <div class="bg-red-50 rounded-3xl border border-red-100 p-6 sm:p-8">
        <div class="flex items-center gap-2 text-red-800 font-bold mb-6">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z"/></svg>
            <h2 class="text-xl">Laporan Ditolak</h2>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-red-100 shadow-sm space-y-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Ditolak Oleh</p>
                <p class="text-sm font-bold text-gray-900">Admin Pusat</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Waktu Penolakan</p>
                <p class="text-sm font-bold text-gray-900">2026-04-25 14:45 WIB</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Alasan Penolakan</p>
                <p class="text-sm text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100 text-red-800 font-medium">"Laporan ini terindikasi spam/duplikat. Kami telah menerima laporan yang sama persis di titik ini dan sedang dalam proses penanganan oleh tim PU."</p>
            </div>
        </div>
    </div>
    @endif

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
        <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=1200&q=80" alt="Bukti Foto" class="max-h-full max-w-full object-contain rounded-lg shadow-2xl">
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
