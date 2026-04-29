@extends('layouts.admin')

@section('title', 'Lembaga - Lapor Infrastruktur')
@section('title_mobile', 'Lembaga')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8">
    
    <!-- Page Header -->
    <div class="hidden lg:block">
        <h1 class="text-2xl font-bold text-blue-800 mb-1">Kelola Lembaga</h1>
        <p class="text-sm text-gray-500">Tambah, edit, atau hapus lembaga/perusahaan</p>
    </div>

    <!-- Search and Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row gap-3 items-center w-full">
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-gray-800" placeholder="Cari lembaga...">
        </div>
        <button class="w-full sm:w-auto shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Lembaga
        </button>
    </div>

    <!-- Organizations List -->
    <div class="space-y-4">

        <!-- Item 1 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 hover:shadow-md transition-shadow relative">
            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <!-- Logo -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=150&h=150&q=80" alt="Dinas Pekerjaan Umum Jakarta" class="w-full h-full object-cover">
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0 w-full pr-12 sm:pr-0">
                    <h2 class="text-base sm:text-lg font-bold text-blue-800 mb-1">Dinas Pekerjaan Umum Jakarta</h2>
                    <p class="text-sm text-gray-600 mb-4">Mengelola infrastruktur jalan dan jembatan</p>

                    <div class="flex flex-wrap gap-4 sm:gap-6 lg:gap-10">
                        <div class="space-y-1 w-full sm:w-auto">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                Alamat
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800">Jl. Thamrin No. 10, Jakarta Pusat</p>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.496-4.196-7.092-7.092l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                Telepon
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800">021-12345678</p>
                        </div>
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                Email
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800 truncate">info@pu.jakarta.go.id</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="absolute top-4 sm:top-5 right-4 sm:right-6 flex items-center gap-1.5">
                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                </button>
                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                </button>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 hover:shadow-md transition-shadow relative">
            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <!-- Logo -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=150&h=150&q=80" alt="PLN Jakarta Raya" class="w-full h-full object-cover">
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0 w-full pr-12 sm:pr-0">
                    <h2 class="text-base sm:text-lg font-bold text-blue-800 mb-1">PLN Jakarta Raya</h2>
                    <p class="text-sm text-gray-600 mb-4">Pengelola listrik dan penerangan umum</p>

                    <div class="flex flex-wrap gap-4 sm:gap-6 lg:gap-10">
                        <div class="space-y-1 w-full sm:w-auto">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                Alamat
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800">Jl. Sudirman No. 50, Jakarta Selatan</p>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.496-4.196-7.092-7.092l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                Telepon
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800">021-87654321</p>
                        </div>
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                Email
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800 truncate">cs@pln.co.id</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="absolute top-4 sm:top-5 right-4 sm:right-6 flex items-center gap-1.5">
                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                </button>
                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                </button>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 hover:shadow-md transition-shadow relative">
            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <!-- Logo -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 shrink-0 rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=150&h=150&q=80" alt="PDAM Jakarta" class="w-full h-full object-cover">
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0 w-full pr-12 sm:pr-0">
                    <h2 class="text-base sm:text-lg font-bold text-blue-800 mb-1">PDAM Jakarta</h2>
                    <p class="text-sm text-gray-600 mb-4">Pengelola air bersih dan sanitasi</p>

                    <div class="flex flex-wrap gap-4 sm:gap-6 lg:gap-10">
                        <div class="space-y-1 w-full sm:w-auto">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                Alamat
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800">Jl. Gatot Subroto No. 30, Jakarta</p>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.496-4.196-7.092-7.092l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                Telepon
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800">021-11223344</p>
                        </div>
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center text-xs text-blue-600 gap-1.5 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                Email
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-gray-800 truncate">contact@pdam.jakarta.go.id</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="absolute top-4 sm:top-5 right-4 sm:right-6 flex items-center gap-1.5">
                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                </button>
                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Modal Overlay -->
<div id="modal-overlay" class="fixed inset-0 bg-black/50 z-40 hidden flex items-center justify-center p-4">
    
    <!-- Modal Lembaga Form (Tambah & Edit) -->
    <div id="modal-lembaga" class="bg-white rounded-2xl shadow-xl w-full max-w-lg hidden max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 id="modal-lembaga-title" class="text-xl font-bold text-blue-800">Edit Lembaga</h2>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form class="space-y-4">
                <!-- Logo -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Upload Logo *</label>
                    <input type="file" id="lembaga-logo" accept="image/*" onchange="previewImage(event)" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[11px] text-gray-400 mt-1">Upload file logo lembaga (disarankan ukuran 400x400px)</p>
                </div>

                <!-- Preview Logo (Hidden initially for Tambah) -->
                <div id="preview-container" class="hidden">
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Preview Logo</label>
                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                        <img id="logo-preview" src="" alt="Preview" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Nama Lembaga -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Nama Lembaga *</label>
                    <input type="text" id="lembaga-nama" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Contoh: Dinas Pekerjaan Umum">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Deskripsi</label>
                    <textarea id="lembaga-deskripsi" rows="3" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none" placeholder="Jelaskan tugas dan fungsi lembaga..."></textarea>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Alamat</label>
                    <textarea id="lembaga-alamat" rows="2" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none" placeholder="Alamat lengkap lembaga"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Telepon</label>
                        <input type="text" id="lembaga-telepon" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="021-12345678">
                    </div>
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Email</label>
                        <input type="email" id="lembaga-email" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="info@lembaga.go.id">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row gap-3">
                    <button type="button" id="btn-submit-lembaga" class="w-full sm:flex-1 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeModal()" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-blue-700 rounded-xl text-sm font-semibold hover:bg-blue-50 transition-colors shadow-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const overlay = document.getElementById('modal-overlay');
    const modalLembaga = document.getElementById('modal-lembaga');
    const previewContainer = document.getElementById('preview-container');
    const logoPreview = document.getElementById('logo-preview');

    function closeModal() {
        overlay.classList.add('hidden');
        modalLembaga.classList.add('hidden');
    }

    function openLembagaModal(mode, data = null) {
        // Reset form
        document.getElementById('lembaga-logo').value = '';
        document.getElementById('lembaga-nama').value = '';
        document.getElementById('lembaga-deskripsi').value = '';
        document.getElementById('lembaga-alamat').value = '';
        document.getElementById('lembaga-telepon').value = '';
        document.getElementById('lembaga-email').value = '';
        logoPreview.src = '';
        previewContainer.classList.add('hidden');

        if (mode === 'tambah') {
            document.getElementById('modal-lembaga-title').innerText = 'Tambah Lembaga Baru';
            document.getElementById('btn-submit-lembaga').innerText = 'Tambah Lembaga';
        } else if (mode === 'edit' && data) {
            document.getElementById('modal-lembaga-title').innerText = 'Edit Lembaga';
            document.getElementById('btn-submit-lembaga').innerText = 'Simpan Perubahan';

            // Populate data
            document.getElementById('lembaga-nama').value = data.nama;
            document.getElementById('lembaga-deskripsi').value = data.deskripsi || '';
            document.getElementById('lembaga-alamat').value = data.alamat;
            document.getElementById('lembaga-telepon').value = data.telepon;
            document.getElementById('lembaga-email').value = data.email;

            // Tampilkan preview logo jika ada logo awal
            if (data.logoUrl) {
                logoPreview.src = data.logoUrl;
                previewContainer.classList.remove('hidden');
            }
        }

        // Show modal
        overlay.classList.remove('hidden');
        modalLembaga.classList.remove('hidden');
    }

    // Handle file preview
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            logoPreview.src = '';
            previewContainer.classList.add('hidden');
        }
    }

    // Attach click listener for Tambah button (on top bar)
    document.querySelector('button:has(svg path[d="M12 4.5v15m7.5-7.5h-15"])').setAttribute('onclick', "openLembagaModal('tambah')");

    // Attach click listeners for Edit buttons in the table/list
    // Example: Edit Dinas Pekerjaan Umum
    const editBtn1 = document.querySelectorAll('.bg-white.rounded-2xl .text-blue-600.hover\\:bg-blue-50')[0];
    if(editBtn1) {
        editBtn1.setAttribute('onclick', "openLembagaModal('edit', {nama: 'Dinas Pekerjaan Umum Jakarta', deskripsi: 'Mengelola infrastruktur jalan dan jembatan', alamat: 'Jl. Thamrin No. 10, Jakarta Pusat', telepon: '021-12345678', email: 'info@pu.jakarta.go.id', logoUrl: 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=150&h=150&q=80'})");
    }

    // Example: Edit PLN
    const editBtn2 = document.querySelectorAll('.bg-white.rounded-2xl .text-blue-600.hover\\:bg-blue-50')[1];
    if(editBtn2) {
        editBtn2.setAttribute('onclick', "openLembagaModal('edit', {nama: 'PLN Jakarta Raya', deskripsi: 'Pengelola listrik dan penerangan umum', alamat: 'Jl. Sudirman No. 50, Jakarta Selatan', telepon: '021-87654321', email: 'cs@pln.co.id', logoUrl: 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=150&h=150&q=80'})");
    }

    // Example: Edit PDAM
    const editBtn3 = document.querySelectorAll('.bg-white.rounded-2xl .text-blue-600.hover\\:bg-blue-50')[2];
    if(editBtn3) {
        editBtn3.setAttribute('onclick', "openLembagaModal('edit', {nama: 'PDAM Jakarta', deskripsi: 'Pengelola air bersih dan sanitasi', alamat: 'Jl. Gatot Subroto No. 30, Jakarta', telepon: '021-11223344', email: 'contact@pdam.jakarta.go.id', logoUrl: 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=150&h=150&q=80'})");
    }

    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeModal();
        }
    });
</script>
@endsection
