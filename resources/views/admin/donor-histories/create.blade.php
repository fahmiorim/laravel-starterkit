@extends('admin.layouts.app')

@section('title', 'Tambah Riwayat Donor')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Tambah Riwayat Donor</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Catat kehadiran atau hasil donor terbaru.</p>
        </div>
        <a href="{{ route('admin.donor-histories.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-md">
            <p class="font-semibold mb-2">Periksa kembali input Anda:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-6">
        <form action="{{ route('admin.donor-histories.store') }}" method="POST">
            @include('admin.donor-histories._form')
        </form>
    </div>
</div>
@endsection

