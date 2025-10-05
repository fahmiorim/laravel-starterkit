@extends('admin.layouts.app')

@section('title', 'Riwayat Donor Darah')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Riwayat Donor Darah</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola catatan kehadiran dan hasil donor pendonor.</p>
        </div>
        <a href="{{ route('admin.donor-histories.create', request('donor') ? ['donor_id' => request('donor')] : []) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md shadow hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
            <i class="fas fa-plus mr-2"></i> Tambah Riwayat
        </a>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow divide-y divide-gray-200 dark:divide-gray-700">
        <div class="p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Pendonor</label>
                    <select name="donor" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Pendonor</option>
                        @foreach($donors as $donor)
                            <option value="{{ $donor->id }}" @selected(request('donor') == $donor->id)>{{ $donor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                    <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="valid" @selected(request('status') === 'valid')>Valid</option>
                        <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                        Terapkan Filter
                    </button>
                    @if(request()->filled('status') || request()->filled('donor'))
                        <a href="{{ route('admin.donor-histories.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pendonor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kantong</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($histories as $history)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $history->donor->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $history->schedule?->judul ?? 'Mandiri' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $history->tanggal_donor->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $history->lokasi }}</td>
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
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.donor-histories.edit', $history) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Edit</a>
                                    <form action="{{ route('admin.donor-histories.destroy', $history) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada catatan riwayat donor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $histories->links() }}
        </div>
    </div>
</div>
@endsection
