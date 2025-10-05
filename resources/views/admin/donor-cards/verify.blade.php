@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-red-700">
            <h2 class="text-xl font-semibold text-white">Verifikasi Kartu Donor Darah</h2>
        </div>
        
        <div class="p-6">
            <form action="{{ route('donor-cards.verify-card') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nomor Kartu Donor
                    </label>
                    <input type="text" 
                           name="card_number" 
                           id="card_number" 
                           required
                           placeholder="Masukkan nomor kartu donor"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                    @error('card_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Verifikasi
                    </button>
                </div>
            </form>
            
            <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-md">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                    Cara Verifikasi Kartu Donor
                </h3>
                <ol class="text-sm text-blue-700 dark:text-blue-300 space-y-1 list-decimal list-inside">
                    <li>Masukkan nomor kartu donor pada form di atas</li>
                    <li>Klik tombol "Verifikasi"</li>
                    <li>Informasi kartu donor akan ditampilkan jika nomor valid</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
