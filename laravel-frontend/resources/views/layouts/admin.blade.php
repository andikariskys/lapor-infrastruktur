<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lapor Infrastruktur')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Leaflet JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @stack('styles')
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex flex-col justify-between transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto -translate-x-full h-screen">
        <div class="overflow-y-auto">
            <!-- Sidebar Header -->
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-lg font-bold text-blue-800">Lapor Infrastruktur</h1>
                <p class="text-xs text-gray-400 mt-0.5">{{ session('user')['name'] ?? 'Admin' }}</p>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="p-3 space-y-1 mt-1">
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-colors {{ request()->is('dashboard') ? 'bg-blue-600 text-white font-medium shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 {{ request()->is('dashboard') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Beranda
                </a>
                
                <a href="{{ url('/laporan') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-colors {{ request()->is('laporan*') ? 'bg-blue-100 text-blue-700 font-medium shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 {{ request()->is('laporan*') ? 'text-blue-700' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                    Daftar Laporan
                </a>
                
                <a href="{{ url('/kategori') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-colors {{ request()->is('kategori*') ? 'bg-blue-100 text-blue-700 font-medium shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 {{ request()->is('kategori*') ? 'text-blue-700' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                    Kategori
                </a>
                
                <a href="{{ url('/lembaga') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-colors {{ request()->is('lembaga*') ? 'bg-blue-100 text-blue-700 font-medium shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 {{ request()->is('lembaga*') ? 'text-blue-700' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    Lembaga
                </a>
                
                <a href="{{ url('/users') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-colors {{ request()->is('users*') ? 'bg-blue-100 text-blue-700 font-medium shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 {{ request()->is('users*') ? 'text-blue-700' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    Kelola User
                </a>
                
                <a href="{{ url('/profil') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-colors {{ request()->is('profil*') ? 'bg-blue-100 text-blue-700 font-medium shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 {{ request()->is('profil*') ? 'text-blue-700' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                    Profil
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-3 border-t border-gray-100 mt-auto">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-red-500 hover:bg-red-50 rounded-xl text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Main Content Wrapper -->
    <div class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden">
        
        <!-- Mobile Header -->
        <div class="lg:hidden flex items-center gap-3 p-4 bg-white border-b border-gray-200 shrink-0 z-10">
            <button onclick="toggleSidebar()" class="p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
            <h1 class="text-base font-bold text-blue-800">@yield('title_mobile', 'Dashboard Admin')</h1>
        </div>

        <!-- Main Scrolling Content -->
        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>
        
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
