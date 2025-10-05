@extends('admin.layouts.app')

@section('title', 'Tambah Pendonor Baru')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Tambah Pendonor Baru</h1>
                    <p class="text-gray-600 dark:text-gray-400">Lengkapi informasi di bawah untuk menambahkan pendonor baru
                        ke dalam sistem</p>
                </div>
                <div>
                    <a href="{{ route('admin.donors.index') }}"
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

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="px-8 py-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-user-plus text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Form Tambah Pendonor</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pastikan semua informasi yang dimasukkan
                            sudah benar</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.donors.store') }}" method="POST" id="donor-form" class="p-8 space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Nama Lengkap -->
                    <div class="lg:col-span-2">
                        <label for="name"
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user mr-2 text-indigo-500"></i>
                            Nama Lengkap <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500"
                                required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik"
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-id-card mr-2 text-indigo-500"></i>
                            NIK <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500"
                                required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nomor KTA -->
                    <div>
                        <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-credit-card mr-2 text-indigo-500"></i>
                            Nomor KTA
                        </label>
                        <div
                            class="relative px-12 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-dashed border-gray-300 dark:border-gray-500 rounded-lg">
                            <div class="flex items-center justify-center text-sm text-gray-600 dark:text-gray-300">
                                <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                Nomor KTA akan dibuat otomatis setelah data disimpan.
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="gender"
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-venus-mars mr-2 text-indigo-500"></i>
                            Jenis Kelamin <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="gender" id="gender"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 appearance-none"
                                required>
                                <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-venus-mars text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="birth_date"
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                            Tanggal Lahir <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500"
                                required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="phone"
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-indigo-500"></i>
                            Nomor Telepon <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500"
                                required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Golongan Darah -->
                    <div>
                        <label for="blood_type"
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tint mr-2 text-red-500"></i>
                            Golongan Darah <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="blood_type" id="blood_type"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 appearance-none"
                                required>
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
                        <label for="rhesus"
                            class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-plus-circle mr-2 text-indigo-500"></i>
                            Rhesus <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="rhesus" id="rhesus"
                                class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 appearance-none"
                                required>
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
                </div>

                <!-- Alamat -->
                <div>
                    <label for="address"
                        class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-indigo-500"></i>
                        Alamat Lengkap <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <textarea name="address" id="address" rows="4"
                            class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-500 resize-none"
                            required>{{ old('address') }}</textarea>
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div
            class="mt-8 flex justify-end space-x-4 sticky bottom-0 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 -mx-4">
            <button type="reset" form="donor-form"
                class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                <i class="fas fa-undo-alt mr-2"></i> Reset Form
            </button>
            <button type="submit"
                class="inline-flex items-center px-6 py-3 border-2 border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i> Simpan Pendonor
            </button>
        </div>
    </div>
@endsection
