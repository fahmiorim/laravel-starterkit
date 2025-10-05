<x-guest-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-gray-200">Jadwal Donor Darah</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($schedules as $schedule)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-2 text-gray-900 dark:text-gray-100">
                            <a href="{{ route('jadwal-donor.show', $schedule) }}" class="hover:text-blue-500">
                                {{ $schedule->judul }}
                            </a>
                        </h2>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <p><span class="font-semibold">Lokasi:</span> {{ $schedule->lokasi }}</p>
                            <p><span class="font-semibold">Waktu:</span> {{ $schedule->tanggal_mulai->format('d M Y, H:i') }}</p>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 text-sm mb-4">
                            {{ Str::limit($schedule->deskripsi, 100) }}
                        </p>
                        <a href="{{ route('jadwal-donor.show', $schedule) }}" class="text-blue-500 hover:underline text-sm font-medium">Lihat Detail &rarr;</a>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3 text-center text-gray-500 dark:text-gray-400">
                    <p>Belum ada jadwal donor darah yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $schedules->links() }}
        </div>
    </div>
</x-guest-layout>
