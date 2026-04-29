@extends('layouts.admin')

@section('title', 'Kategori - Lapor Infrastruktur')
@section('title_mobile', 'Kategori')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8">
    
    <!-- Page Header -->
    <div class="hidden lg:block">
        <h1 class="text-2xl font-bold text-blue-800 mb-1">Kelola Kategori</h1>
        <p class="text-sm text-gray-500">Tambah, edit, atau hapus kategori laporan</p>
    </div>

    <!-- Search and Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row gap-3 items-center w-full">
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-gray-800" placeholder="Cari kategori...">
        </div>
        <button class="w-full sm:w-auto shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Kategori
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        <!-- Category 1 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <!-- Image Area -->
            <div class="h-32 w-full relative bg-gray-200">
                <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=400&h=200&q=80" alt="Road Damage" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/10 mix-blend-multiply"></div>
            </div>
            <!-- Content Area -->
            <div class="p-5 flex-1 flex flex-col">
                <h3 class="text-base font-bold text-gray-900 mb-1">Road Damage</h3>
                <p class="text-xs text-gray-500 mb-4 flex-1">Kerusakan jalan seperti lubang, aspal retak, dll.</p>
                
                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                        <span class="w-3.5 h-3.5 rounded-full bg-[#9A5B00]"></span>
                        #9A5B00
                    </div>
                    <div class="flex items-center gap-2">
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

        <!-- Category 2 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <!-- Image Area -->
            <div class="h-32 w-full relative bg-gray-200">
                <img src="https://images.unsplash.com/photo-1494522855154-9297ac14b55f?auto=format&fit=crop&w=400&h=200&q=80" alt="Street Lighting" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/10 mix-blend-multiply"></div>
            </div>
            <!-- Content Area -->
            <div class="p-5 flex-1 flex flex-col">
                <h3 class="text-base font-bold text-gray-900 mb-1">Street Lighting</h3>
                <p class="text-xs text-gray-500 mb-4 flex-1">Masalah penerangan jalan dan lampu.</p>
                
                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                        <span class="w-3.5 h-3.5 rounded-full bg-[#1D4ED8]"></span>
                        #1D4ED8
                    </div>
                    <div class="flex items-center gap-2">
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

        <!-- Category 3 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <!-- Image Area -->
            <div class="h-32 w-full relative bg-gray-200">
                <img src="https://images.unsplash.com/photo-1494522855154-9297ac14b55f?auto=format&fit=crop&w=400&h=200&q=80" alt="Drainage" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/10 mix-blend-multiply"></div>
            </div>
            <!-- Content Area -->
            <div class="p-5 flex-1 flex flex-col">
                <h3 class="text-base font-bold text-gray-900 mb-1">Drainage</h3>
                <p class="text-xs text-gray-500 mb-4 flex-1">Saluran air, got, dan drainase.</p>
                
                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                        <span class="w-3.5 h-3.5 rounded-full bg-[#059669]"></span>
                        #059669
                    </div>
                    <div class="flex items-center gap-2">
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

        <!-- Category 4 -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
            <!-- Image Area -->
            <div class="h-32 w-full relative bg-gray-200">
                <img src="https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=400&h=200&q=80" alt="Traffic Signs" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/10 mix-blend-multiply"></div>
            </div>
            <!-- Content Area -->
            <div class="p-5 flex-1 flex flex-col">
                <h3 class="text-base font-bold text-gray-900 mb-1">Traffic Signs</h3>
                <p class="text-xs text-gray-500 mb-4 flex-1">Rambu lalu lintas dan marka jalan.</p>
                
                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                        <span class="w-3.5 h-3.5 rounded-full bg-[#DC2626]"></span>
                        #DC2626
                    </div>
                    <div class="flex items-center gap-2">
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

    </div>
</div>

<!-- Modal Overlay -->
<div id="modal-overlay" class="fixed inset-0 bg-black/50 z-40 hidden flex items-center justify-center p-4">
    
    <!-- Modal Kategori Form (Tambah & Edit) -->
    <div id="modal-kategori" class="bg-white rounded-2xl shadow-xl w-full max-w-lg hidden max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 id="modal-kategori-title" class="text-xl font-bold text-blue-800">Edit Kategori</h2>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form class="space-y-4">
                <!-- Nama Kategori -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Nama Kategori *</label>
                    <input type="text" id="kategori-nama" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Contoh: Road Damage">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Deskripsi</label>
                    <textarea id="kategori-deskripsi" rows="3" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none" placeholder="Jelaskan kategori ini..."></textarea>
                </div>

                <!-- Upload Gambar -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Upload Gambar *</label>
                    <input type="file" id="kategori-gambar" accept="image/*" onchange="previewImage(event)" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[11px] text-gray-400 mt-1">Upload gambar ilustrasi kategori (disarankan ukuran 400x400px)</p>
                </div>

                <!-- Preview Gambar (Hidden initially for Tambah) -->
                <div id="preview-container" class="hidden">
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Preview Gambar</label>
                    <div class="w-full h-40 rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                        <img id="gambar-preview" src="" alt="Preview" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Warna Tema -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Warna Tema</label>
                    <input type="text" id="kategori-warna" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="#000000">
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row gap-3">
                    <button type="button" id="btn-submit-kategori" class="w-full sm:flex-1 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
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
    const modalKategori = document.getElementById('modal-kategori');
    const previewContainer = document.getElementById('preview-container');
    const gambarPreview = document.getElementById('gambar-preview');

    function closeModal() {
        overlay.classList.add('hidden');
        modalKategori.classList.add('hidden');
    }

    function openKategoriModal(mode, data = null) {
        // Reset form
        document.getElementById('kategori-nama').value = '';
        document.getElementById('kategori-deskripsi').value = '';
        document.getElementById('kategori-gambar').value = '';
        document.getElementById('kategori-warna').value = '';
        gambarPreview.src = '';
        previewContainer.classList.add('hidden');

        if (mode === 'tambah') {
            document.getElementById('modal-kategori-title').innerText = 'Tambah Kategori Baru';
            document.getElementById('btn-submit-kategori').innerText = 'Tambah Kategori';
        } else if (mode === 'edit' && data) {
            document.getElementById('modal-kategori-title').innerText = 'Edit Kategori';
            document.getElementById('btn-submit-kategori').innerText = 'Simpan Perubahan';

            // Populate data
            document.getElementById('kategori-nama').value = data.nama;
            document.getElementById('kategori-deskripsi').value = data.deskripsi || '';
            document.getElementById('kategori-warna').value = data.warna || '';

            // Tampilkan preview gambar jika ada
            if (data.gambarUrl) {
                gambarPreview.src = data.gambarUrl;
                previewContainer.classList.remove('hidden');
            }
        }

        // Show modal
        overlay.classList.remove('hidden');
        modalKategori.classList.remove('hidden');
    }

    // Handle file preview
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                gambarPreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            gambarPreview.src = '';
            previewContainer.classList.add('hidden');
        }
    }

    // Attach click listener for Tambah button (on top bar)
    document.querySelector('button:has(svg path[d="M12 4.5v15m7.5-7.5h-15"])').setAttribute('onclick', "openKategoriModal('tambah')");

    // Attach click listeners for Edit buttons in the grid
    // Edit Road Damage
    const editBtn1 = document.querySelectorAll('.bg-white.rounded-2xl .text-blue-600.hover\\:bg-blue-50')[0];
    if(editBtn1) {
        editBtn1.setAttribute('onclick', "openKategoriModal('edit', {nama: 'Road Damage', deskripsi: 'Kerusakan jalan seperti lubang, aspal retak, dll.', warna: '#9A5B00', gambarUrl: 'https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=400&h=200&q=80'})");
    }

    // Edit Street Lighting
    const editBtn2 = document.querySelectorAll('.bg-white.rounded-2xl .text-blue-600.hover\\:bg-blue-50')[1];
    if(editBtn2) {
        editBtn2.setAttribute('onclick', "openKategoriModal('edit', {nama: 'Street Lighting', deskripsi: 'Masalah penerangan jalan dan lampu.', warna: '#1D4ED8', gambarUrl: 'https://images.unsplash.com/photo-1494522855154-9297ac14b55f?auto=format&fit=crop&w=400&h=200&q=80'})");
    }

    // Edit Drainage
    const editBtn3 = document.querySelectorAll('.bg-white.rounded-2xl .text-blue-600.hover\\:bg-blue-50')[2];
    if(editBtn3) {
        editBtn3.setAttribute('onclick', "openKategoriModal('edit', {nama: 'Drainage', deskripsi: 'Saluran air, got, dan drainase.', warna: '#059669', gambarUrl: 'https://images.unsplash.com/photo-1494522855154-9297ac14b55f?auto=format&fit=crop&w=400&h=200&q=80'})");
    }

    // Edit Traffic Signs
    const editBtn4 = document.querySelectorAll('.bg-white.rounded-2xl .text-blue-600.hover\\:bg-blue-50')[3];
    if(editBtn4) {
        editBtn4.setAttribute('onclick', "openKategoriModal('edit', {nama: 'Traffic Signs', deskripsi: 'Rambu lalu lintas dan marka jalan.', warna: '#DC2626', gambarUrl: 'https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&w=400&h=200&q=80'})");
    }

    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeModal();
        }
    });
</script>
@endsection
