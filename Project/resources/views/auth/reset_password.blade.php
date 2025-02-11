@extends('layouts.app')

@section('content')

<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Form for the password reset -->
    <div class="w-full max-w-md p-8 space-y-6 bg-primary rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-white text-center">Reset Password <span class="text-red-500">*</span></h2>
        <form id="reset-password-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <fieldset class="password mt-4">
                <legend class="block text-sm font-medium text-white">New Password <span class="text-red-500">*</span></legend>
                <input id="password" type="password" name="password" required minlength="3"
                    class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm"
                    placeholder="Enter your new password">
                @if ($errors->has('password'))
                    <span class="text-sm text-white">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </fieldset>

            <fieldset class="submit mt-6">
                <legend class="sr-only">Submit</legend>
                <button type="submit"
                    class="w-full px-4 py-2 text-sm font-medium text-primary bg-tertiary border border-transparent rounded-md shadow-sm hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                    Reset Password
                </button>
            </fieldset>

            @if ($errors->any())
                <fieldset class="errors mt-4">
                    <legend class="sr-only">Errors</legend>
                    <ul class="text-sm text-white">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </fieldset>
            @endif
        </form>
    </div>
</div>
@endsection