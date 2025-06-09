@extends('admin.layouts.app')

@section('title', $title)

@section('content')
<div class="p-4 sm:ml-64">
    <div class="p-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="space-y-6">
                <!-- Application Information -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Application Information</h2>
                    <dl class="sm:divide-y sm:divide-gray-200 dark:divide-gray-700">
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Application Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $settings['app_name'] }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Environment</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $settings['app_env'] }}
                                </span>
                            </dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Debug Mode</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $settings['app_debug'] === 'true' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                    {{ $settings['app_debug'] }}
                                </span>
                            </dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Application URL</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">
                                <a href="{{ $settings['app_url'] }}" target="_blank" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                                    {{ $settings['app_url'] }}
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- System Information -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">System Information</h2>
                    <dl class="sm:divide-y sm:divide-gray-200 dark:divide-gray-700">
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Laravel Version</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ app()->version() }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PHP Version</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ phpversion() }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Server Environment -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Server Environment</h2>
                    <dl class="sm:divide-y sm:divide-gray-200 dark:divide-gray-700">
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Server Software</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Server Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $_SERVER['SERVER_NAME'] ?? 'N/A' }}</dd>
                        </div>
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Server Protocol</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2">{{ $_SERVER['SERVER_PROTOCOL'] ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
