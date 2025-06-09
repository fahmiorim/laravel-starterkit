<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'StarterKit') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-full">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            @include('admin.layouts.sidebar')
            
            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Navbar -->
                @include('admin.layouts.navbar')
                
                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4">
                    @yield('content')
                </main>
                
                <!-- Footer -->
                @include('admin.layouts.footer')
            </div>
        </div>
    </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
