@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="p-6 space-y-6 bg-gray-50 min-h-screen dark:bg-gray-900">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <x-admin.stat-card title="Total Pengguna" icon="users" color="blue">
            {{ number_format($stats['totalUsers']) }}
        </x-admin.stat-card>
        <x-admin.stat-card title="Total Pendonor" icon="droplet" color="red">
            {{ number_format($stats['totalDonors']) }}
        </x-admin.stat-card>
        <x-admin.stat-card title="Donasi Bulan Ini" icon="heart" color="rose">
            {{ number_format($stats['donationsThisMonth']) }} kantong
        </x-admin.stat-card>
        <x-admin.stat-card title="Permintaan Pending" icon="envelope" color="amber">
            {{ number_format($stats['pendingRequests']) }} permintaan
        </x-admin.stat-card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-admin.stat-card title="Pendonor Aktif" icon="users" color="green">
            {{ number_format($stats['activeDonors']) }} pendonor
        </x-admin.stat-card>
        <x-admin.stat-card title="Total Stok Darah" icon="tint" color="purple">
            {{ number_format($stats['bloodStockBags']) }} kantong
        </x-admin.stat-card>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Donor Mendatang</h2>
                <a href="{{ route('admin.schedules.index') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Kelola Jadwal</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($upcomingSchedules as $schedule)
                    <article class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $schedule->judul }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $schedule->lokasi }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($schedule->tanggal_mulai)->format('d M Y H:i') }}</p>
                        </div>
                        <span class="text-xs font-medium px-3 py-1 rounded-full {{ $schedule->status === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ucfirst($schedule->status) }}
                        </span>
                    </article>
                @empty
                    <div class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        Belum ada jadwal yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Stok Darah Rendah</h2>
                <a href="{{ route('admin.blood-stocks.index') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Detail</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($lowStocks as $stock)
                    <div class="px-6 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $stock->blood_type }} {{ $stock->rhesus }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Terakhir diperbarui {{ optional($stock->updated_at)->diffForHumans() }}</p>
                        </div>
                        <span class="text-sm font-semibold {{ $stock->quantity <= 5 ? 'text-red-600' : 'text-gray-600' }}">
                            {{ number_format($stock->quantity) }} kantong
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Data stok belum tersedia.</div>
                @endforelse
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <section class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Permintaan Darah Terbaru</h2>
                <a href="{{ route('admin.blood-requests.index') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Kelola</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Rumah Sakit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Kebutuhan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse ($recentRequests as $request)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    <div class="font-semibold">{{ $request->hospital_name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $request->patient_name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $request->blood_type }}{{ $request->rhesus }}  {{ number_format($request->quantity) }} kantong
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @class([
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200' => $request->status === 'pending',
                                            'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200' => $request->status === 'approved',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200' => $request->status === 'completed',
                                            'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200' => $request->status === 'rejected',
                                        ])
                                    ">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada permintaan darah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Donor Terakhir</h2>
                <a href="{{ route('admin.donor-histories.index') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Riwayat Lengkap</a>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($recentHistories as $history)
                    <li class="px-6 py-3">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $history->donor->name ?? 'Pendonor' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $history->tanggal_donor->format('d M Y') }}  {{ $history->jumlah_kantong }} kantong</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $history->schedule?->judul ?? 'Donor mandiri' }}</p>
                    </li>
                @empty
                    <li class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat donor yang tervalidasi.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
@endsection