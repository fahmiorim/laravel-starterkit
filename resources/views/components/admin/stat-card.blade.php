@props([
    'title' => 'Statistik',
    'icon' => 'chart-bar',
    'color' => 'blue',
])

@php
    $colorClasses = [
        'blue' => 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-200',
        'red' => 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-200',
        'rose' => 'bg-rose-100 text-rose-600 dark:bg-rose-900 dark:text-rose-200',
        'amber' => 'bg-amber-100 text-amber-600 dark:bg-amber-900 dark:text-amber-200',
        'green' => 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-200',
        'purple' => 'bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-200',
    ];

    $badgeColor = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">
                {{ $slot }}
            </p>
        </div>
        <div class="p-3 rounded-full {{ $badgeColor }}">
            <i class="fas fa-{{ $icon }} text-lg"></i>
        </div>
    </div>
</div>