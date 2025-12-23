<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KPPN Yogyakarta - Selamat Datang</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80"
                alt="Kantor KPPN Yogyakarta" class="w-full h-full object-cover">
            <!-- Overlay untuk readability -->
            <div class="absolute inset-0 bg-black/60"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Navbar -->
            <nav class="w-full px-6 py-4 lg:px-8">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                            <!-- Logo KPPN - ganti dengan logo asli -->
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Logo_kementerian_keuangan_republik_indonesia.png/969px-Logo_kementerian_keuangan_republik_indonesia.png"
                                alt="Logo" style="height: 40px; width: 40px;">
                        </div>
                        <div class="text-white">
                            <h1 class="text-xl font-bold">KPPN Yogyakarta</h1>
                            <p class="text-sm text-gray-200">Kantor Pelayanan Perbendaharaan Negara</p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="px-6 py-2 bg-transparent border border-white text-white rounded-lg hover:bg-white hover:text-gray-900 transition-colors duration-300">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-300">
                                    Masuk
                                </a>
                                <!-- @if (Route::has('register'))
                                                    <a href="{{ route('register') }}"
                                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-300">
                                                        Daftar
                                                    </a>
                                                @endif -->
                            @endauth
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="flex-1 flex items-center justify-center px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center text-white">
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
                        Selamat Datang di
                        <span class="block text-blue-400 mt-2">KPPN Yogyakarta</span>
                    </h1>

                    <p class="text-xl md:text-2xl mb-8 text-gray-200 max-w-3xl mx-auto leading-relaxed">
                        Kantor Pelayanan Perbendaharaan Negara Yogyakarta melayani kebutuhan administrasi keuangan
                        negara dengan profesional dan terpercaya.
                    </p><br>
                </div>
            </div>

            <!-- Features Section -->
            <div class="bg-white/10 backdrop-blur-sm border-t border-white/20">
                <div class="max-w-7xl mx-auto px-6 py-12 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center text-white">
                            <div
                                class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Pelayanan Terpercaya</h3>
                            <p class="text-gray-200 text-sm">
                                Melayani dengan standar pelayanan publik yang berkualitas dan dapat diandalkan.
                            </p>
                        </div>

                        <div class="text-center text-white">
                            <div
                                class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Proses Cepat</h3>
                            <p class="text-gray-200 text-sm">
                                Sistem digital yang memungkinkan proses administrasi lebih cepat dan efisien.
                            </p>
                        </div>

                        <div class="text-center text-white">
                            <div
                                class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Keamanan Terjamin</h3>
                            <p class="text-gray-200 text-sm">
                                Data dan transaksi Anda dilindungi dengan sistem keamanan tingkat tinggi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Footer -->
            <footer class="bg-black/30 backdrop-blur-sm border-t border-white/20">
                <div class="max-w-7xl mx-auto px-6 py-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center text-white">
                        <div class="mb-4 md:mb-0">
                            <p class="text-sm text-gray-200">© 2025 KPPN Yogyakarta. Semua hak dilindungi undang-undang.
                            </p>
                        </div>
                        <div class="flex space-x-6 text-sm">
                            <a href="https://wa.me/+6281225487266" class="text-gray-200 hover:text-white transition-colors">Kontak</a>
                            <a href="#" class="text-gray-200 hover:text-white transition-colors">Bantuan</a>
                            <a href="#" class="text-gray-200 hover:text-white transition-colors">Kebijakan Privasi</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>