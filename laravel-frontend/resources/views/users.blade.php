@extends('layouts.admin')

@section('title', 'Kelola User - Lapor Infrastruktur')
@section('title_mobile', 'Kelola User')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 lg:space-y-8 relative">
    
    <!-- Page Header -->
    <div class="hidden lg:block">
        <h1 class="text-2xl font-bold text-blue-800 mb-1">Kelola User</h1>
        <p class="text-sm text-gray-500">Tambah, edit, atau hapus user dalam sistem</p>
    </div>

    <!-- Alert Feedback -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-green-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3 text-sm font-bold text-green-800">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center mb-1">
                <div class="flex-shrink-0 text-red-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3 text-sm font-bold text-red-800">Gagal Memproses Data:</div>
            </div>
            <ul class="ml-8 list-disc text-xs text-red-700 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search and Action Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row gap-3 items-center w-full">
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-gray-800" placeholder="Cari user berdasarkan nama, email, atau role...">
        </div>
        <button type="button" onclick="openUserModal('tambah')" class="w-full sm:w-auto shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 19v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m8-11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm13 8v-1a4 4 0 0 0-3-3.87m-4-12a4 4 0 0 1 0 7.75M21 15h-6"/></svg>
            Tambah User
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-xs text-gray-600 uppercase tracking-wider font-semibold">
                        <th class="px-6 py-4 whitespace-nowrap">Nama</th>
                        <th class="px-6 py-4 whitespace-nowrap">Email</th>
                        <th class="px-6 py-4 whitespace-nowrap">Telepon</th>
                        <th class="px-6 py-4 whitespace-nowrap">Role</th>
                        <th class="px-6 py-4">Lembaga/Jabatan</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">{{ $user['name'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $user['email'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $user['phone'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user['role'] == 'admin')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700">Admin</span>
                            @elseif($user['role'] == 'officer')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-100 text-indigo-700">Petugas</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700">Pelapor</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 min-w-[200px]">
                            @if(isset($user['institution_id']))
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-800">
                                        {{ collect($institutions)->firstWhere('id', $user['institution_id'])['name'] ?? 'Instansi' }}
                                    </span>
                                    <span class="text-xs text-gray-500">Petugas Lapangan</span>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-xs font-bold text-gray-800">Aktif</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="handleResetPassword({{ $user['id'] }}, '{{ $user['name'] }}')" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Ubah Password">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                                </button>
                                <button type="button" onclick="openUserModal('edit', {{ json_encode($user) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <form action="{{ url('/users/' . $user['id']) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Overlay -->
<div id="modal-overlay" class="fixed inset-0 bg-black/50 z-40 hidden flex items-center justify-center p-4">
    
    <!-- Modal User Form (Tambah & Edit) -->
    <div id="modal-user" class="bg-white rounded-2xl shadow-xl w-full max-w-lg hidden max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 id="modal-user-title" class="text-xl font-bold text-blue-800">Edit User</h2>
                <button type="button" onclick="closeModals()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="form-user" method="POST" action="{{ url('/users/officers') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <!-- Role -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Role *</label>
                    <select id="user-role" name="role" onchange="handleRoleChange()" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <option value="citizen">Pelapor</option>
                        <option value="officer">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                    <p id="role-helper" class="text-[11px] text-gray-400 mt-1 hidden">* Petugas tidak bisa diubah menjadi Pelapor</p>
                </div>

                <!-- Lembaga & Jabatan (Hidden by default) -->
                <div id="petugas-fields" class="hidden space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Lembaga *</label>
                        <select id="user-institution" name="institution_id" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            <option value="">Pilih Lembaga</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst['id'] }}">{{ $inst['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Jabatan *</label>
                        <input type="text" name="jabatan" id="user-jabatan" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Contoh: Supervisor Infrastruktur">
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" id="user-nama" required class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Masukkan nama lengkap">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Email *</label>
                        <input type="email" name="email" id="user-email" required class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Email aktif">
                    </div>
                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Nomor HP *</label>
                        <input type="text" name="phone" id="user-hp" required class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="08xxx">
                    </div>
                </div>

                <!-- Password (Hanya muncul saat TAMBAH) -->
                <div id="password-field">
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Password *</label>
                    <input type="password" name="password" id="user-password" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="******">
                </div>

                <!-- Status Akun -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Status Akun</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="user-status" class="sr-only peer" checked onchange="document.getElementById('status-text').innerText = this.checked ? 'Aktif' : 'Nonaktif'; document.getElementById('status-text').className = this.checked ? 'ml-3 text-sm font-bold text-green-600' : 'ml-3 text-sm font-bold text-gray-500'">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-700"></div>
                        <span id="status-text" class="ml-3 text-sm font-bold text-green-600">Aktif</span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row gap-3">
                    <button type="submit" id="btn-submit-user" class="w-full sm:flex-1 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeModals()" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-blue-700 rounded-xl text-sm font-semibold hover:bg-blue-50 transition-colors shadow-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div id="modal-reset-password" class="bg-white rounded-2xl shadow-xl w-full max-w-sm hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-blue-800">Reset Password</h2>
                <button type="button" onclick="closeModals()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <p class="text-sm text-gray-600 mb-6">Password baru telah di-generate untuk user <span id="reset-user-name" class="font-bold text-gray-800"></span></p>

            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between border border-gray-100 mb-6">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Password Baru:</p>
                    <p id="generated-password" class="text-lg font-bold tracking-wider text-blue-700"></p>
                </div>
                <button type="button" onclick="copyPassword()" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors" title="Salin Password">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"/></svg>
                </button>
            </div>

            <div class="bg-orange-50 border border-orange-200 text-orange-800 text-xs rounded-xl p-3 mb-6">
                <strong>Penting:</strong> Salin password ini dan berikan kepada user. Password hanya ditampilkan satu kali.
            </div>

            <button type="button" onclick="closeModals()" class="w-full py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
                Tutup
            </button>
        </div>
    </div>

</div>

<script>
    const overlay = document.getElementById('modal-overlay');
    const modalUser = document.getElementById('modal-user');
    const modalReset = document.getElementById('modal-reset-password');
    const roleSelect = document.getElementById('user-role');
    const petugasFields = document.getElementById('petugas-fields');
    const roleHelper = document.getElementById('role-helper');

    function closeModals() {
        overlay.classList.add('hidden');
        modalUser.classList.add('hidden');
        modalReset.classList.add('hidden');
    }

    function handleRoleChange() {
        if (roleSelect.value === 'officer') {
            petugasFields.classList.remove('hidden');
        } else {
            petugasFields.classList.add('hidden');
        }
    }

    function openUserModal(mode, data = null) {
        const form = document.getElementById('form-user');
        const methodInput = document.getElementById('form-method');
        const passwordField = document.getElementById('password-field');
        const submitBtn = document.getElementById('btn-submit-user');
        
        form.reset();
        
        if (mode === 'tambah') {
            document.getElementById('modal-user-title').innerText = 'Tambah User Baru';
            submitBtn.innerText = 'Tambah User';
            form.action = "{{ url('/users/officers') }}";
            methodInput.value = 'POST';
            roleSelect.value = 'citizen';
            roleSelect.removeAttribute('disabled');
            passwordField.classList.remove('hidden');
            document.getElementById('user-password').setAttribute('required', 'required');
        } else if (mode === 'edit' && data) {
            document.getElementById('modal-user-title').innerText = 'Edit User';
            submitBtn.innerText = 'Simpan Perubahan';
            form.action = "{{ url('/users') }}/" + data.id;
            methodInput.value = 'PATCH';
            passwordField.classList.add('hidden');
            document.getElementById('user-password').removeAttribute('required');
            
            roleSelect.value = data.role;
            document.getElementById('user-nama').value = data.name;
            document.getElementById('user-email').value = data.email;
            document.getElementById('user-hp').value = data.phone;
            
            if(data.institution_id) document.getElementById('user-institution').value = data.institution_id;

            if (data.role === 'officer') {
                roleHelper.classList.remove('hidden');
            } else {
                roleHelper.classList.add('hidden');
            }
        }

        handleRoleChange();
        overlay.classList.remove('hidden');
        modalUser.classList.remove('hidden');
    }

    async function handleResetPassword(userId, userName) {
        if (!confirm(`Generate password baru untuk ${userName}?`)) return;

        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let newPass = "";
        for (let i = 0; i < 10; i++) newPass += charset.charAt(Math.floor(Math.random() * charset.length));

        try {
            const response = await fetch("{{ url('/users') }}/" + userId + "/reset-password", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    "_method": "PATCH",
                    "new_password": newPass
                })
            });

            if (response.ok) {
                document.getElementById('reset-user-name').innerText = userName;
                document.getElementById('generated-password').innerText = newPass;
                overlay.classList.remove('hidden');
                modalReset.classList.remove('hidden');
            } else {
                alert("Gagal mereset password.");
            }
        } catch (err) {
            alert("Terjadi kesalahan.");
        }
    }

    function copyPassword() {
        const pass = document.getElementById('generated-password').innerText;
        navigator.clipboard.writeText(pass).then(() => alert('Password disalin!'));
    }

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeModals();
    });
</script>
@endsection
