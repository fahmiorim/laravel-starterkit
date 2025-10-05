@extends('admin.layouts.app')

@section('title', 'Permintaan Darah')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Permintaan Darah</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola permintaan dari rumah sakit atau pihak eksternal.</p>
        </div>
        <a href="{{ route('admin.blood-requests.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md shadow hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
            <i class="fas fa-plus mr-2"></i> Tambah Permintaan
        </a>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow divide-y divide-gray-200 dark:divide-gray-700">
        <div class="p-4">
            <form method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                    <select name="status" class="w-full md:w-48 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                        <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                        Terapkan
                    </button>
                    @if(request()->filled('status'))
                        <a href="{{ route('admin.blood-requests.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rumah Sakit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kebutuhan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $request->hospital_name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Diinput oleh {{ $request->processor->name ?? 'Sistem' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $request->patient_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                <span class="font-semibold">{{ $request->blood_type }} {{ $request->rhesus }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">— {{ $request->quantity }} kantong</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($request->notes, 60) ?: '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @class([
                                        'bg-yellow-100 text-yellow-800' => $request->status === 'pending',
                                        'bg-green-100 text-green-800' => $request->status === 'approved',
                                        'bg-blue-100 text-blue-800' => $request->status === 'completed',
                                        'bg-red-100 text-red-800' => $request->status === 'rejected',
                                    ])
                                ">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.blood-requests.edit', $request) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Kelola</a>
                                    <form action="{{ route('admin.blood-requests.destroy', $request) }}" method="POST" onsubmit="return confirm('Hapus permintaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada permintaan darah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection


