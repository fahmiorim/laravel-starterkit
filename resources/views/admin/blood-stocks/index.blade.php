@extends('admin.layouts.app')

@section('title', 'Manajemen Stok Darah')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Stok Darah</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pantau dan kelola stok darah. Masa simpan darah: 35 hari sejak tanggal donor.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('admin.blood-stocks.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Stok
            </a>
            <div class="flex items-center space-x-2">
                <span class="h-3 w-3 rounded-full bg-green-500"></span>
                <span class="text-sm text-gray-600 dark:text-gray-400">Aktif</span>
                <span class="h-3 w-3 rounded-full bg-amber-500 ml-2"></span>
                <span class="text-sm text-gray-600 dark:text-gray-400">Akan Kadaluarsa</span>
                <span class="h-3 w-3 rounded-full bg-red-500 ml-2"></span>
                <span class="text-sm text-gray-600 dark:text-gray-400">Kadaluarsa</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif
    
    @if($lowStocks->isNotEmpty())
        <div class="bg-amber-50 border-l-4 border-amber-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-amber-700">
                        <strong>Peringatan:</strong> Stok darah berikut ini sudah mencapai batas minimum:
                        @foreach($lowStocks as $stock)
                            {{ $stock->blood_type . $stock->rhesus }} ({{ $stock->quantity }} kantong){{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    @endif
    
    @if($expiringSoon->isNotEmpty())
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h2a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Informasi:</strong> Stok darah berikut akan segera kadaluarsa:
                        @foreach($expiringSoon as $stock)
                            {{ $stock->blood_type . $stock->rhesus }} ({{ $stock->expiry_date->diffForHumans() }}){{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </p>
                </div>
            </div>
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
