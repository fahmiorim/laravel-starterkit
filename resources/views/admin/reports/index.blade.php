@extends('admin.layouts.app')

@section('title', 'Ringkasan Laporan')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Ringkasan Laporan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pantau statistik utama donor darah secara cepat.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Total Pendonor</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($summary['total_donors']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Pendonor Aktif</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($summary['active_donors']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Jadwal Terpublikasi</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($summary['scheduled_events']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Kantong Bulan Ini</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($summary['donations_this_month']) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tren Donasi (Placeholder)</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Integrasikan chart library (misal: Chart.js) untuk menampilkan tren bulanan.</p>
            <div class="mt-6 h-56 flex items-center justify-center border border-dashed border-gray-300 dark:border-gray-600 rounded-md text-gray-400">
                Area chart akan ditempatkan di sini.
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Permintaan Terbaru</h2>
            <ul class="space-y-3">
                @forelse($recentRequests as $request)
                    <li class="border border-gray-200 dark:border-gray-700 rounded-md px-3 py-2">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $request->hospital_name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->blood_type }}{{ $request->rhesus }} • {{ $request->quantity }} kantong</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Status: {{ ucfirst($request->status) }}</p>
                    </li>
                @empty
                    <li class="text-sm text-gray-500 dark:text-gray-400">Belum ada permintaan darah.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Catatan Pengembangan</h2>
        <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1">
            <li>Tambahkan filter rentang tanggal dan ekspor PDF/Excel.</li>
            <li>Integrasikan grafik aktual menggunakan data DonorHistory.</li>
            <li>Kembangkan endpoint API untuk integrasi dashboard eksternal.</li>
        </ul>
    </div>
</div>
@endsection
