@extends('admin.layouts.app')

@section('title', $title)

@section('content')
<div class="p-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola pengaturan aplikasi dan konfigurasi sistem</p>
        </div>
        
        @if(session('success'))
            <div class="mt-4 md:mt-0 p-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200 rounded-md flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan Aplikasi</h3>
        </div>
        
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm" class="divide-y divide-gray-200 dark:divide-gray-700">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" id="currentGroupInput" value="{{ $currentGroup }}">
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <button type="button" 
                            data-group="general" 
                            class="settings-tab {{ $currentGroup === 'general' ? 'border-primary-500 text-primary-600 dark:border-primary-400 dark:text-primary-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm">
                        <i class="fas fa-cog mr-2"></i>
                        Umum
                    </button>
                    <button type="button" 
                            data-group="company" 
                            class="settings-tab {{ $currentGroup === 'company' ? 'border-primary-500 text-primary-600 dark:border-primary-400 dark:text-primary-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm">
                        <i class="fas fa-building mr-2"></i>
                        Perusahaan
                    </button>
                    <button type="button" 
                            data-group="social" 
                            class="settings-tab {{ $currentGroup === 'social' ? 'border-primary-500 text-primary-600 dark:border-primary-400 dark:text-primary-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm">
                        <i class="fas fa-share-alt mr-2"></i>
                        Media Sosial
                    </button>
                </nav>
            </div>
            
            <!-- General Settings -->
            <div class="settings-group {{ $currentGroup === 'general' ? 'block' : 'hidden' }}" id="general-settings">
                <div class="p-6">
                    <input type="hidden" name="group" value="general">
                    
                    <div class="mb-6">
                        <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tag text-primary-500 mr-1"></i> Nama Aplikasi
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <input type="text" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm" 
                                id="app_name" 
                                name="app_name" 
                                value="{{ old('app_name', $settings['general']['app_name'] ?? '') }}" 
                                required
                                placeholder="Nama Aplikasi">
                        </div>
                        @error('app_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="app_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-link text-primary-500 mr-1"></i> URL Aplikasi
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-link text-gray-400"></i>
                            </div>
                            <input type="url" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                id="app_url" 
                                name="app_url" 
                                value="{{ old('app_url', $settings['general']['app_url'] ?? '') }}" 
                                required
                                placeholder="https://example.com">
                        </div>
                        @error('app_url')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-globe text-primary-500 mr-1"></i> Zona Waktu
                        </label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                            <select id="timezone" 
                                    name="timezone" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    required>
                                @foreach(timezone_identifiers_list() as $timezone)
                                    <option value="{{ $timezone }}" 
                                        {{ old('timezone', $settings['general']['timezone'] ?? '') === $timezone ? 'selected' : '' }}>
                                        {{ $timezone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('timezone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="locale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-language text-primary-500 mr-1"></i> Bahasa Default
                        </label>
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-language text-gray-400"></i>
                            </div>
                            <select id="locale" 
                                    name="locale" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    required>
                                <option value="en" {{ old('locale', $settings['general']['locale'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="id" {{ old('locale', $settings['general']['locale'] ?? '') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                            </select>
                        </div>
                        @error('locale')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Company Settings -->
            <div class="settings-group {{ $currentGroup === 'company' ? 'block' : 'hidden' }}" id="company-settings">
                <div class="p-6">
                    <input type="hidden" name="group" value="company">
                    
                    <div class="mb-6">
                        <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-building text-primary-500 mr-1"></i> Nama Perusahaan
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                            <input type="text" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                id="company_name" 
                                name="company_name" 
                                value="{{ old('company_name', $settings['company']['company_name'] ?? '') }}" 
                                required
                                placeholder="Nama Perusahaan">
                        </div>
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="company_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt text-primary-500 mr-1"></i> Alamat Perusahaan
                        </label>
                        <div class="mt-1">
                            <textarea id="company_address" 
                                    name="company_address" 
                                    rows="3"
                                    class="block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="Alamat lengkap perusahaan">{{ old('company_address', $settings['company']['company_address'] ?? '') }}</textarea>
                        </div>
                        @error('company_address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-6">
                            <label for="company_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone text-primary-500 mr-1"></i> Nomor Telepon
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="text" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    id="company_phone" 
                                    name="company_phone" 
                                    value="{{ old('company_phone', $settings['company']['company_phone'] ?? '') }}"
                                    placeholder="Contoh: +62 123 4567 890">
                            </div>
                            @error('company_phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="company_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope text-primary-500 mr-1"></i> Email Perusahaan
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    id="company_email" 
                                    name="company_email" 
                                    value="{{ old('company_email', $settings['company']['company_email'] ?? '') }}"
                                    placeholder="contoh@perusahaan.com">
                            </div>
                            @error('company_email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-image text-primary-500 mr-1"></i> Logo Perusahaan
                        </label>
                        <div class="mt-1 flex items-center">
                            <div class="relative group">
                                @if(isset($settings['company']['company_logo']) && $settings['company']['company_logo'])
                                    <img id="company-logo-preview" 
                                        src="{{ asset('storage/' . $settings['company']['company_logo']) }}" 
                                        alt="Logo Perusahaan" 
                                        class="h-24 w-24 rounded-md object-cover border-2 border-gray-300 dark:border-gray-600">
                                    <button type="button" 
                                            onclick="removeImage('{{ $settings['company']['company_logo'] }}')"
                                            class="absolute -top-2 -right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                @else
                                    <div class="h-24 w-24 rounded-md border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <label for="company_logo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-upload mr-2"></i> Unggah Logo
                                    <input id="company_logo" name="company_logo" type="file" class="sr-only" accept="image/*" onchange="previewImage(this, 'company-logo-preview')">
                                </label>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Format: JPG, PNG (maks. 2MB)
                                </p>
                            </div>
                        </div>
                        @error('company_logo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>


            <!-- Social Media Settings -->
            <div class="settings-group {{ $currentGroup === 'social' ? 'block' : 'hidden' }}" id="social-settings">
                <div class="p-6">
                    <input type="hidden" name="group" value="social">
                    
                    <div class="mb-6">
                        <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-facebook text-gray-400"></i>
                            </div>
                            <input type="url" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    id="facebook_url" 
                                    name="facebook_url" 
                                    value="{{ old('facebook_url', $settings['social']['facebook_url'] ?? '') }}"
                                    placeholder="https://facebook.com/namapengguna">
                        </div>
                        @error('facebook_url')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="twitter_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fab fa-twitter text-blue-400 mr-1"></i> Twitter
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-twitter text-gray-400"></i>
                            </div>
                            <input type="url" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    id="twitter_url" 
                                    name="twitter_url" 
                                    value="{{ old('twitter_url', $settings['social']['twitter_url'] ?? '') }}"
                                    placeholder="https://twitter.com/namapengguna">
                        </div>
                        @error('twitter_url')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fab fa-instagram text-pink-600 mr-1"></i> Instagram
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-instagram text-gray-400"></i>
                            </div>
                            <input type="url" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    id="instagram_url" 
                                    name="instagram_url" 
                                    value="{{ old('instagram_url', $settings['social']['instagram_url'] ?? '') }}"
                                    placeholder="https://instagram.com/namapengguna">
                        </div>
                        @error('instagram_url')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="linkedin_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fab fa-linkedin text-blue-700 mr-1"></i> LinkedIn
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-linkedin text-gray-400"></i>
                            </div>
                            <input type="url" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    id="linkedin_url" 
                                    name="linkedin_url" 
                                    value="{{ old('linkedin_url', $settings['social']['linkedin_url'] ?? '') }}"
                                    placeholder="https://linkedin.com/company/namaperusahaan">
                        </div>
                </div>
            </div>
            
            <!-- Save Button -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 text-right">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Handle tab switching
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const group = this.getAttribute('data-group');
            document.getElementById('currentGroupInput').value = group;
            
            // Hide all groups
            document.querySelectorAll('.settings-group').forEach(div => {
                div.classList.add('hidden');
            });
            
            // Show selected group
            document.getElementById(group + '-settings').classList.remove('hidden');
            
            // Update active tab
            document.querySelectorAll('.settings-tab').forEach(t => {
                t.classList.remove('border-primary-500', 'text-primary-600', 'dark:border-primary-400', 'dark:text-primary-300');
                t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            });
            
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            this.classList.add('border-primary-500', 'text-primary-600', 'dark:border-primary-400', 'dark:text-primary-300');
            
            // Update URL without reloading
            const url = new URL(window.location);
            url.searchParams.set('group', group);
            window.history.pushState({}, '', url);
        });
    });
    
    // Handle form submission with AJAX
    document.getElementById('settingsForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const saveBtn = this.querySelector('button[type="submit"]');
        const originalHtml = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'mt-4 md:mt-0 p-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200 rounded-md flex items-center';
                successDiv.innerHTML = `
                    <i class="fas fa-check-circle mr-2"></i>
                    ${data.message || 'Pengaturan berhasil disimpan'}
                `;
                
                // Remove any existing success messages
                const existingSuccess = document.querySelector('.bg-green-50');
                if (existingSuccess) {
                    existingSuccess.remove();
                }
                
                // Insert success message after the heading
                const heading = document.querySelector('h1')?.parentNode;
                if (heading) {
                    heading.parentNode.insertBefore(successDiv, heading.nextSibling);
                }
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Remove success message after 5 seconds
                setTimeout(() => {
                    successDiv.remove();
                }, 5000);
                
                // Update any file previews if needed
                if (data.file_url) {
                    const preview = document.getElementById('logo-preview');
                    if (preview) {
                        preview.src = data.file_url + '?t=' + new Date().getTime();
                        preview.classList.remove('hidden');
                        
                        const removeBtn = document.getElementById('remove-logo-btn');
                        if (removeBtn) {
                            removeBtn.classList.remove('hidden');
                        }
                    }
                }
            } else {
                // Show error message
                alert('Terjadi kesalahan: ' + (data.message || 'Tidak dapat menyimpan pengaturan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan pengaturan');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalHtml;
        });
    });
    
    // Handle file input change
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('logo-preview');
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    
                    const removeBtn = document.getElementById('remove-logo-btn');
                    if (removeBtn) {
                        removeBtn.classList.remove('hidden');
                    }
                }
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Remove image
    function removeImage(imagePath) {
        if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
            fetch('{{ route("admin.settings.remove-image") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    image_path: imagePath,
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Reload the page to reflect changes
                    window.location.reload();
                } else {
                    alert('Gagal menghapus gambar. ' + (data.message || 'Silakan coba lagi.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    }
    
    // Set active tab based on URL parameter on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const group = urlParams.get('group') || 'general';
        
        const tab = document.querySelector(`.settings-tab[data-group="${group}"]`);
        if (tab) {
            tab.click();
        } else {
            // Default to first tab if invalid group
            const firstTab = document.querySelector('.settings-tab');
            if (firstTab) firstTab.click();
        }
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    
    // Handle form submission with loading state
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            }
        });
    });
</script>
@endpush