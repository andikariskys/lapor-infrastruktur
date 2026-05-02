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
        <button onclick="openKategoriModal('tambah')" class="w-full sm:w-auto shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Kategori
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($categories as $category)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow group">
            <!-- Icon/Visual Area (Since no image in DB) -->
            <div class="h-24 w-full relative flex items-center justify-center bg-gray-50 border-b border-gray-100 overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-gradient-to-br from-blue-600 to-indigo-800"></div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg transform group-hover:scale-110 transition-transform duration-300" style="background-color: {{ $category['color_code'] ?? '#3b82f6' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.125 1.125 0 0 0 1.591 0l4.318-4.318a1.125 1.125 0 0 0 0-1.591l-9.581-9.581c-.422-.422-.994-.659-1.591-.659Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h.008v.008H7.5V7.5Z"/></svg>
                </div>
            </div>
            <!-- Content Area -->
            <div class="p-5 flex-1 flex flex-col">
                <h3 class="text-base font-bold text-gray-900 mb-1 group-hover:text-blue-700 transition-colors">{{ $category['name'] }}</h3>
                <p class="text-xs text-gray-500 mb-4 flex-1">{{ $category['description'] ?? 'Tidak ada deskripsi.' }}</p>
                
                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $category['color_code'] ?? '#3b82f6' }}"></span>
                        {{ $category['color_code'] ?? '#3B82F6' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="openKategoriModal('edit', {{ json_encode($category) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                        </button>
                        <form action="{{ url('/kategori/' . $category['id']) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
            </div>
            <p class="text-gray-500 font-medium">Belum ada kategori. Silakan tambah kategori baru.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Overlay -->
<div id="modal-overlay" class="fixed inset-0 bg-black/60 z-40 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
    
    <!-- Modal Kategori Form (Tambah & Edit) -->
    <div id="modal-kategori" class="bg-white rounded-3xl shadow-2xl w-full max-w-md hidden transform transition-all duration-300 scale-95 opacity-0">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 id="modal-kategori-title" class="text-2xl font-bold text-blue-900 tracking-tight">Edit Kategori</h2>
                <button type="button" onclick="closeModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-kategori" action="{{ url('/kategori') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                
                <!-- Nama Kategori -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori *</label>
                    <input type="text" name="name" id="kategori-nama" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all" placeholder="Contoh: Kerusakan Jalan">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="kategori-deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all resize-none" placeholder="Jelaskan jenis laporan untuk kategori ini..."></textarea>
                </div>

                <!-- Warna Tema -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Warna Tema</label>
                    <div class="flex gap-3">
                        <input type="color" name="color_code" id="kategori-warna-picker" class="h-11 w-14 p-1 rounded-xl border border-gray-200 cursor-pointer bg-white">
                        <input type="text" id="kategori-warna-text" readonly class="flex-1 bg-gray-50 border border-gray-200 text-gray-500 text-sm rounded-2xl px-5 py-3 focus:outline-none" placeholder="#000000">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                    <button type="submit" id="btn-submit-kategori" class="w-full sm:flex-1 py-3.5 bg-blue-700 text-white rounded-2xl text-sm font-bold hover:bg-blue-800 transition-all shadow-lg shadow-blue-100 active:scale-95">
                        Simpan Kategori
                    </button>
                    <button type="button" onclick="closeModal()" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all">
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
    const colorPicker = document.getElementById('kategori-warna-picker');
    const colorText = document.getElementById('kategori-warna-text');

    colorPicker.addEventListener('input', (e) => {
        colorText.value = e.target.value.toUpperCase();
    });

    function closeModal() {
        modalKategori.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            overlay.classList.add('hidden');
            modalKategori.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    function openKategoriModal(mode, data = null) {
        const form = document.getElementById('form-kategori');
        const methodInput = document.getElementById('form-method');
        
        // Reset form
        document.getElementById('kategori-nama').value = '';
        document.getElementById('kategori-deskripsi').value = '';
        colorPicker.value = '#3B82F6';
        colorText.value = '#3B82F6';

        if (mode === 'tambah') {
            document.getElementById('modal-kategori-title').innerText = 'Tambah Kategori Baru';
            document.getElementById('btn-submit-kategori').innerText = 'Tambah Kategori';
            form.action = "{{ url('/kategori') }}";
            methodInput.value = 'POST';
        } else if (mode === 'edit' && data) {
            document.getElementById('modal-kategori-title').innerText = 'Edit Kategori';
            document.getElementById('btn-submit-kategori').innerText = 'Simpan Perubahan';
            form.action = "{{ url('/kategori') }}/" + data.id;
            methodInput.value = 'PATCH';

            document.getElementById('kategori-nama').value = data.name;
            document.getElementById('kategori-deskripsi').value = data.description || '';
            colorPicker.value = data.color_code || '#3B82F6';
            colorText.value = data.color_code || '#3B82F6';
        }

        // Show modal with animation
        overlay.classList.remove('hidden');
        modalKategori.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            modalKategori.classList.remove('scale-95', 'opacity-0');
            modalKategori.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closeModal();
    });
</script>
@endsection
