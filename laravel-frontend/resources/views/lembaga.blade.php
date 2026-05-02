@extends('layouts.admin')

@section('title', 'Lembaga - Lapor Infrastruktur')
@section('title_mobile', 'Lembaga')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8">
    
    <!-- Page Header -->
    <div class="hidden lg:block">
        <h1 class="text-2xl font-bold text-blue-800 mb-1">Kelola Lembaga</h1>
        <p class="text-sm text-gray-500">Tambah, edit, atau hapus lembaga penanganan infrastruktur</p>
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
        <button onclick="openLembagaModal('tambah')" class="w-full sm:w-auto shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Lembaga
        </button>
    </div>

    <!-- Organizations List -->
    <div class="space-y-4">
        @forelse($institutions as $inst)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 hover:shadow-md transition-shadow relative group">
            <div class="flex flex-col sm:flex-row gap-6 items-start">
                <!-- Logo -->
                <div class="w-16 h-16 sm:w-24 sm:h-24 shrink-0 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 shadow-inner flex items-center justify-center">
                    @if(isset($inst['profile_photo']))
                        <img src="{{ config('app.backend_url') . $inst['profile_photo'] }}" alt="{{ $inst['name'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-blue-50 flex items-center justify-center text-blue-300 font-bold text-2xl uppercase">
                            {{ substr($inst['name'], 0, 2) }}
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0 w-full pr-12 sm:pr-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-lg font-bold text-blue-900 group-hover:text-blue-700 transition-colors">{{ $inst['name'] }}</h2>
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider">Instansi Resmi</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-5 leading-relaxed">{{ $inst['description'] ?? 'Tidak ada deskripsi.' }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8">
                        <div class="space-y-1">
                            <div class="flex items-center text-[10px] uppercase tracking-wider text-blue-600 gap-1.5 font-bold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                Alamat
                            </div>
                            <p class="text-xs font-bold text-gray-800">{{ $inst['address'] }}</p>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center text-[10px] uppercase tracking-wider text-blue-600 gap-1.5 font-bold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.496-4.196-7.092-7.092l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                Telepon
                            </div>
                            <p class="text-xs font-bold text-gray-800">{{ $inst['phone'] }}</p>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center text-[10px] uppercase tracking-wider text-blue-600 gap-1.5 font-bold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                Email
                            </div>
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $inst['email'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="absolute top-4 sm:top-6 right-4 sm:right-6 flex items-center gap-2">
                <button onclick="openLembagaModal('edit', {{ json_encode($inst) }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                </button>
                <form action="{{ url('/lembaga/' . $inst['id']) }}" method="POST" onsubmit="return confirm('Hapus lembaga ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="py-20 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <p class="text-gray-500 font-medium">Belum ada data lembaga.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Overlay -->
<div id="modal-overlay" class="fixed inset-0 bg-black/60 z-40 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
    
    <!-- Modal Lembaga Form (Tambah & Edit) -->
    <div id="modal-lembaga" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg hidden transform transition-all duration-300 scale-95 opacity-0 overflow-hidden">
        <div class="p-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-8">
                <h2 id="modal-lembaga-title" class="text-2xl font-bold text-blue-900 tracking-tight">Tambah Lembaga</h2>
                <button type="button" onclick="closeModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-lembaga" action="{{ url('/lembaga') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <!-- Logo & Preview -->
                <div class="flex items-center gap-6 mb-6">
                    <div class="w-24 h-24 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden shrink-0 group relative">
                        <img id="logo-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        <div id="logo-placeholder" class="text-gray-400 flex flex-col items-center">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Logo Lembaga</label>
                        <input type="file" name="image" id="lembaga-logo" accept="image/*" onchange="previewImage(event)" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                        <p class="text-[10px] text-gray-400 mt-2 font-medium">Format: JPG, PNG. Maksimal 2MB.</p>
                    </div>
                </div>

                <!-- Nama Lembaga -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lembaga *</label>
                    <input type="text" name="name" id="lembaga-nama" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all" placeholder="Contoh: Dinas Pekerjaan Umum Jakarta">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="lembaga-deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all resize-none" placeholder="Jelaskan bidang penanganan instansi ini..."></textarea>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="address" id="lembaga-alamat" rows="2" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all resize-none" placeholder="Alamat kantor lembaga..."></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Telepon</label>
                        <input type="text" name="phone" id="lembaga-telepon" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all" placeholder="021-XXXXXXX">
                    </div>
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="lembaga-email" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all" placeholder="info@lembaga.go.id">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                    <button type="submit" id="btn-submit-lembaga" class="w-full sm:flex-1 py-4 bg-blue-700 text-white rounded-2xl text-sm font-bold hover:bg-blue-800 transition-all shadow-lg shadow-blue-100 active:scale-95">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeModal()" class="w-full sm:w-auto px-8 py-4 bg-white border border-gray-200 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all">
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
    const logoPreview = document.getElementById('logo-preview');
    const logoPlaceholder = document.getElementById('logo-placeholder');

    function closeModal() {
        modalLembaga.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            overlay.classList.add('hidden');
            modalLembaga.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    function openLembagaModal(mode, data = null) {
        const form = document.getElementById('form-lembaga');
        const methodInput = document.getElementById('form-method');
        
        // Reset form
        form.reset();
        logoPreview.classList.add('hidden');
        logoPlaceholder.classList.remove('hidden');

        if (mode === 'tambah') {
            document.getElementById('modal-lembaga-title').innerText = 'Tambah Lembaga Baru';
            document.getElementById('btn-submit-lembaga').innerText = 'Tambah Lembaga';
            form.action = "{{ url('/lembaga') }}";
            methodInput.value = 'POST';
        } else if (mode === 'edit' && data) {
            document.getElementById('modal-lembaga-title').innerText = 'Edit Lembaga';
            document.getElementById('btn-submit-lembaga').innerText = 'Simpan Perubahan';
            form.action = "{{ url('/lembaga') }}/" + data.id;
            methodInput.value = 'PATCH';

            document.getElementById('lembaga-nama').value = data.name;
            document.getElementById('lembaga-deskripsi').value = data.description || '';
            document.getElementById('lembaga-alamat').value = data.address || '';
            document.getElementById('lembaga-telepon').value = data.phone || '';
            document.getElementById('lembaga-email').value = data.email || '';

            if (data.profile_photo) {
                logoPreview.src = "{{ config('app.backend_url') }}" + data.profile_photo;
                logoPreview.classList.remove('hidden');
                logoPlaceholder.classList.add('hidden');
            }
        }

        // Show modal
        overlay.classList.remove('hidden');
        modalLembaga.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            modalLembaga.classList.remove('scale-95', 'opacity-0');
            modalLembaga.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.src = e.target.result;
                logoPreview.classList.remove('hidden');
                logoPlaceholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeModal();
    });
</script>
@endsection
