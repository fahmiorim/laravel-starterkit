@extends('admin.layouts.app')

@section('title', 'Profil Saya')

@section('content')

    <div class="p-4 max-w-7xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Profil Saya</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
            </div>
            
            @if(session('success'))
                <div class="mt-4 md:mt-0 p-3 bg-green-50 border border-green-200 text-green-700 rounded-md flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mx-auto">
            <!-- Profile Card -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-8 text-center">
                        <div class="flex justify-center">
                            <div class="relative group">
                                @if(auth()->user()->profile_photo_path)
                                    <img class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md" 
                                         src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" 
                                         alt="{{ auth()->user()->name }}">
                                @else
                                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-800 dark:to-primary-900 flex items-center justify-center border-4 border-white dark:border-gray-700 shadow-md">
                                        <span class="text-4xl font-bold text-primary-600 dark:text-primary-300">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                <button type="button" 
                                        class="absolute bottom-0 right-0 p-2 bg-white dark:bg-gray-700 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform group-hover:scale-110">
                                    <i class="fas fa-camera text-gray-600 dark:text-gray-300"></i>
                                    <span class="sr-only">Unggah foto</span>
                                </button>
                            </div>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ auth()->user()->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-envelope mr-2"></i>{{ auth()->user()->email }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-user-tag mr-2"></i>{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}
                        </p>
                        <div class="mt-4">
                            @if(auth()->user()->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    <i class="fas fa-check-circle w-3 h-3 mr-1.5"></i>
                                    Akun Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                    <i class="fas fa-times-circle w-3 h-3 mr-1.5"></i>
                                    Akun Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center justify-center">
                            <i class="fas fa-info-circle mr-2"></i> Tentang
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 text-center">
                            Kelola informasi profil dan pengaturan akun Anda.
                        </p>
                    </div>
                </div>
                
                <!-- Account Actions -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-cog text-primary-500 mr-2"></i>
                            Aksi Akun
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                                <i class="fas fa-sign-out-alt text-red-500"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Keluar</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Keluar dari akun Anda</p>
                            </div>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2 w-full">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user-edit text-primary-500 mr-2"></i>
                            Informasi Profil
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Perbarui informasi profil dan alamat email akun Anda.
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.profile.update') }}" class="p-6">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user text-primary-500 mr-1"></i> Nama Lengkap
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       required 
                                       autofocus 
                                       autocomplete="name"
                                       placeholder="Masukkan nama lengkap">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope text-primary-500 mr-1"></i> Alamat Email
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       required 
                                       autocomplete="email"
                                       placeholder="contoh@email.com">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone text-primary-500 mr-1"></i> Nomor Telepon
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       autocomplete="tel"
                                       placeholder="0812-3456-7890">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-2">
                            <button type="button" onclick="this.form.reset()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <i class="fas fa-undo-alt mr-2"></i> Reset
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                    <!-- Update Password -->
                    <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-700/30">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-lock text-primary-500 mr-2"></i>
                            Ubah Kata Sandi
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Pastikan akun Anda menggunakan kata sandi yang kuat dan unik.
                        </p>
                    </div>
                    <form method="POST" action="{{ route('password.update') }}" class="p-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Current Password -->
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-key text-primary-500 mr-1"></i> Kata Sandi Saat Ini
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input type="password" 
                                       name="current_password" 
                                       id="current_password" 
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm" 
                                       required 
                                       autocomplete="current-password"
                                       placeholder="Masukkan kata sandi saat ini">
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock text-primary-500 mr-1"></i> Kata Sandi Baru
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Masukkan kata sandi baru">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                        onclick="togglePasswordVisibility('password')">
                                    <i class="far fa-eye text-gray-400 hover:text-gray-500 cursor-pointer" id="togglePassword"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Minimal 8 karakter, kombinasi huruf dan angka
                            </p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-check-circle text-primary-500 mr-1"></i> Konfirmasi Kata Sandi Baru
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle text-gray-400"></i>
                                </div>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Konfirmasi kata sandi baru">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                        onclick="togglePasswordVisibility('password_confirmation')">
                                    <i class="far fa-eye text-gray-400 hover:text-gray-500 cursor-pointer" id="togglePasswordConfirmation"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-2">
                            <button type="button" onclick="this.form.reset()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <i class="fas fa-undo-alt mr-2"></i> Reset
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-key mr-2"></i> Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        window.togglePasswordVisibility = function(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(`toggle${fieldId.charAt(0).toUpperCase() + fieldId.slice(1)}`);
            
            if (field && icon) {
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
        };

        // Photo upload functionality
        const photoButton = document.querySelector('button[aria-label="Unggah foto"]');
        if (photoButton) {
            const photoInput = document.createElement('input');
            photoInput.type = 'file';
            photoInput.accept = 'image/*';
            photoInput.style.display = 'none';
            
            photoButton.addEventListener('click', function() {
                photoInput.click();
            });
            
            photoInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // Update the preview image
                        const preview = document.querySelector('.profile-photo-preview');
                        if (preview) {
                            preview.src = e.target.result;
                        }
                        
                    };
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        // Add loading state to forms
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        ${submitButton.textContent.trim()}
                    `;
                }
            });
        });
    });
</script>
@endpush
@endsection
