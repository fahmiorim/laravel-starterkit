@extends('auth.app')

@section('title', 'Login SIMPATI')

@section('content')
    <div
        class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-red-500 via-red-600 to-rose-600 overflow-hidden">

        {{-- Bubble Effects --}}
        <div class="absolute top-10 left-[-50px] w-80 h-80 bg-red-400 opacity-30 rounded-full blur-3xl animate-pulse"></div>
        <div
            class="absolute bottom-[-60px] right-[-40px] w-96 h-96 bg-rose-300 opacity-20 rounded-full blur-2xl animate-pulse">
        </div>
        <div class="absolute top-[30%] right-[20%] w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>

        {{-- Login Card --}}
        <div
            class="relative z-10 w-full max-w-md bg-white/90 backdrop-blur-md border border-white/30 rounded-2xl shadow-2xl p-8">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SIMPATI" class="w-64 mb-4">
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-800">Email / NIK</label>
                    <input type="text" name="email" required autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 bg-white/80 backdrop-blur-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-800">Password</label>
                    <input type="password" name="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 bg-white/80 backdrop-blur-md">
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-red-600 to-rose-500 hover:from-red-700 hover:to-rose-600 text-white font-semibold py-2 rounded-lg shadow-md transition">
                    Login
                </button>

                <div class="flex justify-between text-sm text-gray-600 mt-3">
                    <a href="{{ route('password.request') }}" class="hover:text-red-500">Lupa password?</a>
                    <a href="{{ route('register') }}" class="hover:text-red-500">Belum punya akun?</a>
                </div>
            </form>

            <p class="text-xs text-center text-gray-500 mt-6">&copy; 2025 PMI Kabupaten Batu Bara</p>
        </div>
    </div>
@endsection
