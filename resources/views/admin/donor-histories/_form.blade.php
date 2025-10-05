@csrf
@php($donorHistory = $donorHistory ?? new \App\Models\DonorHistory())

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="donor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pendonor <span class="text-red-500">*</span></label>
        <select name="donor_id" id="donor_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
            <option value="">Pilih pendonor</option>
            @foreach($donors as $donor)
                <option value="{{ $donor->id }}" @selected(old('donor_id', $donorHistory->donor_id) == $donor->id)>{{ $donor->name }} ({{ $donor->blood_type }}{{ $donor->rhesus }})</option>
            @endforeach
        </select>
        @error('donor_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="blood_donation_schedule_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jadwal Terkait</label>
        <select name="blood_donation_schedule_id" id="blood_donation_schedule_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">Mandiri</option>
            @foreach($schedules as $schedule)
                <option value="{{ $schedule->id }}" @selected(old('blood_donation_schedule_id', $donorHistory->blood_donation_schedule_id) == $schedule->id)>{{ $schedule->judul }} ({{ optional($schedule->tanggal_mulai)->format('d M Y') }})</option>
            @endforeach
        </select>
        @error('blood_donation_schedule_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tanggal_donor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Donor <span class="text-red-500">*</span></label>
        <input type="date" name="tanggal_donor" id="tanggal_donor" value="{{ old('tanggal_donor', optional($donorHistory->tanggal_donor)->format('Y-m-d')) }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
        @error('tanggal_donor')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="jumlah_kantong" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Kantong <span class="text-red-500">*</span></label>
        <input type="number" name="jumlah_kantong" id="jumlah_kantong" min="1" value="{{ old('jumlah_kantong', $donorHistory->jumlah_kantong ?? 1) }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
        @error('jumlah_kantong')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
        <select name="status" id="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
            <option value="pending" @selected(old('status', $donorHistory->status ?? 'pending') === 'pending')>Pending</option>
            <option value="valid" @selected(old('status', $donorHistory->status ?? 'pending') === 'valid')>Valid</option>
            <option value="ditolak" @selected(old('status', $donorHistory->status ?? 'pending') === 'ditolak')>Ditolak</option>
        </select>
        @error('status')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi <span class="text-red-500">*</span></label>
        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $donorHistory->lokasi) }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
        @error('lokasi')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
        <textarea name="note" id="note" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('note', $donorHistory->note) }}</textarea>
        @error('note')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.donor-histories.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Batal</a>
    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
        Simpan
    </button>
</div>
