<nav class="w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 h-16 flex-shrink-0">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars w-6 h-6"></i>
                </button>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ml-3">
                    <div>
                        <button type="button" id="theme-toggle" class="flex items-center p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                            <i class="fas fa-moon dark:hidden w-5 h-5"></i>
                            <i class="fas fa-sun hidden dark:block w-5 h-5 text-yellow-300"></i>
                        </button>
                    </div>
                    <div class="relative ml-3">
                        <div>
                            <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white">
                                    @if(auth()->user()->profile_photo_path)
                                        <img class="w-full h-full rounded-full object-cover" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}">
                                    @else
                                        <span>{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                            </button>
                        </div>
                        <!-- Dropdown menu -->
                        <div class="z-50 absolute right-0 hidden w-48 mt-2 text-base list-none bg-white divide-y divide-gray-100 rounded-md shadow-lg dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                            <div class="px-4 py-3">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                                <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ auth()->user()->email }}</span>
                            </div>
                            <ul class="py-1" aria-labelledby="user-menu-button">
                                <li>
                                    <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        <i class="fas fa-user-circle w-4 h-4 mr-2"></i>
                                        <span>My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        <i class="fas fa-cog w-4 h-4 mr-2"></i>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-400 dark:hover:text-white">
                                            <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>
                                            <span>Sign out</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>