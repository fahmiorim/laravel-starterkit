@extends('auth.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 auth-bg">
    <div class="w-full max-w-md p-8 rounded-2xl shadow-2xl form-container transform transition-all duration-500">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Forgot Password</h1>
            <p class="mt-2 text-gray-600">Enter your email to reset your password</p>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There was a problem</h3>
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

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            
            <div class="space-y-6">
                <p class="text-sm text-gray-600 leading-relaxed">
                    Enter the email address associated with your account and we'll send you a link to reset your password.
                </p>
                
                <!-- Email Input -->
                <div class="space-y-1">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm input-field"
                            placeholder="you@example.com" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="p-4">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 btn-primary">
                        <span id="buttonText">Send Password Reset Link</span>
                        <span id="buttonSpinner" class="hidden ml-2">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Back to login
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission
        const form = document.querySelector('form');
        const submitButton = form ? form.querySelector('button[type="submit"]') : null;
        const buttonText = document.querySelector('#buttonText');
        const buttonSpinner = document.querySelector('#buttonSpinner');
        
        if (form && submitButton && buttonText && buttonSpinner) {
            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                buttonText.textContent = 'Sending Link...';
                buttonSpinner.classList.remove('hidden');
            });
        }
        
        // Animate form on load
        const formContainer = document.querySelector('.form-container');
        if (formContainer) {
            setTimeout(() => {
                formContainer.classList.add('opacity-100', 'translate-y-0');
            }, 100);
        }
    });
</script>
@endpush
