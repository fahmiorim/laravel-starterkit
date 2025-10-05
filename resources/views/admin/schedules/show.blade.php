<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $schedule->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <a href="{{ route('admin.schedules.index') }}" class="text-sm text-blue-500 hover:underline">&larr; Kembali ke semua jadwal</a>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">{{ __('Edit') }}</a>
                            @if($schedule->status == 'draft')
                                <form action="{{ route('admin.schedules.publish', $schedule) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mempublikasikan jadwal ini?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">{{ __('Publish') }}</button>
                                </form>
                            @endif
                            @if($schedule->status == 'published')
                                <form action="{{ route('admin.schedules.cancel', $schedule) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan jadwal ini?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">{{ __('Cancel') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div>
                            <h3 class="font-semibold text-lg">{{ __('Detail Jadwal') }}</h3>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-8">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Lokasi') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->lokasi }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Penanggung Jawab') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->penanggung_jawab ?? '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Tanggal Mulai') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->tanggal_mulai->format('l, d F Y, H:i') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Tanggal Selesai') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->tanggal_selesai ? $schedule->tanggal_selesai->format('l, d F Y, H:i') : '-' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @switch($schedule->status)
                                            @case('draft') bg-yellow-100 text-yellow-800 @break
                                            @case('published') bg-green-100 text-green-800 @break
                                            @case('canceled') bg-red-100 text-red-800 @break
                                        @endswitch
                                    ">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Deskripsi') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {!! nl2br(e($schedule->deskripsi)) !!}
                                </dd>
                            </div>
                        </dl>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
