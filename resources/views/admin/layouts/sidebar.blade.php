<style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .bubble {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
    }
</style>

<aside
    class="hidden md:flex md:flex-col w-64 bg-gradient-to-b from-red-600 via-red-500 to-red-600 text-white h-full relative overflow-hidden dark:from-gray-800 dark:via-gray-900 dark:to-gray-800">
    <!-- Bubbles Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="bubble w-32 h-32 -top-16 -right-16" style="animation-delay: 0s;"></div>
        <div class="bubble w-24 h-24 bottom-20 -left-10" style="animation-delay: 1s;"></div>
        <div class="bubble w-16 h-16 top-1/3 right-10" style="animation-delay: 2s;"></div>
        <div class="bubble w-20 h-20 bottom-1/4 left-1/4" style="animation-delay: 3s;"></div>
    </div>

    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-red-400/30 px-4 relative z-10">
        <img src="{{ asset('assets/images/logo_white.png') }}" class="w-36 drop-shadow-lg" alt="Logo">
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto relative z-10">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <!-- Menu Utama -->
        <div class="px-3 mt-4 mb-2 text-xs font-semibold tracking-wider text-white/50 uppercase">
            <span>Menu Utama</span>
        </div>

        <!-- Pendonor -->
        @can('donor_management_access')
        <a href="{{ route('admin.donors.index') }}"
            class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.donors.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Data Pendonor
            </div>
        </a>
        <a href="{{ route('admin.donor-histories.index') }}"
            class="flex items-center justify-between px-3 py-2.5 mt-2 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.donor-histories.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 2c-2.667 0-8 1.333-8 4v3a1 1 0 001 1h14a1 1 0 001-1v-3c0-2.667-5.333-4-8-4z" />
                </svg>
                Riwayat Donor
            </div>
        </a>
        @endcan


        <!-- Kegiatan Donor -->
        @can('view_schedules')
        <a href="{{ route('admin.schedules.index') }}"
            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.schedules.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Jadwal Donor
        </a>
        @endcan

        <!-- Data Pendonor -->
        @can('view_donors')
        <a href="{{ route('admin.donors.index') }}"
            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.donors.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Data Pendonor
        </a>
        @endcan

        <!-- Stok Darah -->
        @can('view_blood_stocks')
        <a href="{{ route('admin.blood-stocks.index') }}"
            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.blood-stocks.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 5.5l4 4a4 4 0 11-8 0l4-4zM6 16h12a2 2 0 012 2v1a2 2 0 01-2 2H6a2 2 0 01-2-2v-1a2 2 0 012-2z" />
            </svg>
            Stok Darah
        </a>
        @endcan

        <!-- Permintaan Darah -->
        @can('view_blood_requests')
        <a href="{{ route('admin.blood-requests.index') }}"
            class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.blood-requests.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 010 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-3a6 6 0 00-6-6H9a6 6 0 00-6 6v3h12z" />
            </svg>
            Permintaan Darah
        </a>
        @endcan
        
        <!-- Manajemen Kartu Donor -->
        @can('view_donor_cards')
        <div x-data="{ open: {{ request()->routeIs('admin.donor-cards.*') ? 'true' : 'false' }} }}" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 text-white/90 hover:bg-white/10">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Kartu Donor</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200 transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" class="ml-8 space-y-1">
                <a href="{{ route('admin.donor-cards.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.donor-cards.index') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                    Daftar Kartu
                </a>
                <a href="{{ route('admin.donor-cards.create') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.donor-cards.create') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                    Buat Kartu Baru
                </a>
                <a href="{{ route('donor-cards.verify-form') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('donor-cards.verify-form') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                    Verifikasi Kartu
                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-yellow-500/20 text-yellow-400 rounded-full">Baru</span>
                </a>
            </div>
        </div>
        @endcan
        
        <!-- Sistem -->
        <div class="pt-4 mt-4 border-t border-white/10">
            <div class="px-3 mt-2 mb-2 text-xs font-semibold tracking-wider text-white/50 uppercase">
                <span>Sistem</span>
            </div>
            <!-- Users -->
            @can('user_management_access')
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.users.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197" />
                    </svg>
                    Users
                </a>
            @endcan
            <!-- Roles -->
            @can('role_management_access')
                <a href="{{ route('admin.roles.index') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.roles.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4" />
                    </svg>
                    Roles
                </a>
            @endcan
            <!-- Permissions -->
            @can('permission_management_access')
                <a href="{{ route('admin.permissions.index') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.permissions.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Permissions
                </a>
            @endcan
            @can('view_reports')
                <a href="{{ route('admin.reports.index') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.reports.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18M5 9l6-6 6 6" />
                    </svg>
                    Laporan
                </a>
            @endcan

            <!-- Settings -->
            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.settings.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
            </a>
            <!-- Activity Log -->
            <a href="{{ route('admin.activity-logs.index') }}"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-300 transform hover:scale-105 {{ request()->routeIs('admin.activity-logs.*') ? 'bg-white/20 backdrop-blur-sm shadow-lg' : 'text-white/90 hover:bg-white/10' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                Activity Log
            </a>
        </div>
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-white/10 relative z-10">
        <div class="flex items-center">
            <img class="w-8 h-8 rounded-full border-2 border-white shadow-sm"
                src="{{ asset('assets/images/avatar.png') }}" alt="User">
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-white/70">Administrator</p>
            </div>
        </div>
    </div>
</aside>








