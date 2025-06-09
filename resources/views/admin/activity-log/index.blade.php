@extends('admin.layouts.app')

@section('title', $title)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="p-4 sm:ml-64">
    <div class="p-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Log Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $activity->log_name === 'default' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $activity->log_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <div class="flex items-center">
                                    @php
                                        $icon = match(true) {
                                            str_contains(strtolower($activity->description), 'created') => 'plus-circle text-green-500',
                                            str_contains(strtolower($activity->description), 'updated') => 'pencil-alt text-blue-500',
                                            str_contains(strtolower($activity->description), 'deleted') => 'trash-alt text-red-500',
                                            default => 'info-circle text-gray-500'
                                        };
                                    @endphp
                                    <i class="fas {{ $icon }} mr-2"></i>
                                    {{ ucfirst($activity->description) }}
                                </div>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                @if($activity->causer)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                            <span class="text-primary-600 dark:text-primary-300 text-sm font-medium">
                                                {{ strtoupper(substr($activity->causer->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->causer->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->causer->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $activity->created_at->diffForHumans() }}
                                <div class="text-xs text-gray-400">
                                    {{ $activity->created_at->format('M d, Y h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" 
                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                                    @click="$dispatch('open-modal', 'activity-details-{{ $activity->id }}')">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No activity logs found.
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
</div>

@foreach($activities as $activity)
<!-- Activity Details Modal -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail === 'activity-details-{{ $activity->id }}') show = true"
     x-on:close-modal.window="show = false"
     class="fixed z-50 inset-0 overflow-y-auto" 
     aria-labelledby="modal-title" 
     x-ref="dialog" 
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="show = false"
             aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full sm:p-6">
            <div>
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                        Activity Details
                    </h3>
                    <div class="mt-4">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($activity->description) }}
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                    {{ $activity->created_at->format('F j, Y, g:i a') }}
                                </p>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Performed By
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $activity->causer ? $activity->causer->name : 'System' }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            IP Address
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $activity->properties->get('ip_address', 'N/A') }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            User Agent
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $activity->properties->get('user_agent', 'N/A') }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Log Name
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $activity->log_name }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            
                            @if($activity->properties->has('attributes') || $activity->properties->has('old'))
                            <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                                    Changes
                                </h3>
                                <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                                    <div class="border-t border-gray-200 dark:border-gray-700">
                                        <dl>
                                            @if($activity->properties->has('attributes'))
                                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    New Values
                                                </dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                                    <div class="space-y-2">
                                                        @foreach($activity->properties->get('attributes') as $key => $value)
                                                            <div>
                                                                <span class="font-medium">{{ $key }}:</span>
                                                                @if(is_array($value) || is_object($value))
                                                                    <pre class="mt-1 bg-gray-100 dark:bg-gray-800 p-2 rounded text-xs overflow-x-auto">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                                @elseif(is_bool($value))
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                        {{ $value ? 'true' : 'false' }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-gray-900 dark:text-white">{{ $value }}</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </dd>
                                            </div>
                                            @endif
                                            
                                            @if($activity->properties->has('old'))
                                            <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-t border-gray-200 dark:border-gray-700">
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    Old Values
                                                </dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">
                                                    <div class="space-y-2">
                                                        @foreach($activity->properties->get('old') as $key => $value)
                                                            <div>
                                                                <span class="font-medium">{{ $key }}:</span>
                                                                @if(is_array($value) || is_object($value))
                                                                    <pre class="mt-1 bg-gray-100 dark:bg-gray-900 p-2 rounded text-xs overflow-x-auto">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                                @elseif(is_bool($value))
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $value ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                        {{ $value ? 'true' : 'false' }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-gray-900 dark:text-white">{{ $value }}</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </dd>
                                            </div>
                                            @endif
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        @click="show = false"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Alpine.js is already loaded by the layout
    });
</script>
@endpush
