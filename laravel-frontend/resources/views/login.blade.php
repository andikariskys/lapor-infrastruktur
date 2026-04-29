<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lapor Infrastruktur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-6 flex flex-col items-center">
        <!-- Logo -->
        <div class="mb-4">
            <img src="{{ asset('assets/img/Logo_lapor_infra.svg') }}" alt="Logo Lapor Infrastruktur" class="w-16 h-16 object-contain">
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-blue-800 mb-1">Lapor Infrastruktur</h1>
        <p class="text-sm text-gray-500 mb-8 text-center">Dashboard Admin - Sistem Pelaporan Infrastruktur</p>

        <!-- Login Card -->
        <div class="w-full bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 border border-gray-100">
            <form action="/dashboard" method="GET">
                <!-- Email Field -->
                <div class="mb-5">
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-2">Email Admin</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" class="block w-full pl-10 pr-3 py-2.5 border border-blue-600 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 text-gray-800" placeholder="admin@laporinfra.com" required>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-8">
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-gray-800" placeholder="••••••••••" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition duration-150 ease-in-out">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
