<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Jadwal Donor Darah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul -->
                            <div class="mb-4">
                                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Judul Acara') }}</label>
                                <input type="text" name="judul" id="judul" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" value="{{ old('judul', $schedule->judul) }}" required>
                                @error('judul') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Lokasi -->
                            <div class="mb-4">
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Lokasi') }}</label>
                                <input type="text" name="lokasi" id="lokasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" value="{{ old('lokasi', $schedule->lokasi) }}" required>
                                @error('lokasi') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Tanggal Mulai -->
                            <div class="mb-4">
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tanggal Mulai') }}</label>
                                <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-white" value="{{ old('tanggal_mulai', $schedule->tanggal_mulai->format('Y-m-d\TH:i')) }}" required>
                                @error('tanggal_mulai') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="mb-4">
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tanggal Selesai (Opsional)') }}</label>
                                <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-white" value="{{ old('tanggal_selesai', $schedule->tanggal_selesai ? $schedule->tanggal_selesai->format('Y-m-d\TH:i') : '') }}">
                                @error('tanggal_selesai') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Penanggung Jawab -->
                            <div class="mb-4">
                                <label for="penanggung_jawab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Penanggung Jawab (Opsional)') }}</label>
                                <input type="text" name="penanggung_jawab" id="penanggung_jawab" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" value="{{ old('penanggung_jawab', $schedule->penanggung_jawab) }}">
                                @error('penanggung_jawab') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-white" required>
                                    <option value="draft" @if(old('status', $schedule->status) == 'draft') selected @endif>{{ __('Draft') }}</option>
                                    <option value="published" @if(old('status', $schedule->status) == 'published') selected @endif>{{ __('Published') }}</option>
                                    <option value="canceled" @if(old('status', $schedule->status) == 'canceled') selected @endif>{{ __('Canceled') }}</option>
                                </select>
                                @error('status') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Deskripsi (Opsional)') }}</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">{{ old('deskripsi', $schedule->deskripsi) }}</textarea>
                            @error('deskripsi') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.schedules.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md mr-4">
                                {{ __('Batal') }}
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ __('Simpan Perubahan') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
