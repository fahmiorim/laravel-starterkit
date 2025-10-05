@extends('admin.layouts.app')

@section('title', 'Tambah Stok Darah')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Tambah Stok Darah</h1>
                    <p class="text-gray-600 dark:text-gray-400">Lengkapi informasi di bawah untuk menambahkan stok darah ke dalam sistem</p>
                </div>
                <div>
                    <a href="{{ route('admin.blood-stocks.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left mr-2 text-gray-500"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Terdapat kesalahan input</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-center">
                                        <i class="fas fa-times-circle mr-2 text-xs"></i>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-800 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-tint text-red-600 dark:text-red-400 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Form Tambah Stok Darah</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pastikan semua informasi yang dimasukkan sudah benar</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.blood-stocks.store') }}" method="POST" id="blood-stock-form" class="p-8 space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Golongan Darah -->
                    <div>
                        <label for="blood_type" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tint mr-2 text-red-500"></i>
                            Golongan Darah <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="blood_type" id="blood_type"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 appearance-none"
                                required>
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A" {{ old('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tint text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Rhesus -->
                    <div>
                        <label for="rhesus" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-plus-circle mr-2 text-indigo-500"></i>
                            Rhesus <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="rhesus" id="rhesus"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 appearance-none"
                                required>
                                <option value="">Pilih Rhesus</option>
                                <option value="+" {{ old('rhesus') == '+' ? 'selected' : '' }}>+ (Positif)</option>
                                <option value="-" {{ old('rhesus') == '-' ? 'selected' : '' }}>- (Negatif)</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-plus-circle text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jumlah Stok -->
                    <div>
                        <label for="quantity" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-boxes mr-2 text-green-500"></i>
                            Jumlah Stok <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="0"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500"
                                required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-boxes text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Donasi -->
                    <div>
                        <label for="donation_date" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                            Tanggal Donasi <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="donation_date" id="donation_date" value="{{ old('donation_date') }}"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500"
                                required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Kadaluarsa -->
                    <div>
                        <label for="expiry_date" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-times mr-2 text-orange-500"></i>
                            Tanggal Kadaluarsa <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500"
                                required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-times text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Batas Stok Minimum -->
                    <div>
                        <label for="low_stock_threshold" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>
                            Batas Stok Minimum
                        </label>
                        <div class="relative">
                            <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold') }}" min="1"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-yellow-500 focus:ring-yellow-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-exclamation-triangle text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jumlah minimum stok sebelum peringatan stok rendah</p>
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="notes" class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-purple-500"></i>
                        Catatan
                    </label>
                    <div class="relative">
                        <textarea name="notes" id="notes" rows="4" maxlength="1000"
                            class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 resize-none"
                            placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <i class="fas fa-sticky-note text-gray-400"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal 1000 karakter</p>
                </div>
            </form>
        </div>

        <div class="mt-8 flex justify-end space-x-4 sticky bottom-0 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 -mx-4">
            <button type="reset" form="blood-stock-form"
                class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                <i class="fas fa-undo-alt mr-2"></i> Reset Form
            </button>
            <button type="submit"
                class="inline-flex items-center px-6 py-3 border-2 border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i> Simpan Stok Darah
            </button>
        </div>
    </div>
@endsection
