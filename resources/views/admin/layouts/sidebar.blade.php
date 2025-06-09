<aside id="sidebar" class="hidden sm:flex flex-col w-64 h-full bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 transition-transform -translate-x-full sm:translate-x-0">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                <i class="fas fa-shapes text-primary-600 text-2xl mr-2"></i>
                <span class="text-xl font-semibold text-gray-800 dark:text-white">{{ config('app.name') }} <span class="text-primary-600">Admin</span></span>
            </a>
            <button type="button" class="p-1 rounded-lg sm:hidden hover:bg-gray-100 dark:hover:bg-gray-700" data-drawer-hide="sidebar" aria-controls="sidebar">
                <i class="fas fa-times text-gray-500 dark:text-gray-400 text-xl"></i>
                <span class="sr-only">Close sidebar</span>
            </button>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                @if(auth()->user()->profile_photo_path)
                    <img class="w-10 h-10 rounded-full" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-gray-700 flex items-center justify-center">
                        <span class="text-lg font-medium text-primary-600 dark:text-primary-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                @endif
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        {{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto">
            <div class="px-3 py-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2">Main Menu</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 text-base font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-primary-600 dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <!-- User Management Dropdown -->
                    <li>
                        <button type="button" id="userManagementBtn" class="flex items-center justify-between w-full p-3 text-base font-medium rounded-lg text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-users-cog w-5 h-5 mr-3"></i>
                                <span>User Management</span>
                            </div>
                            <svg id="dropdownIcon" class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul id="userManagementMenu" class="py-2 space-y-1 pl-11 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? '' : 'hidden' }}">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 text-sm rounded-lg {{ request()->routeIs('admin.users.*') ? 'text-primary-600 bg-primary-50 dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-users w-4 h-4 mr-2"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roles.index') }}" class="flex items-center p-2 text-sm rounded-lg {{ request()->routeIs('admin.roles.*') ? 'text-primary-600 bg-primary-50 dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <i class="fas fa-user-shield w-4 h-4 mr-2"></i>
                                    <span>Roles & Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="px-3 py-2 mt-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2">Settings</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.settings') }}" class="flex items-center p-3 text-base font-medium rounded-lg {{ request()->routeIs('admin.settings') ? 'bg-gray-100 text-primary-600 dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-cog w-5 h-5 mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.activity-log') }}" class="flex items-center p-3 text-base font-medium rounded-lg {{ request()->routeIs('admin.activity-log') ? 'bg-gray-100 text-primary-600 dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <i class="fas fa-history w-5 h-5 mr-3"></i>
                            <span>Activity Log</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Logout -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full p-3 text-base font-medium text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Mobile sidebar toggle button -->
<div class="fixed top-4 left-4 z-10 md:hidden">
    <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>
</div>
