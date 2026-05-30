@extends('layouts.admin')

@section('title', 'Detail Laporan - Lapor Infrastruktur')
@section('title_mobile', 'Detail Laporan')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8">

        <!-- Back Button -->
        <a href="{{ url('/laporan') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Laporan
        </a>

        <!-- Alert Feedback -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-green-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 text-sm font-bold text-green-800">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <!-- Main Content Form -->
        <form action="{{ url('/laporan/' . $report['id']) }}" method="POST" class="space-y-6 lg:space-y-8">
            @csrf
            @method('PATCH')

            <!-- Header Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-blue-900 mb-3">{{ $report['category']['name'] ?? 'Kategori Umum' }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 font-medium">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                {{ \Carbon\Carbon::parse($report['created_at'])->format('Y-m-d • H:i') }}
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
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $color }} whitespace-nowrap self-start border shadow-sm uppercase tracking-wider">
                        {{ ucfirst(str_replace('_', ' ', $report['status'])) }}
                    </span>
                </div>

                @php
                    $currentAssignment = !empty($report['assignments']) ? end($report['assignments']) : null;
                    $currentOfficerId = $currentAssignment['officer_id'] ?? 0;
                    $currentInstitutionId = $currentAssignment['officer']['institution_id'] ?? 0;
                @endphp

                <!-- Location & Media Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                        <div class="flex items-center gap-2 text-blue-800 font-bold mb-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            Lokasi
                        </div>
                        <p class="text-sm font-medium text-gray-700 mb-1">Koordinat: {{ $report['latitude'] }},
                            {{ $report['longitude'] }}</p>
                        <p class="text-xs text-gray-500">Lokasi Infrastruktur Terdeteksi</p>
                    </div>

                    <div
                        class="bg-green-50 rounded-2xl border border-green-100 relative overflow-hidden h-32 flex items-center justify-center">
                        <div
                            class="absolute inset-0 opacity-20 bg-[url('https://www.google.com/maps/vt/pb=!1m4!1m3!1i14!2i8413!3i5385!2m3!1e0!2sm!3i407105169!3m8!2sid!3sUS!5e1105!12m4!1e68!2m2!1sset!2sRoadmap!4e0!5m1!5f2')] bg-cover">
                        </div>
                        <div
                            class="relative bg-white px-3 py-2 rounded-xl shadow-sm border border-green-200 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                            <p class="text-[10px] font-extrabold text-green-700 uppercase tracking-tight">GPS Location
                                Active</p>
                        </div>
                    </div>

                    <div class="bg-gray-100 rounded-2xl border border-gray-200 relative overflow-hidden h-32 group cursor-pointer"
                        onclick="openPhotoModal()">
                        <img src="{{ isset($report['photo_url']) ? config('app.backend_url') . $report['photo_url'] : 'https://via.placeholder.com/400x200?text=No+Image' }}"
                            alt="Capture"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div
                            class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-md p-2 rounded-full border border-white/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pelapor Info Section -->
                <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6">
                    <div class="flex items-center gap-2 text-blue-800 font-bold mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Informasi Pelapor
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Nama</p>
                            <p class="text-sm font-bold text-gray-900">{{ $report['author']['name'] ?? 'Anonim' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Email</p>
                            <p class="text-sm font-bold text-gray-900">{{ $report['author']['email'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Telepon</p>
                            <p class="text-sm font-bold text-gray-900">{{ $report['author']['phone'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Progres & Bukti Penyelesaian Petugas -->
                @if(isset($report['completion_photo']) || ($currentAssignment && !empty($currentAssignment['note'])) || !empty($report['officer_reply']))
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <div class="flex items-center gap-2 text-green-700 font-bold mb-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                            Bukti Penyelesaian Petugas
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                            @if(isset($report['completion_photo']))
                                <div class="bg-gray-100 rounded-2xl border border-gray-200 relative overflow-hidden h-40 group cursor-pointer" onclick="openResolutionPhotoModal()">
                                    <img src="{{ config('app.backend_url') . $report['completion_photo'] }}"
                                        alt="Bukti Perbaikan"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors flex items-center justify-center">
                                        <span class="text-white text-xs font-bold bg-black/50 px-3 py-1.5 rounded-full backdrop-blur-sm">Lihat Foto Bukti</span>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="md:col-span-2 space-y-4">
                                {{-- Catatan Penugasan dari Admin --}}
                                @if($currentAssignment && !empty($currentAssignment['note']))
                                    <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-5">
                                        <p class="text-[10px] uppercase font-bold text-blue-600 tracking-wider mb-2">Catatan Penugasan (Admin)</p>
                                        <p class="text-sm text-gray-700 leading-relaxed font-semibold italic">
                                            "{{ $currentAssignment['note'] }}"
                                        </p>
                                        <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
                                            <span class="font-bold text-gray-700">{{ $currentAssignment['officer']['name'] ?? 'Petugas' }}</span>
                                            <span>•</span>
                                            <span>Ditugaskan pada {{ \Carbon\Carbon::parse($currentAssignment['assigned_at'] ?? now())->format('Y-m-d H:i') }} WIB</span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Balasan Progres dari Petugas --}}
                                @if(!empty($report['officer_reply']))
                                    <div class="bg-green-50/50 rounded-2xl border border-green-100 p-5">
                                        <p class="text-[10px] uppercase font-bold text-green-600 tracking-wider mb-2">Balasan Progres (Petugas)</p>
                                        <p class="text-sm text-gray-700 leading-relaxed font-semibold italic">
                                            "{{ $report['officer_reply'] }}"
                                        </p>
                                        <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
                                            <span class="font-bold text-gray-700">{{ $currentAssignment['officer']['name'] ?? 'Petugas' }}</span>
                                            <span>•</span>
                                            <span>Diupdate pada {{ \Carbon\Carbon::parse($report['updated_at'] ?? now())->format('Y-m-d H:i') }} WIB</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Ulasan & Rating Warga -->
                @php
                    $currentFeedback = !empty($report['feedbacks']) ? $report['feedbacks'][0] : null;
                @endphp
                @if($currentFeedback)
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <div class="flex items-center gap-2 text-orange-600 font-bold mb-4">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.158-.326.608-.326.765 0l1.597 3.238 3.57 1.055c.362.107.507.553.243.812l-2.585 2.531.616 3.557c.063.364-.32.643-.642.47L12 10.978l-3.197 1.674c-.322.172-.705-.107-.642-.47l.616-3.557-2.585-2.531c-.264-.259-.119-.705.243-.812l3.57-1.055 1.597-3.238Z" />
                            </svg>
                            Ulasan & Rating Kepuasan Pelapor
                        </div>
                        <div class="bg-orange-50/50 rounded-2xl border border-orange-100 p-5">
                            <div class="flex items-center gap-1.5 mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $currentFeedback['rating'] ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm font-extrabold text-orange-700">({{ $currentFeedback['rating'] }} / 5)</span>
                            </div>
                            <p class="text-sm text-gray-700 leading-relaxed font-semibold italic">
                                "{{ $currentFeedback['content'] }}"
                            </p>
                            <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
                                <span class="font-bold text-gray-700">{{ $currentFeedback['user']['name'] ?? 'Pelapor' }}</span>
                                <span>•</span>
                                <span>Dikirim pada {{ \Carbon\Carbon::parse($currentFeedback['created_at'] ?? now())->format('Y-m-d H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Deskripsi -->
                <div class="mt-8">
                    <h3 class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Deskripsi Masalah</h3>
                    <p class="text-sm text-gray-700 leading-relaxed font-medium italic">
                        "{{ $report['description'] }}"
                    </p>
                </div>
            </div>

            <!-- Verifikasi Laporan Section -->
            <div id="verification-section" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 {{ in_array($report['status'], ['in_progress', 'resolved']) ? 'opacity-50 pointer-events-none' : '' }}">
                <div class="flex items-center gap-2 text-blue-800 font-bold mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    Verifikasi Laporan
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="verified" class="peer hidden" {{ $report['status'] == 'verified' ? 'checked' : '' }}>
                        <div
                            class="flex flex-col items-center justify-center p-6 border-2 border-gray-100 rounded-2xl bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all hover:border-gray-200">
                            <div
                                class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3 peer-checked:bg-blue-100">
                                <svg class="w-6 h-6 text-gray-400 peer-checked:text-blue-600" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900">Laporan Valid</p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="status" value="spam" class="peer hidden" {{ $report['status'] == 'spam' ? 'checked' : '' }}>
                        <div
                            class="flex flex-col items-center justify-center p-6 border-2 border-gray-100 rounded-2xl bg-white peer-checked:border-red-600 peer-checked:bg-red-50 transition-all hover:border-gray-200">
                            <div
                                class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3 peer-checked:bg-red-100">
                                <svg class="w-6 h-6 text-gray-400 peer-checked:text-red-600" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-gray-900">Laporan Spam</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Penugasan Laporan Section -->
            <div id="assignment-section" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 {{ $report['status'] !== 'verified' ? 'opacity-50 pointer-events-none' : '' }}">
                <div class="flex items-center gap-2 text-blue-800 font-bold mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.998 5.998 0 0 0-12 0m12 0c0-1.31-.302-2.547-.842-3.645m-9.158 3.645A5.998 5.998 0 0 1 12 15a5.998 5.998 0 0 1 4.318 1.855M12 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 2.25a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM5.25 9.25a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    Teruskan ke Petugas
                </div>
                <p class="text-xs text-gray-500 mb-6">Pilih instansi dan petugas pelaksana</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Lembaga Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Pilih Lembaga /
                            Instansi *</label>
                        <select id="select-institution" name="institution_id" onchange="filterOfficers()"
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white shadow-sm font-bold">
                            <option value="">-- Pilih Lembaga --</option>
                             @foreach($institutions as $inst)
                                <option value="{{ $inst['id'] }}" {{ ($currentInstitutionId == $inst['id']) ? 'selected' : '' }}>{{ $inst['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Petugas Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Pilih Petugas
                            Pelaksana *</label>
                        <select id="select-officer" name="officer_id" disabled
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 bg-gray-50 shadow-sm font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="">-- Pilih Instansi Terlebih Dahulu --</option>
                             @foreach($officers as $off)
                                <option value="{{ $off['id'] }}" data-institution="{{ $off['institution_id'] }}" {{ ($currentOfficerId == $off['id']) ? 'selected' : '' }}>{{ $off['name'] }}
                                    ({{ $off['email'] }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Pesan Admin ke Petugas -->
                <div class="mt-6">
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Pesan untuk Petugas</label>
                    <textarea id="note-textarea" name="note" rows="3" disabled placeholder="Tulis catatan atau instruksi untuk petugas pelaksana..."
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white shadow-sm font-medium resize-none"></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-end pb-12">
                <button type="submit"
                    class="w-full sm:w-auto px-12 py-3.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-2xl shadow-lg shadow-blue-100 transition-all active:scale-95 text-sm uppercase tracking-widest">
                    Simpan Perubahan
                </button>
                <a href="{{ url('/laporan') }}"
                    class="w-full sm:w-auto px-8 py-3.5 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-bold rounded-2xl transition-all text-center text-sm uppercase tracking-widest">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Photo Lightbox Modal -->
    <div id="photo-modal"
        class="fixed inset-0 z-50 bg-black/95 hidden flex flex-col items-center justify-center backdrop-blur-md opacity-0 transition-opacity duration-300">
        <div
            class="absolute top-0 left-0 right-0 p-6 flex justify-between items-center bg-gradient-to-b from-black/80 to-transparent">
            <h3 class="text-white font-bold text-lg">Foto Bukti Laporan</h3>
            <button onclick="closePhotoModal()"
                class="text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2.5 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="w-full h-full p-4 sm:p-12 flex items-center justify-center pt-24">
            <img src="{{ isset($report['photo_url']) ? config('app.backend_url') . $report['photo_url'] : 'https://via.placeholder.com/1200?text=No+Image' }}"
                alt="Bukti Foto" class="max-h-full max-w-full object-contain rounded-2xl shadow-2xl ring-1 ring-white/10">
        </div>
    </div>

    <!-- Resolution Photo Lightbox Modal -->
    @if(isset($report['completion_photo']))
        <div id="resolution-photo-modal"
            class="fixed inset-0 z-50 bg-black/95 hidden flex flex-col items-center justify-center backdrop-blur-md opacity-0 transition-opacity duration-300">
            <div
                class="absolute top-0 left-0 right-0 p-6 flex justify-between items-center bg-gradient-to-b from-black/80 to-transparent">
                <h3 class="text-white font-bold text-lg">Foto Bukti Perbaikan (Penyelesaian)</h3>
                <button onclick="closeResolutionPhotoModal()"
                    class="text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2.5 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="w-full h-full p-4 sm:p-12 flex items-center justify-center pt-24">
                <img src="{{ config('app.backend_url') . $report['completion_photo'] }}"
                    alt="Bukti Perbaikan" class="max-h-full max-w-full object-contain rounded-2xl shadow-2xl ring-1 ring-white/10">
            </div>
        </div>
    @endif

    <script>
        const photoModal = document.getElementById('photo-modal');
        const resolutionPhotoModal = document.getElementById('resolution-photo-modal');
        const selectInstitution = document.getElementById('select-institution');
        const selectOfficer = document.getElementById('select-officer');
        const officerOptions = Array.from(selectOfficer.options);
        const assignmentSection = document.getElementById('assignment-section');
        const noteTextarea = document.getElementById('note-textarea');
        const statusRadios = document.querySelectorAll('input[name="status"]');

        function filterOfficers() {
            const institutionId = selectInstitution.value;

            // Reset and clear current options
            selectOfficer.innerHTML = '<option value="">-- Pilih Petugas Pelaksana --</option>';

            if (!institutionId) {
                selectOfficer.disabled = true;
                selectOfficer.classList.add('bg-gray-50');
                selectOfficer.innerHTML = '<option value="">-- Pilih Instansi Terlebih Dahulu --</option>';
                return;
            }

            // Filter and add matching options
            const filtered = officerOptions.filter(opt => opt.dataset.institution === institutionId);

            if (filtered.length > 0) {
                selectOfficer.disabled = false;
                selectOfficer.classList.remove('bg-gray-50');
                filtered.forEach(opt => selectOfficer.appendChild(opt.cloneNode(true)));
            } else {
                selectOfficer.disabled = true;
                selectOfficer.classList.add('bg-gray-50');
                selectOfficer.innerHTML = '<option value="">(Belum ada petugas di instansi ini)</option>';
            }
        }

        function toggleAssignmentSection(enabled) {
            if (enabled) {
                assignmentSection.classList.remove('opacity-50', 'pointer-events-none');
                selectInstitution.disabled = false;
                if (noteTextarea) noteTextarea.disabled = false;
            } else {
                assignmentSection.classList.add('opacity-50', 'pointer-events-none');
                selectInstitution.disabled = true;
                selectOfficer.disabled = true;
                if (noteTextarea) noteTextarea.disabled = true;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isDisabledStatus = @json(in_array($report['status'], ['in_progress', 'resolved']));

            if (!isDisabledStatus) {
                // Toggle assignment section based on current radio selection
                const checkedRadio = document.querySelector('input[name="status"]:checked');
                toggleAssignmentSection(checkedRadio && checkedRadio.value === 'verified');

                // Listen for radio changes
                statusRadios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        toggleAssignmentSection(radio.value === 'verified');
                    });
                });
            }

            // Filter officers if institution already selected and section is enabled
            if (selectInstitution.value && !selectInstitution.disabled) {
                const currentOfficerId = "{{ $currentOfficerId }}";
                filterOfficers();
                selectOfficer.value = currentOfficerId;
            }
        });

        function openPhotoModal() {
            photoModal.classList.remove('hidden');
            setTimeout(() => {
                photoModal.classList.remove('opacity-0');
                photoModal.classList.add('opacity-100');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            photoModal.classList.add('opacity-0');
            photoModal.classList.remove('opacity-100');
            setTimeout(() => {
                photoModal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        function openResolutionPhotoModal() {
            if (resolutionPhotoModal) {
                resolutionPhotoModal.classList.remove('hidden');
                setTimeout(() => {
                    resolutionPhotoModal.classList.remove('opacity-0');
                    resolutionPhotoModal.classList.add('opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            }
        }

        function closeResolutionPhotoModal() {
            if (resolutionPhotoModal) {
                resolutionPhotoModal.classList.add('opacity-0');
                resolutionPhotoModal.classList.remove('opacity-100');
                setTimeout(() => {
                    resolutionPhotoModal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (photoModal && !photoModal.classList.contains('hidden')) {
                    closePhotoModal();
                }
                if (resolutionPhotoModal && !resolutionPhotoModal.classList.contains('hidden')) {
                    closeResolutionPhotoModal();
                }
            }
        });
    </script>
@endsection