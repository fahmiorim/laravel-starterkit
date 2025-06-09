@extends('admin.layouts.app')

@section('title', 'Edit Role: ' . $role->name)

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Role: {{ $role->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola permission untuk role ini</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" onsubmit="return submitForm(this)">
            @csrf
            @method('PUT')
            <input type="hidden" name="_method" value="PUT">
            <div class="p-6 space-y-6">
                <!-- Nama Role -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Role <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $role->name) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                           placeholder="Contoh: admin, writer, editor">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Daftar Permission -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pilih Permissions <span class="text-red-500">*</span>
                    </label>
                    @error('permissions')
                        <p class="mb-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="permission-{{ $permission->id }}" 
                                           name="permissions[]" 
                                           type="checkbox" 
                                           value="{{ $permission->id }}"
                                           {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}
                                           class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="permission-{{ $permission->id }}" class="font-medium text-gray-700 dark:text-gray-300">
                                        {{ $permission->name }}
                                    </label>
                                    @if($permission->description)
                                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                            {{ $permission->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i> Hapus Role
                    </button>
                </form>
                <div class="space-x-3">
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-save mr-2"></i> Perbarui Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function submitForm(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        }
        return true; // Allow form submission
    }
    
    // Debug form submission
    document.addEventListener('submit', function(e) {
        console.log('Form submitted', e.target.action);
        console.log('Form method:', e.target.method);
    }, true);
</script>
@endpush
