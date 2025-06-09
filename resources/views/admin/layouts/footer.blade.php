<footer class="bg-white rounded-lg shadow dark:bg-gray-800">
    <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-end">
        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
            © {{ date('Y') }} <a href="{{ url('/') }}" class="hover:underline">{{ config('app.name') }}</a>. All Rights Reserved.
        </span>
    </div>
</footer>