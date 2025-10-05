@extends('admin.layouts.app')

@section('title', 'Tambah Jadwal Donor')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Tambah Jadwal Donor Darah</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lengkapi form di bawah untuk menambahkan jadwal baru</p>
            </div>

            <form action="{{ route('admin.schedules.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid gap-6 md:grid-cols-2">
            <!-- Kolom Kiri -->
            <div class="space-y-4">
                <!-- Judul -->
                <div>
                    <div class="space-y-1">
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Acara</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Contoh: Donor Darah Rutin PMI" required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Lokasi -->
                <div>
                    <div class="space-y-1">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Contoh: Gedung PMI Kota Bandung" required>
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <div class="space-y-1">
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal & Waktu Mulai</label>
                        <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" 
                               value="{{ old('tanggal_mulai') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                               required>
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <div class="space-y-1">
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal & Waktu Selesai (Opsional)</label>
                        <input type="datetime-local" id="tanggal_selesai" name="tanggal_selesai" 
                               value="{{ old('tanggal_selesai') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ada waktu selesai tertentu</p>
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-4">
                <!-- Penanggung Jawab -->
                <div>
                    <div class="space-y-1">
                        <label for="penanggung_jawab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penanggung Jawab (Opsional)</label>
                        <input type="text" id="penanggung_jawab" name="penanggung_jawab" 
                               value="{{ old('penanggung_jawab') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Nama penanggung jawab">
                        @error('penanggung_jawab')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <div class="space-y-1">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="status" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                required>
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2 space-y-1">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi (Opsional)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Tambahkan deskripsi acara...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

                </div>

                <!-- Tombol Aksi -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 md:col-span-2">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 sm:justify-end">
                        <a href="{{ route('admin.schedules.index') }}" 
                           class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Simpan Jadwal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
