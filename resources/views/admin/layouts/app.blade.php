<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIMPATI</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'defer')

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script>
        const theme = (() => {
            if (typeof localStorage !== 'undefined' && localStorage.getItem('theme')) {
                return localStorage.getItem('theme');
            }
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return 'dark';
            }
            return 'light';
        })();

        if (theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
        window.localStorage.setItem('theme', theme);
    </script>
</head>

<body class="bg-gray-100 font-sans text-gray-800 h-full dark:bg-gray-800 dark:text-gray-200">
    <div class="flex min-h-screen">
        <div class="hidden lg:flex lg:flex-col">
            @include('admin.layouts.sidebar')
        </div>

        <div class="flex flex-col flex-1">
            @include('admin.layouts.navbar')

            <main class="flex-1 overflow-y-auto dark:bg-gray-900">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
