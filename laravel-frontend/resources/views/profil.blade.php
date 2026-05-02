@extends('layouts.admin')

@section('title', 'Profil Saya - Lapor Infrastruktur')
@section('title_mobile', 'Profil Saya')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8">
    
    <!-- Page Header -->
    <div class="hidden lg:block">
        <h1 class="text-2xl font-bold text-blue-800 mb-1">Profil Saya</h1>
        <p class="text-sm text-gray-500">Kelola informasi profil dan keamanan akun Anda</p>
    </div>

    <!-- Alert Feedback -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm mb-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-green-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3 text-sm font-bold text-green-800">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm mb-4">
            <div class="flex items-center mb-1">
                <div class="flex-shrink-0 text-red-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3 text-sm font-bold text-red-800">Terjadi Kesalahan:</div>
            </div>
            <ul class="ml-8 list-disc text-xs text-red-700 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Informasi Profil Card -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-8 relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-3">
                    Informasi Profil
                </h2>
                <button type="button" id="btn-edit-profil" onclick="toggleEditMode()" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-all shadow-sm shadow-blue-100 active:scale-95">
                    Edit Profil
                </button>
            </div>

            <form id="form-profil" action="{{ url('/profil') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="flex flex-col sm:flex-row items-center gap-6 pb-4 border-b border-gray-50">
                    <div class="relative group">
                        <div class="w-24 h-24 rounded-3xl bg-blue-50 flex items-center justify-center text-blue-600 border-2 border-blue-100 overflow-hidden shadow-inner">
                            @if(Session::get('user')['profile_photo'])
                                <img src="{{ config('app.backend_url') . Session::get('user')['profile_photo'] }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                            @endif
                        </div>
                        <label id="label-upload" class="absolute -bottom-2 -right-2 p-2 bg-white rounded-xl shadow-lg border border-gray-100 text-blue-600 cursor-pointer hover:bg-blue-50 transition-all hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg>
                            <input type="file" name="image" class="hidden">
                        </label>
                    </div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-lg font-bold text-gray-900">{{ Session::get('user')['name'] ?? 'Admin' }}</h3>
                        <p class="text-sm text-gray-500 capitalize">{{ Session::get('user')['role'] ?? 'User' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ Session::get('user')['name'] ?? '' }}" readonly class="w-full bg-gray-50 border border-gray-200 text-gray-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors profile-input">
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            Email
                        </label>
                        <input type="email" value="{{ Session::get('user')['email'] ?? '' }}" readonly class="w-full bg-gray-50 border border-gray-200 text-gray-400 text-sm rounded-xl px-4 py-3 cursor-not-allowed">
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.496-4.196-7.092-7.092l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            Nomor Telepon
                        </label>
                        <input type="text" name="phone" value="{{ Session::get('user')['phone'] ?? '' }}" readonly class="w-full bg-gray-50 border border-gray-200 text-gray-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors profile-input">
                    </div>
                </div>

                <!-- Simpan Button (Hidden by default) -->
                <div id="save-profil-container" class="pt-4 border-t border-gray-50 flex justify-end gap-3 hidden">
                    <button type="button" onclick="toggleEditMode()" class="px-6 py-3 bg-white border border-gray-200 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="px-8 py-3 bg-blue-700 text-white rounded-2xl text-sm font-bold hover:bg-blue-800 transition-all shadow-lg shadow-blue-100 active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Keamanan Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                Keamanan
            </h2>

            <form action="{{ url('/profil/password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Password Saat Ini</label>
                    <input type="password" name="old_password" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all" placeholder="********">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest mb-2">Password Baru</label>
                    <input type="password" name="new_password" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all" placeholder="********">
                </div>

                <button type="submit" class="w-full py-4 bg-blue-700 text-white rounded-2xl text-sm font-bold hover:bg-blue-800 transition-all shadow-lg shadow-blue-100 active:scale-95">
                    Ubah Password
                </button>
            </form>

            <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-50">
                <h4 class="text-xs font-bold text-indigo-700 mb-2">Tips Keamanan:</h4>
                <ul class="text-[11px] text-indigo-600 space-y-1">
                    <li>• Gunakan minimal 8 karakter</li>
                    <li>• Kombinasi huruf besar, kecil, dan angka</li>
                    <li>• Hindari menggunakan data pribadi (nama/tgl lahir)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    let editMode = false;
    const inputs = document.querySelectorAll('.profile-input');
    const saveBtn = document.getElementById('save-profil-container');
    const editBtn = document.getElementById('btn-edit-profil');
    const uploadLabel = document.getElementById('label-upload');

    function toggleEditMode() {
        editMode = !editMode;
        
        inputs.forEach(input => {
            if (editMode) {
                input.removeAttribute('readonly');
                input.classList.remove('bg-gray-50', 'text-gray-500');
                input.classList.add('bg-white', 'text-gray-900');
            } else {
                input.setAttribute('readonly', 'readonly');
                input.classList.remove('bg-white', 'text-gray-900');
                input.classList.add('bg-gray-50', 'text-gray-500');
            }
        });

        if (editMode) {
            saveBtn.classList.remove('hidden');
            editBtn.classList.add('hidden');
            uploadLabel.classList.remove('hidden');
        } else {
            saveBtn.classList.add('hidden');
            editBtn.classList.remove('hidden');
            uploadLabel.classList.add('hidden');
        }
    }
</script>
@endsection
