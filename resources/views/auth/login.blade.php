@extends('auth.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 auth-bg">
    <div class="w-full max-w-md p-8 rounded-2xl shadow-2xl form-container transform transition-all duration-500">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
            <p class="mt-2 text-gray-600">Please sign in to your account</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were some errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                        class="appearance-none block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm input-field"
                        placeholder="you@example.com" value="{{ old('email') }}">
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Forgot password?
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                        class="appearance-none block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm input-field"
                        placeholder="••••••••">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="togglePassword">
                        <i class="far fa-eye text-gray-400 hover:text-gray-500 cursor-pointer" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                    Remember me
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 btn-primary">
                    <span id="buttonText">Sign in</span>
                    <span id="buttonSpinner" class="hidden ml-2">
                        <i class="fas fa-circle-notch fa-spin"></i>
                    </span>
                </button>
            </div>
        </form>

        <!-- Social Login -->
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <div>
                    <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i class="fab fa-google text-red-500"></i>
                        <span class="ml-2">Google</span>
                    </a>
                </div>
                <div>
                    <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i class="fab fa-github text-gray-800"></i>
                        <span class="ml-2">GitHub</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sign Up Link -->
        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                Sign up
            </a>
        </p>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');
            
            if (togglePassword && password && eyeIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            }

            // Form submission
            const form = document.querySelector('form');
            const submitButton = form ? form.querySelector('button[type="submit"]') : null;
            const buttonText = document.querySelector('#buttonText');
            const buttonSpinner = document.querySelector('#buttonSpinner');
            
            if (form && submitButton && buttonText && buttonSpinner) {
                form.addEventListener('submit', function() {
                    submitButton.disabled = true;
                    buttonText.textContent = 'Signing in...';
                    buttonSpinner.classList.remove('hidden');
                });
            }

            // Load saved email if remember me was checked
            const rememberMe = document.getElementById('remember_me');
            const emailInput = document.getElementById('email');
            
            if (localStorage.getItem('rememberMe') === 'true' && localStorage.getItem('savedEmail')) {
                rememberMe.checked = true;
                emailInput.value = localStorage.getItem('savedEmail');
            }
            
            // Save email to localStorage when remember me is checked
            if (rememberMe && emailInput) {
                rememberMe.addEventListener('change', function() {
                    if (this.checked && emailInput.value) {
                        localStorage.setItem('rememberMe', 'true');
                        localStorage.setItem('savedEmail', emailInput.value);
                    } else {
                        localStorage.removeItem('rememberMe');
                        localStorage.removeItem('savedEmail');
                    }
                });
            }
            
            // Animate form on load
            const authContainer = document.querySelector('.auth-container');
            if (authContainer) {
                setTimeout(() => {
                    authContainer.classList.add('opacity-100', 'translate-y-0');
                }, 100);
            }
        });
    </script>
@endpush