<footer class="bg-white/10 backdrop-blur-md text-white border-t border-white/10 shadow-inner">
    <div class="w-full mx-auto max-w-screen-xl px-6 py-4 flex justify-end items-center">
        <span class="text-sm text-white/60">
            © {{ date('Y') }}
            <a href="{{ url('/') }}" class="hover:underline text-cyan-300">{{ config('app.name') }}</a>.
            All Rights Reserved.
        </span>
    </div>
</footer>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const html = document.documentElement;
            const darkIcon = document.getElementById("darkIcon");
            const lightIcon = document.getElementById("lightIcon");
            const toggleBtn = document.getElementById("darkModeToggle");

            function updateTheme() {
                if (html.classList.contains("dark")) {
                    darkIcon.classList.add("hidden");
                    lightIcon.classList.remove("hidden");
                    localStorage.setItem("theme", "dark");
                } else {
                    darkIcon.classList.remove("hidden");
                    lightIcon.classList.add("hidden");
                    localStorage.setItem("theme", "light");
                }
            }

            toggleBtn.addEventListener("click", () => {
                html.classList.toggle("dark");
                updateTheme();
            });

            // Initial theme
            if (localStorage.getItem("theme") === "dark") {
                html.classList.add("dark");
            }
            updateTheme();

            // User menu toggle
            document.getElementById('userDropdownToggle').addEventListener('click', () => {
                document.getElementById('userDropdownMenu').classList.toggle('hidden');
            });
        });
    </script>
@endpush
