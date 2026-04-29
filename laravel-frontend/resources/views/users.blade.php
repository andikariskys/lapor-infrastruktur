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
                    
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">Admin Utama</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">admin@lapor.com</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">081234567890</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700">
                                Admin
                            </span>
                        </td>
                        <td class="px-6 py-4 min-w-[200px]">
                            <span class="text-sm text-gray-500">-</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-xs font-bold text-gray-800">Aktif</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="openResetPasswordModal('Admin Utama')" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Ubah Password">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                                </button>
                                <button type="button" onclick="openUserModal('edit', {role: 'Admin', nama: 'Admin Utama', email: 'admin@lapor.com', hp: '081234567890', status: true})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button type="button" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">Petugas Jalan</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">petugas.jalan@lapor.com</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">081234567891</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-100 text-indigo-700">
                                Petugas
                            </span>
                        </td>
                        <td class="px-6 py-4 min-w-[200px]">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-800">Dinas Pekerjaan Umum Jakarta</span>
                                <span class="text-xs text-gray-500">Supervisor Infrastruktur</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-xs font-bold text-gray-800">Aktif</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="openResetPasswordModal('Petugas Jalan')" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Ubah Password">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                                </button>
                                <button type="button" onclick="openUserModal('edit', {role: 'Petugas', nama: 'Petugas Jalan', email: 'petugas.jalan@lapor.com', hp: '081234567891', lembaga: 'Dinas Pekerjaan Umum Jakarta', jabatan: 'Supervisor Infrastruktur', status: true})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button type="button" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">Petugas Listrik</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">petugas.listrik@lapor.com</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">081234567892</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-100 text-indigo-700">
                                Petugas
                            </span>
                        </td>
                        <td class="px-6 py-4 min-w-[200px]">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-800">PLN Jakarta Raya</span>
                                <span class="text-xs text-gray-500">Teknisi Senior</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-xs font-bold text-gray-800">Aktif</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="openResetPasswordModal('Petugas Listrik')" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Ubah Password">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                                </button>
                                <button type="button" onclick="openUserModal('edit', {role: 'Petugas', nama: 'Petugas Listrik', email: 'petugas.listrik@lapor.com', hp: '081234567892', lembaga: 'PLN Jakarta Raya', jabatan: 'Teknisi Senior', status: true})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button type="button" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

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

            <form class="space-y-4">
                <!-- Role -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Role *</label>
                    <select id="user-role" onchange="handleRoleChange()" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <option value="Pelapor">Pelapor</option>
                        <option value="Petugas">Petugas</option>
                        <option value="Admin">Admin</option>
                    </select>
                    <p id="role-helper" class="text-[11px] text-gray-400 mt-1 hidden">* Petugas tidak bisa diubah menjadi Pelapor</p>
                </div>

                <!-- Lembaga & Jabatan (Hidden by default) -->
                <div id="petugas-fields" class="hidden space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Lembaga *</label>
                        <input type="text" id="user-lembaga" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Contoh: Dinas Pekerjaan Umum">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Jabatan *</label>
                        <input type="text" id="user-jabatan" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Contoh: Supervisor Infrastruktur">
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Nama Lengkap *</label>
                    <input type="text" id="user-nama" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Masukkan nama lengkap">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Email *</label>
                        <input type="email" id="user-email" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Email aktif">
                    </div>
                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Nomor HP *</label>
                        <input type="text" id="user-hp" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="08xxx">
                    </div>
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
                    <button type="button" id="btn-submit-user" class="w-full sm:flex-1 py-2.5 bg-blue-700 text-white rounded-xl text-sm font-semibold hover:bg-blue-800 transition-colors shadow-sm">
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

            <p class="text-sm text-gray-600 mb-6">Password baru telah di-generate untuk user <span id="reset-user-name" class="font-bold text-gray-800">Admin Utama</span></p>

            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between border border-gray-100 mb-6">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Password Baru:</p>
                    <p id="generated-password" class="text-lg font-bold tracking-wider text-blue-700">%P#BjeBvm7qv</p>
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

    // Close Modals
    function closeModals() {
        overlay.classList.add('hidden');
        modalUser.classList.add('hidden');
        modalReset.classList.add('hidden');
    }

    // Handle Role Change to show/hide Petugas fields
    function handleRoleChange() {
        if (roleSelect.value === 'Petugas') {
            petugasFields.classList.remove('hidden');
        } else {
            petugasFields.classList.add('hidden');
        }
    }

    // Open Add/Edit User Modal
    function openUserModal(mode, data = null) {
        // Reset form
        document.getElementById('user-nama').value = '';
        document.getElementById('user-email').value = '';
        document.getElementById('user-hp').value = '';
        document.getElementById('user-lembaga').value = '';
        document.getElementById('user-jabatan').value = '';
        document.getElementById('user-status').checked = true;
        document.getElementById('status-text').innerText = 'Aktif';
        document.getElementById('status-text').className = 'ml-3 text-sm font-bold text-green-600';
        
        if (mode === 'tambah') {
            document.getElementById('modal-user-title').innerText = 'Tambah User Baru';
            document.getElementById('btn-submit-user').innerText = 'Tambah User';
            roleSelect.value = 'Pelapor';
            roleHelper.classList.add('hidden');
            roleSelect.removeAttribute('disabled');
        } else if (mode === 'edit' && data) {
            document.getElementById('modal-user-title').innerText = 'Edit User';
            document.getElementById('btn-submit-user').innerText = 'Simpan Perubahan';
            
            roleSelect.value = data.role;
            document.getElementById('user-nama').value = data.nama;
            document.getElementById('user-email').value = data.email;
            document.getElementById('user-hp').value = data.hp;
            
            if(data.lembaga) document.getElementById('user-lembaga').value = data.lembaga;
            if(data.jabatan) document.getElementById('user-jabatan').value = data.jabatan;

            // Manage Petugas restriction
            if (data.role === 'Petugas') {
                roleHelper.classList.remove('hidden');
            } else {
                roleHelper.classList.add('hidden');
            }

            // Status
            document.getElementById('user-status').checked = data.status;
            document.getElementById('status-text').innerText = data.status ? 'Aktif' : 'Nonaktif';
            document.getElementById('status-text').className = data.status ? 'ml-3 text-sm font-bold text-green-600' : 'ml-3 text-sm font-bold text-gray-500';
        }

        handleRoleChange(); // Show/hide petugas fields accordingly
        
        // Show modal
        overlay.classList.remove('hidden');
        modalUser.classList.remove('hidden');
    }

    // Open Reset Password Modal
    function openResetPasswordModal(userName) {
        document.getElementById('reset-user-name').innerText = userName;
        // Generate random string as placeholder for demo
        const randomStr = Math.random().toString(36).substring(2, 12);
        document.getElementById('generated-password').innerText = `%P#${randomStr}`;

        overlay.classList.remove('hidden');
        modalReset.classList.remove('hidden');
    }

    function copyPassword() {
        const password = document.getElementById('generated-password').innerText;
        navigator.clipboard.writeText(password).then(() => {
            alert('Password disalin ke clipboard!');
        });
    }

    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeModals();
        }
    });
</script>
@endsection
