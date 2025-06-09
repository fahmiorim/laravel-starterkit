@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Pengguna Baru</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lengkapi form di bawah untuk menambahkan pengguna baru</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mx-6 mt-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan input</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Lengkap -->
            <div class="col-span-1">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                           placeholder="Nama lengkap" required>
                </div>
            </div>

            <!-- Email -->
            <div class="col-span-1">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                           placeholder="email@contoh.com" required>
                </div>
            </div>

            <!-- Password -->
            <div class="col-span-1">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password <span class="text-red-500">*</span></label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password"
                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                           placeholder="Minimal 8 karakter" required>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="col-span-1">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                           placeholder="Ketik ulang password" required>
                </div>
            </div>

            <!-- Nomor Telepon -->
            <div class="col-span-1">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                           placeholder="Contoh: 081234567890">
                </div>
            </div>

            <!-- Status -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Akun</label>
                <div class="mt-1">
                    <label class="inline-flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Nonaktifkan untuk menonaktifkan akun ini</p>
                </div>
            </div>

            <!-- Roles -->
            <div class="col-span-full">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hak Akses (Role) <span class="text-red-500">*</span></label>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($roles as $role)
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="role-{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}"
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                           {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="role-{{ $role->id }}" class="font-medium text-gray-700 dark:text-gray-300">
                                        {{ $role->name }}
                                    </label>
                                    @if($role->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $role->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
            <button type="reset" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                <i class="fas fa-undo-alt mr-2"></i> Reset
            </button>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-save mr-2"></i> Simpan Pengguna
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Format nomor telepon
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 12) {
            value = value.substring(0, 13);
        }
        e.target.value = value;
    });

    // Toggle password visibility
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.querySelector(`[data-password-toggle="${fieldId}"] i`);
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
@endsection