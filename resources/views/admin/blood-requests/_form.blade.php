@csrf

<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
            <i class="fas fa-hospital mr-2 text-primary-600"></i> Informasi Rumah Sakit
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1">
                <label for="hospital_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama Rumah Sakit <span class="text-red-500">*</span>
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-hospital text-gray-400"></i>
                    </div>
                    <input type="text" name="hospital_name" id="hospital_name" 
                           value="{{ old('hospital_name', $bloodRequest->hospital_name ?? '') }}"
                           class="pl-10 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Nama rumah sakit"
                           required>
                </div>
                @error('hospital_name')
                    <p class="mt-1 text-xs text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="patient_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama Pasien <span class="text-red-500">*</span>
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-injured text-gray-400"></i>
                    </div>
                    <input type="text" name="patient_name" id="patient_name" 
                           value="{{ old('patient_name', $bloodRequest->patient_name ?? '') }}"
                           class="pl-10 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Nama lengkap pasien"
                           required>
                </div>
                @error('patient_name')
                    <p class="mt-1 text-xs text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
            <i class="fas fa-tint mr-2 text-primary-600"></i> Detail Darah
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-1">
                <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Golongan Darah <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="blood_type" id="blood_type" 
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            required>
                        <option value="" disabled selected>Pilih golongan darah</option>
                        @foreach(['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('blood_type', $bloodRequest->blood_type ?? '') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                @error('blood_type')
                    <p class="mt-1 text-xs text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="rhesus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Rhesus <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['+' => 'Positif (+)', '-' => 'Negatif (-)'] as $value => $label)
                        <div class="flex items-center">
                            <input type="radio" name="rhesus" id="rhesus_{{ $value }}" 
                                   value="{{ $value }}" 
                                   @checked(old('rhesus', $bloodRequest->rhesus ?? '+') === $value)
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <label for="rhesus_{{ $value }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('rhesus')
                    <p class="mt-1 text-xs text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Jumlah Kantong <span class="text-red-500">*</span>
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-tint text-gray-400"></i>
                    </div>
                    <input type="number" name="quantity" id="quantity" min="1" 
                           value="{{ old('quantity', $bloodRequest->quantity ?? 1) }}"
                           class="pl-10 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                           required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">kantong</span>
                    </div>
                </div>
                @error('quantity')
                    <p class="mt-1 text-xs text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">
            <i class="fas fa-info-circle mr-2 text-primary-600"></i> Informasi Tambahan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="status" id="status" 
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                            required>
                        @foreach(['pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'completed' => 'Selesai'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $bloodRequest->status ?? 'pending') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('status')
                    <p class="mt-1 text-xs text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Catatan Tambahan
                </label>
                <div class="relative rounded-md shadow-sm">
                    <textarea name="notes" id="notes" rows="3" 
                              class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Masukkan catatan tambahan (opsional)">{{ old('notes', $bloodRequest->notes ?? '') }}</textarea>
                </div>
                @error('notes')
                    <p class="mt-1 text-xs text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="mt-8 flex justify-end space-x-3">
    <a href="{{ route('admin.blood-requests.index') }}" 
       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
        <i class="fas fa-times mr-2"></i> Batal
    </a>
    <button type="submit" 
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
        <i class="fas fa-save mr-2"></i> Simpan Permintaan
    </button>
</div>

