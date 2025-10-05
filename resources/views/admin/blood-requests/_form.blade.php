@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="hospital_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Rumah Sakit <span class="text-red-500">*</span></label>
        <input type="text" name="hospital_name" id="hospital_name" value="{{ old('hospital_name', $bloodRequest->hospital_name ?? '') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
        @error('hospital_name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="patient_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pasien <span class="text-red-500">*</span></label>
        <input type="text" name="patient_name" id="patient_name" value="{{ old('patient_name', $bloodRequest->patient_name ?? '') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
        @error('patient_name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Golongan Darah <span class="text-red-500">*</span></label>
        <select name="blood_type" id="blood_type" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
            @foreach(['A','B','AB','O'] as $type)
                <option value="{{ $type }}" @selected(old('blood_type', $bloodRequest->blood_type ?? '') === $type)>{{ $type }}</option>
            @endforeach
        </select>
        @error('blood_type')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="rhesus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rhesus <span class="text-red-500">*</span></label>
        <select name="rhesus" id="rhesus" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
            @foreach(['+','-'] as $sign)
                <option value="{{ $sign }}" @selected(old('rhesus', $bloodRequest->rhesus ?? '') === $sign)>{{ $sign }}</option>
            @endforeach
        </select>
        @error('rhesus')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Kantong <span class="text-red-500">*</span></label>
        <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', $bloodRequest->quantity ?? 1) }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
        @error('quantity')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
        <select name="status" id="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
            @foreach(['pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'completed' => 'Selesai'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $bloodRequest->status ?? 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
        <textarea name="notes" id="notes" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('notes', $bloodRequest->notes ?? '') }}</textarea>
        @error('notes')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.blood-requests.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Batal</a>
    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
        Simpan
    </button>
</div>

