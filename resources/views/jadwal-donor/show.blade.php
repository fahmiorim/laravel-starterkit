<x-guest-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <div class="mb-6">
                        <a href="{{ route('jadwal-donor.index') }}" class="text-sm text-blue-500 hover:underline">&larr; Kembali ke semua jadwal</a>
                    </div>

                    <h1 class="text-3xl font-bold mb-2">{{ $schedule->judul }}</h1>
                    
                    <div class="text-md text-gray-600 dark:text-gray-400 mb-6 border-b border-gray-200 dark:border-gray-700 pb-6">
                        <p class="font-semibold">Waktu & Lokasi</p>
                        <p>{{ $schedule->tanggal_mulai->format('l, d F Y') }}</p>
                        <p>Pukul {{ $schedule->tanggal_mulai->format('H:i') }} - {{ $schedule->tanggal_selesai ? $schedule->tanggal_selesai->format('H:i') : 'Selesai' }}</p>
                        <p>{{ $schedule->lokasi }}</p>
                    </div>

                    <div class="prose dark:prose-invert max-w-none">
                        {!! nl2br(e($schedule->deskripsi)) !!}
                    </div>

                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold mb-4">Daftar Sekarang</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Sudah menjadi pendonor? Masuk dan daftar untuk acara ini.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Masuk untuk Mendaftar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
