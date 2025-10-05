<x-guest-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <div class="flex items-center mb-6">
                        <div class="flex-shrink-0 h-20 w-20 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                            <span class="text-primary-600 dark:text-primary-300 font-bold text-3xl">
                                {{ strtoupper(substr($donor->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-3xl font-bold">{{ $donor->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">Pendonor Darah PMI</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-8">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. KTA</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->kta_number ?? '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Golongan Darah</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->blood_type }} {{ $donor->rhesus }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Donasi</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->total_donations }} kali</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Donasi Terakhir</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $donor->last_donation_date ? $donor->last_donation_date->format('d F Y') : 'Belum ada' }}</dd>
                            </div>
                        </dl>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
