@extends('admin.layouts.app')

@section('title', 'Edit Pengguna: ' . $user->name)

@section('content')
<div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Edit Pengguna</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Perbarui informasi pengguna di bawah ini</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 dark:bg-red-900/20 dark:border-red-600 dark:text-red-200 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <h3 class="font-medium">Terdapat kesalahan!</h3>
            </div>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Nama -->
        <div class="col-span-1">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                       class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                       placeholder="Nama lengkap" required>
            </div>
        </div>

        <!-- Email -->
        <div class="col-span-1">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                       class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                       placeholder="email@contoh.com" required>
            </div>
        </div>

            <!-- Password -->
            <div class="col-span-1">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password"
                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                           placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ingin mengubah password</p>
            </div>

            <!-- Konfirmasi Password -->
            <div class="col-span-1">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                           placeholder="Ketik ulang password">
                </div>
            </div>

            <!-- Nomor Telepon -->
            <div class="col-span-1">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white h-10"
                           placeholder="Contoh: 081234567890">
                </div>
            </div>

            <!-- Status -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Akun</label>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <input id="status-active" name="is_active" type="radio" value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-blue-600"
                               {{ $user->is_active ? 'checked' : '' }}>
                        <label for="status-active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="status-inactive" name="is_active" type="radio" value="0"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-blue-600"
                               {{ !$user->is_active ? 'checked' : '' }}>
                        <label for="status-inactive" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <i class="fas fa-times-circle mr-1"></i> Nonaktif
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Roles -->
            <div class="col-span-full">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Hak Akses (Role) <span class="text-red-500">*</span></label>
                @error('roles')
                    <p class="mb-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="role-{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                                       {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="role-{{ $role->id }}" class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ $role->name }}
                                </label>
                                @if($role->description)
                                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                        {{ $role->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection