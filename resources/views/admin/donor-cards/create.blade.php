@extends('admin.layouts.app')

@section('title', 'Buat Kartu Donor Baru')

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div class="mb-4 md:mb-0">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Buat Kartu Donor Baru</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lengkapi form di bawah untuk membuat kartu donor baru</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button id="theme-toggle" type="button"
                                class="p-2.5 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700 transition-colors">
                                <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                                <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                        fill-rule="evenodd" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <a href="{{ route('admin.donor-cards.index') }}"
                                class="inline-flex items-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>

                        <form action="{{ route('admin.donor-cards.store') }}" method="POST" enctype="multipart/form-data" class="space-y-0">
                            @csrf

                            <div class="bg-white dark:bg-gray-800">
                                <div class="p-6 md:p-8">
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                        <!-- Left Column -->
                                        <div class="lg:col-span-2 space-y-6">
                                        <!-- Donor Selection -->
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg transition-all duration-200 hover:shadow-sm">
                                            <label for="donor_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Pilih Pendonor <span class="text-red-500">*</span>
                                            </label>
                                            <div class="mt-1">
                                                <select name="donor_id" id="donor_id"
                                                    class="select2 block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/50 dark:bg-gray-700 dark:text-gray-100 text-sm transition duration-150 ease-in-out @error('donor_id') border-red-300 ring-2 ring-red-200 @enderror"
                                                    required>
                                                    <option value="">-- Pilih Pendonor --</option>
                                                    @foreach ($donors as $id => $name)
                                                        <option value="{{ $id }}" class="py-2"
                                                            {{ old('donor_id') == $id ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('donor_id')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Issue Date -->
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg transition-all duration-200 hover:shadow-sm">
                                            <label for="issue_date"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Tanggal Terbit <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative mt-1">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <input type="date" name="issue_date" id="issue_date"
                                                    class="block w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/50 dark:bg-gray-700 dark:text-gray-100 text-sm transition duration-150 ease-in-out @error('issue_date') border-red-300 ring-2 ring-red-200 @enderror"
                                                    value="{{ old('issue_date', now()->format('Y-m-d')) }}" required>
                                                @error('issue_date')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Expiry Date -->
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg transition-all duration-200 hover:shadow-sm">
                                            <label for="expiry_date"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Masa Berlaku <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative mt-1">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <input type="date" name="expiry_date" id="expiry_date"
                                                    class="block w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/50 dark:bg-gray-700 dark:text-gray-100 text-sm transition duration-150 ease-in-out @error('expiry_date') border-red-300 ring-2 ring-red-200 @enderror"
                                                    value="{{ old('expiry_date', now()->addYears(2)->format('Y-m-d')) }}"
                                                    required>
                                                @error('expiry_date')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Photo Upload -->
                                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg transition-all duration-200 hover:shadow-sm">
                                            <label for="photo"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Foto KTA (Opsional)
                                            </label>
                                            <div class="mt-1">
                                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg transition duration-150 ease-in-out hover:border-blue-400 dark:hover:border-blue-500 @error('photo') border-red-300 ring-2 ring-red-200 @enderror">
                                                    <div class="space-y-1 text-center">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="flex text-sm text-gray-600 dark:text-gray-300">
                                                            <label for="photo" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                                <span>Upload file</span>
                                                                <input id="photo" name="photo" type="file" class="sr-only" accept="image/*">
                                                            </label>
                                                            <p class="pl-1">atau seret ke sini</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            Format: JPG, PNG, JPEG (Maks. 2MB)
                                                        </p>
                                                    </div>
                                                </div>
                                                @error('photo')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>

                                        </div> <!-- End Left Column -->

                                        <!-- Right Column -->
                                        <div class="space-y-6">
                                            <!-- Photo Upload and Preview Card -->
                                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                                                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                                                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Foto KTA</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Unggah foto KTA donor darah</p>
                                                </div>
                                                <div class="p-5">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <!-- Image Preview Container -->
                                                        <div id="image-preview" class="mb-4 w-full">
                                                            <div class="relative rounded-lg overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-colors duration-200 p-1">
                                                                <img id="preview-image" src="https://placehold.co/600x400/e5e7eb/9ca3af?text=Preview+Gambar" 
                                                                    alt="Preview Gambar" 
                                                                    class="w-full h-48 object-cover rounded-md">
                                                                <input type="file" id="photo" name="photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                                                                <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('photo').click()">
                                                                    <span class="text-white text-sm font-medium">Klik untuk mengganti</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Upload Button -->
                                                        <div class="w-full">
                                                            <label for="photo" class="w-full flex flex-col items-center px-4 py-2 bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                                                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                                </svg>
                                                                <span class="text-sm font-medium">Unggah Foto</span>
                                                                <input id="photo" name="photo" type="file" class="hidden" accept="image/jpeg, image/png, image/jpg" onchange="previewImage(event)">
                                                            </label>
                                                            <p class="mt-2 text-xs text-center text-gray-500 dark:text-gray-400">
                                                                Format: JPG, PNG, JPEG (Maks. 2MB)
                                                            </p>
                                                        </div>
                                                    </div>
                                                    @error('photo')
                                                        <p class="mt-3 text-sm text-red-600 dark:text-red-400 flex items-center">
                                                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                            <span>{{ $message }}</span>
                                                        </p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Notes Card -->
                                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                                                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                                                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Catatan</h3>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambahkan catatan jika diperlukan</p>
                                                </div>
                                                <div class="p-5">
                                                    <div class="relative">
                                                        <textarea name="notes" id="notes" rows="5"
                                                            class="block w-full px-4 py-3 rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/50 dark:bg-gray-700 dark:text-gray-100 text-sm transition duration-150 ease-in-out @error('notes') border-red-300 ring-2 ring-red-200 @enderror"
                                                            placeholder="Masukkan catatan tambahan...">{{ old('notes') }}</textarea>
                                                        <div class="absolute bottom-3 right-3 text-xs text-gray-400 bg-white dark:bg-gray-800 px-2 py-0.5 rounded">
                                                            <span id="notes-count">{{ strlen(old('notes') ?? '') }}</span>/500
                                                        </div>
                                                    </div>
                                                    @error('notes')
                                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-start">
                                                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                            <span>{{ $message }}</span>
                                                        </p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div> <!-- End Right Column -->

                                    </div>

                                    <!-- Form Actions -->
                                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                                        <a href="{{ route('admin.donor-cards.index') }}"
                                            class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batal
                                        </a>
                                        <button type="submit"
                                            class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Simpan Kartu Donor
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Light mode styles */
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px 10px;
            border: 1px solid #d1d5db;
            background-color: #fff;
        }

        /* Dark mode styles */
        .dark .select2-container--default .select2-selection--single {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 5px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
            padding-left: 0;
            color: #111827;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f3f4f6;
        }

        .select2-container--default .select2-selection--single:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .select2-dropdown {
            border-color: #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Dark mode dropdown */
        .select2-container--default .select2-results__option {
            color: #111827;
            background-color: #ffffff;
        }

        .dark .select2-dropdown {
            background-color: #374151;
            border-color: #4b5563;
        }

        .dark .select2-container--default .select2-results__option {
            color: #f3f4f6;
            background-color: #374151;
        }

        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #1e40af;
            color: #ffffff;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: #d1d5db;
            border-radius: 0.375rem;
            padding: 0.375rem 0.5rem;
        }

        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #4b5563;
            border-color: #6b7280;
            color: #f3f4f6;
        }

        /* Fix for select2 in modal */
        .select2-container {
            z-index: 1056;
            /* Higher than modal z-index */
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Image Preview Function
        function previewImage(event) {
            event.preventDefault();
            const input = event.target;
            const preview = document.getElementById('preview-image');
            const previewContainer = document.getElementById('image-preview');
            
            if (input.files && input.files[0]) {
                // Check file size (max 2MB)
                if (input.files[0].size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB');
                    input.value = '';
                    return false;
                }
                
                // Check file type
                const fileType = input.files[0].type;
                const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validImageTypes.includes(fileType)) {
                    alert('Format file tidak didukung. Gunakan format JPG, JPEG, atau PNG');
                    input.value = '';
                    return false;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    // Show the preview container if it was hidden
                    if (previewContainer) {
                        previewContainer.classList.remove('hidden');
                    }
                }
                
                reader.onerror = function() {
                    console.error('Error reading file');
                    alert('Gagal memuat gambar. Silakan coba lagi.');
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                // Fallback to placeholder if no file is selected
                preview.src = "{{ asset('images/placeholder-image.jpg') }}";
                if (previewContainer) {
                    previewContainer.classList.remove('hidden');
                }
            }
        }
        
        // Drag and drop functionality
        const dropArea = document.querySelector('.border-dashed');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropArea.classList.add('border-blue-400');
        }
        
        function unhighlight() {
            dropArea.classList.remove('border-blue-400');
        }
        
        dropArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const input = document.getElementById('photo');
            
            if (files.length) {
                input.files = files;
                const event = new Event('change');
                input.dispatchEvent(event);
            }
        }

        $(document).ready(function() {
            // Character counter for notes
            $('#notes').on('input', function() {
                const maxLength = 500;
                const currentLength = $(this).val().length;
                const remaining = maxLength - currentLength;
                
                $('#notes-count').text(currentLength);
                
                if (remaining < 0) {
                    $(this).val($(this).val().substring(0, maxLength));
                    $('#notes-count').text(maxLength);
                }
            });
            
            // Initialize Select2
            $('.select2').select2({
                placeholder: '-- Pilih Pendonor --',
                allowClear: true,
                width: '100%',
                dropdownParent: $('form')
            });

            // Theme toggle functionality
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Change the icons inside the button based on previous settings
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window
                    .matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            // Add click event listener to the theme toggle button
            themeToggleBtn.addEventListener('click', function() {
                // Toggle icons
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // If set via local storage previously
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }

                // Refresh Select2 to apply dark mode styles
                $('.select2').select2('destroy').select2({
                    placeholder: '-- Pilih Pendonor --',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('form')
                });
            });
        });
    </script>
@endpush
