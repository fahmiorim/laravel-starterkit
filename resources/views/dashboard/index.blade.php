@extends('auth.app')

@section('title', 'Dashboard Pendonor')

@section('content')
<div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-red-500 via-red-600 to-rose-600 overflow-hidden">
    <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div>
    <div class="absolute top-12 left-[-40px] w-72 h-72 bg-red-300/30 rounded-full blur-3xl"></div>
    <div class="absolute bottom-[-60px] right-[-40px] w-96 h-96 bg-rose-200/30 rounded-full blur-3xl"></div>

    <div class="relative z-10 w-full max-w-6xl mx-auto space-y-6 py-10 px-4">
        <header class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2 bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/30 p-6">
                <h1 class="text-2xl font-semibold text-gray-900">Halo, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-sm text-gray-600">Terima kasih telah menjadi bagian dari pendonor darah PMI. Cek jadwal, stok, dan riwayat terbaru di bawah ini.</p>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/30 p-4">
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Pendonor</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($stats['totalDonors']) }}</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/30 p-4">
                    <p class="text-xs font-medium text-gray-500 uppercase">Kantong Bulan Ini</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($stats['donationsThisMonth']) }}</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/30 p-4">
                    <p class="text-xs font-medium text-gray-500 uppercase">Jadwal Terdekat</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($stats['upcomingCount']) }}</p>
                </div>
            </div>
        </header>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="lg:col-span-2 bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/30 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Jadwal Donor Mendatang</h2>
                    <a href="{{ route('jadwal-donor.index') }}" class="text-sm text-red-600 hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse ($upcomingSchedules as $schedule)
                        <article class="border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 flex items-start gap-3">
                            <div class="flex flex-col items-center justify-center w-16 h-16 rounded-lg bg-red-100 text-red-600">
                                <span class="text-lg font-semibold">{{ $schedule->tanggal_mulai->format('d') }}</span>
                                <span class="text-xs uppercase">{{ $schedule->tanggal_mulai->format('M') }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900">{{ $schedule->judul }}</h3>
                                <p class="text-xs text-gray-500">{{ $schedule->lokasi }}</p>
                                <p class="text-xs text-gray-500">{{ $schedule->tanggal_mulai->format('d M Y H:i') }}</p>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada jadwal yang dipublikasikan.</p>
                    @endforelse
                </div>
            </section>

            <section class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/30 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Donor Terbaru</h2>
                <ul class="space-y-3 max-h-64 overflow-auto pr-2">
                    @forelse ($recentHistories as $history)
                        <li class="border border-gray-200 rounded-lg px-3 py-2">
                            <p class="text-sm font-semibold text-gray-900">{{ $history->donor->name ?? 'Pendonor' }}</p>
                            <p class="text-xs text-gray-500">{{ $history->tanggal_donor->format('d M Y') }} • {{ $history->jumlah_kantong }} kantong</p>
                            <p class="text-xs text-gray-500">{{ $history->schedule?->judul ?? 'Donor mandiri' }}</p>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">Belum ada catatan donor valid.</li>
                    @endforelse
                </ul>
            </section>
        </div>

        <section class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl border border-white/30 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Ringkasan Stok Darah</h2>
                <a href="{{ route('admin.blood-stocks.index') }}" class="text-sm text-red-600 hover:underline">Detail Stok</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @forelse ($stats['bloodStocks'] as $stock)
                    <div class="border border-gray-200 rounded-xl px-4 py-3 text-center">
                        <p class="text-sm font-semibold text-gray-900">{{ $stock->blood_type }} {{ $stock->rhesus }}</p>
                        <p class="text-xs text-gray-500">{{ number_format($stock->quantity) }} kantong</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 col-span-full">Data stok belum tersedia.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

