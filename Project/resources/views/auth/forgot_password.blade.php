@extends('layouts.app')

@section('content')

<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Form for the forgot password -->
    <div class="w-full max-w-md p-8 space-y-6 bg-primary rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-white text-center">Forgot Password</h2>
        <form method="POST" action="/send">
            @csrf
            <fieldset class="email mt-4">
                <legend class="block text-sm font-medium text-white">Email <span class="text-red-500">*</span></legend>
                <input id="email" type="email" name="email" placeholder="Email" required
                    class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm">
                @if ($errors->has('email'))
                    <span class="text-sm text-white">
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </fieldset>
            <fieldset class="submit mt-6">
                <legend class="sr-only">Submit</legend>
                <button type="submit"
                    class="w-full px-4 py-2 text-sm font-medium text-primary bg-tertiary border border-transparent rounded-md shadow-sm hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
                    Send Password Recovery Email
                </button>
            </fieldset>
            @if (session('status'))
                <p class="status text-sm text-white mt-4">
                    {{ session('status') }}
                </p>
            @endif
        </form>
    </div>
</div>
@endsection