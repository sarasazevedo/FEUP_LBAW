@extends('layouts.app')

@section('content')
<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Form for the login -->
    <div class="w-full max-w-md p-8 space-y-6 bg-primary rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-white text-center">Login</h2>
        @include('partials.appeal_unblock')
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <fieldset class="email/username mt-4">
                <legend class="block text-sm font-medium text-white">Username</legend>
                <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Enter your email or username">
                @if ($errors->has('login'))
                    <span class="text-sm text-white">
                        {{ $errors->first('login') }}
                        @if ($errors->has('blocked'))
                            <a id="appealUnblockPopup" class="font-bold text-white hover:text-tertiary">Appeal Unblock</a>
                        @endif
                    </span>
                @endif
            </fieldset>

            <fieldset class="password mt-4">
                <legend class="block text-sm font-medium text-white">Password</legend>
                <input id="password" type="password" name="password" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Enter your password">
                @if ($errors->has('password'))
                    <span class="text-sm text-white">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </fieldset>

            <fieldset class="remember_me flex items-center justify-between mt-4">
                <label class="flex items-center text-white">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 text-secondary border-gray-300 rounded focus:ring-secondary">
                    <span class="ml-2 text-sm">Remember Me</span>
                </label>
                <!-- Forgot Password -->
                <div class="forgot_password">
                    <a href="{{ route('forgot.password') }}" class="text-sm text-white hover:underline">
                        Forgot Your Password?
                    </a>
                </div>
            </fieldset>

            <fieldset class="submit/register mt-6">
                <legend class="sr-only">Submit and Register</legend>
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-primary bg-tertiary border border-transparent rounded-md shadow-sm hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                    Login
                </button>
                <a class="block w-full px-4 py-2 mt-2 text-sm font-medium text-center text-primary bg-tertiary border border-primary rounded-md shadow-sm hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary" href="{{ route('register') }}">
                    Register
                </a>
            </fieldset>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusMessage = localStorage.getItem('status');
    if (statusMessage) {
        showStatusMessage(true, statusMessage);
        localStorage.removeItem('status');
    }
});
</script>
@endsection