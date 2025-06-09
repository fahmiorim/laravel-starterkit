@extends('admin.layouts.app')

@section('title', $title)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .activity-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin-right: 12px;
    }
    .activity-created { background-color: #D1FAE5; color: #10B981; }
    .activity-updated { background-color: #DBEAFE; color: #3B82F6; }
    .activity-deleted { background-color: #FEE2E2; color: #EF4444; }
    .activity-default { background-color: #E5E7EB; color: #6B7280; }
    .badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .badge-created { @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200; }
    .badge-updated { @apply bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200; }
    .badge-deleted { @apply bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200; }
</style>
@endpush

@section('content')
<div class="p-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Riwayat aktivitas sistem</p>
        </div>
        
        @if(session('success'))
            <div class="mt-4 md:mt-0 p-3 bg-green-50 border border-green-200 text-green-700 rounded-md flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Aktivitas</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aktivitas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pengguna</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @php
                                    $iconClass = 'activity-default';
                                    $badgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                    $activityType = 'default';
                                    
                                    if (str_contains(strtolower($activity->description), 'created')) {
                                        $iconClass = 'activity-created';
                                        $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                        $activityType = 'Dibuat';
                                    } elseif (str_contains(strtolower($activity->description), 'updated')) {
                                        $iconClass = 'activity-updated';
                                        $badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                        $activityType = 'Diperbarui';
                                    } elseif (str_contains(strtolower($activity->description), 'deleted')) {
                                        $iconClass = 'activity-deleted';
                                        $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                        $activityType = 'Dihapus';
                                    }
                                    
                                    $modelName = class_basename($activity->subject_type);
                                @endphp
                                <div class="activity-icon {{ $iconClass }}">
                                    <i class="fas fa-{{ $icon ?? 'info-circle' }}"></i>
                                </div>
                                <span class="badge {{ $badgeClass }}">
                                    {{ $activityType }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ ucfirst($activity->description) }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $modelName }} #{{ $activity->subject_id }}
                            </div>
                        </td>
                                @if($activity->properties->has('attributes'))
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        @foreach($activity->properties->get('attributes') as $key => $value)
                                            @if(is_string($value) || is_numeric($value))
                                                <div><span class="font-medium">{{ $key }}:</span> {{ $value }}</div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($activity->causer)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                            <span class="text-primary-600 dark:text-primary-300 text-sm font-medium">
                                                {{ strtoupper(substr($activity->causer->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $activity->causer->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $activity->causer->email }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $activity->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-2"></i>
                                <p class="text-sm">Tidak ada aktivitas yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($activities->hasPages())
        <div class="bg-white dark:bg-gray-800 px-6 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi tooltip jika diperlukan
    document.addEventListener('DOMContentLoaded', function() {
        // Kode inisialisasi tooltip bisa ditambahkan di sini
    });
</script>
@endpush
