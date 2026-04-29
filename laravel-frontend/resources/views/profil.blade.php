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

    <!-- Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Informasi Profil Card (Left Column) -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-lg font-bold text-blue-800">Informasi Profil</h2>
                <div id="profil-actions">
                    <button type="button" id="btn-edit-profil" onclick="toggleEditProfil()" class="px-5 py-2 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
                        Edit Profil
                    </button>
                </div>
                <div id="profil-edit-actions" class="hidden flex items-center gap-2">
                    <button type="button" onclick="cancelEditProfil()" class="px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="button" onclick="saveProfil()" class="px-5 py-2 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
                        Simpan Profil
                    </button>
                </div>
            </div>

            <!-- Avatar & Header Info -->
            <div class="flex items-center gap-5 mb-8 pb-8 border-b border-gray-100">
                <div class="w-20 h-20 rounded-full bg-blue-700 text-white flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Admin Utama</h3>
                    <p class="text-sm text-gray-500">Administrator</p>
                </div>
            </div>

            <!-- Profil Form Fields -->
            <form id="form-profil" class="space-y-6">
                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                        Nama Lengkap
                    </label>
                    <input type="text" id="input-nama" value="Admin Utama" readonly class="w-full bg-gray-50 border border-gray-200 text-gray-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors profile-input">
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                        Email
                    </label>
                    <input type="email" id="input-email" value="admin@lapor.com" readonly class="w-full bg-gray-50 border border-gray-200 text-gray-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors profile-input">
                </div>

                <!-- Nomor Telepon -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.496-4.196-7.092-7.092l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                        Nomor Telepon
                    </label>
                    <input type="text" id="input-telepon" value="0812-3456-7890" readonly class="w-full bg-gray-50 border border-gray-200 text-gray-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors profile-input">
                </div>

                <!-- Alamat -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        Alamat
                    </label>
                    <textarea id="input-alamat" readonly class="w-full bg-gray-50 border border-gray-200 text-gray-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors h-24 resize-none profile-input"></textarea>
                </div>
            </form>
        </div>

        <!-- Keamanan Card (Right Column) -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 sticky top-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                    </div>
                    <h2 class="text-lg font-bold text-blue-800">Keamanan</h2>
                </div>

                <!-- Default Password View -->
                <div id="password-view-default" class="space-y-5 block">
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Password Saat Ini</p>
                        <p class="text-sm font-black tracking-widest text-gray-800">••••••••</p>
                    </div>

                    <button type="button" onclick="toggleEditPassword()" class="w-full py-3 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
                        Ubah Password
                    </button>
                </div>

                <!-- Edit Password Form -->
                <div id="password-view-edit" class="hidden space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700">Password Saat Ini</label>
                        <input type="password" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700">Password Baru</label>
                        <input type="password" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>

                    <div class="pt-2 space-y-2">
                        <button type="button" onclick="savePassword()" class="w-full py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
                            Simpan Password
                        </button>
                        <button type="button" onclick="cancelEditPassword()" class="w-full py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                            Batal
                        </button>
                    </div>
                </div>

                <!-- Tips -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 mb-3">Tips keamanan:</p>
                    <ul class="text-xs text-gray-400 space-y-2 list-inside list-disc">
                        <li>Gunakan password minimal 8 karakter</li>
                        <li>Kombinasikan huruf, angka, dan simbol</li>
                        <li>Jangan gunakan password yang sama</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Profil Edit Toggle logic
    function toggleEditProfil() {
        // Toggle action buttons
        document.getElementById('profil-actions').classList.add('hidden');
        document.getElementById('profil-edit-actions').classList.remove('hidden');
        
        // Enable inputs
        const inputs = document.querySelectorAll('.profile-input');
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            // Ganti warna background, border, dan text agar terlihat editable
            input.classList.remove('bg-gray-50', 'text-gray-500', 'cursor-not-allowed', 'border-gray-200');
            input.classList.add('bg-white', 'text-gray-900', 'border-gray-300');
        });
        
        // Fokuskan pada input pertama
        document.getElementById('input-nama').focus();
    }

    function cancelEditProfil() {
        // Toggle action buttons
        document.getElementById('profil-edit-actions').classList.add('hidden');
        document.getElementById('profil-actions').classList.remove('hidden');
        
        // Disable inputs
        const inputs = document.querySelectorAll('.profile-input');
        inputs.forEach(input => {
            input.setAttribute('readonly', 'readonly');
            // Kembalikan style seperti semula
            input.classList.remove('bg-white', 'text-gray-900', 'border-gray-300');
            input.classList.add('bg-gray-50', 'text-gray-500', 'cursor-not-allowed', 'border-gray-200');
        });

        // Reset the form
        document.getElementById('form-profil').reset();
    }

    function saveProfil() {
        // Here you would do API fetch or form submit
        alert('Profil berhasil diperbarui! (Simulasi)');
        cancelEditProfil(); // Balikkan mode ke view
    }

    // Password Edit Toggle logic
    function toggleEditPassword() {
        document.getElementById('password-view-default').classList.add('hidden');
        document.getElementById('password-view-edit').classList.remove('hidden');
    }

    function cancelEditPassword() {
        document.getElementById('password-view-edit').classList.add('hidden');
        document.getElementById('password-view-default').classList.remove('hidden');
        // Kosongkan form field password
        const passwordInputs = document.querySelectorAll('#password-view-edit input');
        passwordInputs.forEach(input => input.value = '');
    }

    function savePassword() {
        // Validasi dan API call akan dilakukan di sini
        alert('Password berhasil diubah! (Simulasi)');
        cancelEditPassword();
    }
</script>
@endsection
