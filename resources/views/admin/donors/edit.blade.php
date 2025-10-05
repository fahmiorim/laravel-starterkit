@extends('admin.layouts.app')

@section('title', 'Edit Pendonor')

@section('content')
<div class="p-4 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Pendonor</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Perbarui data pendonor</p>
        </div>
        <a href="{{ route('admin.donors.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan input</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Form Edit Pendonor</h3>
        </div>

        <form action="{{ route('admin.donors.update', $donor->id) }}" method="POST" class="divide-y divide-gray-200 dark:divide-gray-700">
            @csrf
            @method('PUT')

            <div class="px-6 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $donor->name) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik', $donor->nik) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor KTA</label>
                        <input type="text" value="{{ $donor->kta_number ?? 'Belum tersedia' }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly disabled>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nomor KTA dikelola otomatis oleh sistem.</p>
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="gender" id="gender" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="Laki-laki" {{ old('gender', $donor->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $donor->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', optional($donor->birth_date)->format('Y-m-d')) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $donor->phone) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>

                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Golongan Darah <span class="text-red-500">*</span></label>
                        <select name="blood_type" id="blood_type" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="A" {{ old('blood_type', $donor->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('blood_type', $donor->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('blood_type', $donor->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('blood_type', $donor->blood_type) == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    <div>
                        <label for="rhesus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rhesus <span class="text-red-500">*</span></label>
                        <select name="rhesus" id="rhesus" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="+" {{ old('rhesus', $donor->rhesus) == '+' ? 'selected' : '' }}>+</option>
                            <option value="-" {{ old('rhesus', $donor->rhesus) == '-' ? 'selected' : '' }}>-</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="address" id="address" rows="3" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>{{ old('address', $donor->address) }}</textarea>
                </div>
            </div>

            <div class="mt-8 p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <a href="{{ route('admin.donors.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <i class="fas fa-save mr-2"></i> Update Pendonor
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Donor Terakhir</h3>
            <a href="{{ route('admin.donor-histories.create', ['donor_id' => $donor->id]) }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Tambah Catatan</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kantong</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse(($histories ?? collect()) as $history)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $history->tanggal_donor->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $history->lokasi }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $history->schedule?->judul ?? 'Mandiri' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900 dark:text-white">{{ $history->jumlah_kantong }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @class([
                                        'bg-yellow-100 text-yellow-800' => $history->status === 'pending',
                                        'bg-green-100 text-green-800' => $history->status === 'valid',
                                        'bg-red-100 text-red-800' => $history->status === 'ditolak',
                                    ])
                                ">
                                    {{ ucfirst($history->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada catatan riwayat untuk pendonor ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
