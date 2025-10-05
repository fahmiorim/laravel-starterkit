@extends('auth.app')

@section('title', 'Selamat Datang di SIMPATI PMI')

@push('styles')
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
@endpush

@section('content')
    <div class="bg-gray-100 text-gray-800">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md shadow-md">
            <div class="container mx-auto px-6 py-3 flex justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SIMPATI" class="h-10 mr-3">
                    <span class="font-bold text-xl text-red-700">SIMPATI PMI</span>
                </div>
                <div class="flex items-center space-x-2 md:space-x-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-red-600 font-semibold rounded-lg hover:bg-red-100 transition">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition shadow-md">Register</a>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section id="hero" class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-red-500 via-red-600 to-rose-600 text-white overflow-hidden">
            {{-- Bubble Effects --}}
            <div class="absolute top-10 left-[-50px] w-80 h-80 bg-red-400 opacity-30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-[-60px] right-[-40px] w-96 h-96 bg-rose-300 opacity-20 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute top-[30%] right-[20%] w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>

            <div class="relative z-10 text-center p-8 mt-16">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">Selamat Datang di SIMPATI</h1>
                <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto drop-shadow-md">Aplikasi resmi PMI Kabupaten Batu Bara untuk mempermudah proses pendataan, pelayanan, dan komunikasi dalam kegiatan donor darah.</p>
                <a href="#features" class="bg-white text-red-600 font-bold py-3 px-8 rounded-full hover:bg-gray-200 transition text-lg shadow-xl">
                    Lihat Fitur <i class="fas fa-arrow-down ml-2"></i>
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold mb-2">Fitur Unggulan</h2>
                <p class="text-lg text-gray-600 mb-12">Akses berbagai layanan dan informasi PMI dalam satu genggaman.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Feature 1 -->
                    <div class="bg-gray-50 p-8 rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <div class="text-red-500 mb-4">
                            <i class="fas fa-address-book fa-3x"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Manajemen Data Pendonor</h3>
                        <p>Petugas dapat mencatat dan mengelola data pendonor secara terpusat dan digital.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="bg-gray-50 p-8 rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <div class="text-red-500 mb-4">
                            <i class="fas fa-calendar-check fa-3x"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Jadwal & Riwayat Donor</h3>
                        <p>Akses jadwal kegiatan, pantau riwayat donor Anda, dan dapatkan notifikasi.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="bg-gray-50 p-8 rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <div class="text-red-500 mb-4">
                            <i class="fas fa-id-card fa-3x"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">KTA Digital & Notifikasi</h3>
                        <p>Cetak Kartu Tanda Anggota (KTA) dan terima notifikasi saat darah Anda telah digunakan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 bg-gray-100">
            <div class="container mx-auto px-6 flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0 text-center">
                    <img src="{{ asset('assets/images/logo_white.png') }}" alt="Tentang SIMPATI" class="rounded-lg shadow-2xl mx-auto bg-red-600 p-4" style="max-width: 300px;">
                </div>
                <div class="md:w-1/2 md:pl-12">
                    <h2 class="text-4xl font-bold mb-4">Tentang SIMPATI PMI</h2>
                    <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                        SIMPATI adalah aplikasi resmi PMI Kabupaten Batu Bara yang dirancang untuk mempermudah proses pendataan, pelayanan, dan komunikasi dalam kegiatan donor darah. 
                    </p>
                    <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                        Aplikasi ini mengintegrasikan berbagai layanan PMI ke dalam satu sistem digital terpadu, sehingga memudahkan petugas dan masyarakat dalam mengakses informasi serta layanan donor darah secara cepat dan akurat.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Dengan tampilan yang sederhana dan sistem yang inovatif, SIMPATI menjadi langkah nyata dalam mendukung digitalisasi layanan kemanusiaan di Kabupaten Batu Bara.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-6 text-center">
                <p>&copy; 2025 PMI Kabupaten Batu Bara. All rights reserved.</p>
                <p class="text-sm text-gray-400 mt-2">Dibuat dengan <i class="fas fa-heart text-red-500"></i> untuk kemanusiaan</p>
            </div>
        </footer>
    </div>
@endsection