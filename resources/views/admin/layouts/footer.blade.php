<footer class="bg-white rounded-lg shadow dark:bg-gray-800">
    <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
            © {{ date('Y') }} <a href="{{ url('/') }}" class="hover:underline">{{ config('app.name') }}</a>. All Rights Reserved.
        </span>
        <div class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
            <li class="me-4 md:me-6">
                <a href="#" class="hover:underline">About</a>
            </li>
            <li class="me-4 md:me-6">
                <a href="#" class="hover:underline">Privacy Policy</a>
            </li>
            <li class="me-4 md:me-6">
                <a href="#" class="hover:underline">Licensing</a>
            </li>
            <li>
                <a href="#" class="hover:underline">Contact</a>
            </li>
        </div>
    </div>
</footer>