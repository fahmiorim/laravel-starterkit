@extends('admin.layouts.app')

@section('title', 'Manajemen Stok Darah')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Stok Darah</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pantau dan perbarui stok darah. Masa simpan darah: 35 hari sejak tanggal donor.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="h-3 w-3 rounded-full bg-green-500"></span>
            <span class="text-sm text-gray-600 dark:text-gray-400">Aktif</span>
            <span class="h-3 w-3 rounded-full bg-amber-500 ml-2"></span>
            <span class="text-sm text-gray-600 dark:text-gray-400">Akan Kadaluarsa</span>
            <span class="h-3 w-3 rounded-full bg-red-500 ml-2"></span>
            <span class="text-sm text-gray-600 dark:text-gray-400">Kadaluarsa</span>
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Golongan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Donor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kadaluarsa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Terakhir Diperbarui</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Kantong</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($stocks as $stock)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $stock->label }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                {{ $stock->donation_date ? $stock->donation_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                @if($stock->expiry_date)
                                    <div class="{{ $stock->isExpired() ? 'text-red-600' : ($stock->isAboutToExpire() ? 'text-amber-600' : 'text-gray-500') }}">
                                        {{ $stock->expiry_date->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $stock->expiry_date->diffForHumans() }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($stock->isExpired())
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Kadaluarsa
                                    </span>
                                @elseif($stock->isAboutToExpire())
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                        Akan Kadaluarsa
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                <div>{{ optional($stock->updated_at)->format('d M Y H:i') ?? '-' }}</div>
                                <div class="text-xs">{{ $stock->updatedBy->name ?? 'Belum ada' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.blood-stocks.update', $stock) }}" method="POST" class="flex items-center justify-end gap-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" min="0" value="{{ old('quantity', $stock->quantity) }}" class="w-24 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                    <button type="submit" class="px-3 py-1.5 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                                        Simpan
                                    </button>
                                </form>
                                @error('quantity')
                                    <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data stok darah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
