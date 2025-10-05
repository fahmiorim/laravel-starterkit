@extends('auth.app')

@section('title', 'Verifikasi Email SIMPATI')

@section('content')
<div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-red-500 via-red-600 to-rose-600 overflow-hidden">
    <div class="absolute top-10 left-[-50px] w-80 h-80 bg-red-400 opacity-30 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-[-60px] right-[-40px] w-96 h-96 bg-rose-300 opacity-20 rounded-full blur-2xl animate-pulse"></div>
    <div class="absolute top-[25%] right-[18%] w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>

    <div class="relative z-10 w-full max-w-lg bg-white/90 backdrop-blur-md border border-white/30 rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-6">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SIMPATI" class="w-56 mx-auto mb-4">
            <h1 class="text-2xl font-semibold text-gray-900">Verifikasi Alamat Email</h1>
            <p class="mt-2 text-sm text-gray-600">Kami telah mengirim tautan verifikasi ke email Anda. Silakan cek kotak masuk atau folder spam.</p>
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                Tautan verifikasi baru sudah dikirim. Silakan periksa email Anda kembali.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf
            <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-rose-500 hover:from-red-700 hover:to-rose-600 text-white font-semibold py-2 rounded-lg shadow-md transition">
                Kirim Ulang Tautan Verifikasi
            </button>
        </form>

        <div class="mt-6 text-sm text-center text-gray-600">
            <p>Sudah menerima email? <a href="{{ route('login') }}" class="text-red-600 font-medium hover:underline">Masuk ke akun</a>.</p>
        </div>
    </div>
</div>
@endsection
