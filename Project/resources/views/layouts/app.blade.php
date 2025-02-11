<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if(Auth::check())
            <meta name="user-id" content="{{ Auth::user()->id }}">
        @endif
        <meta name="pusher-app-key" content="{{ env('PUSHER_APP_KEY') }}">
        <meta name="pusher-app-cluster" content="{{ env('PUSHER_APP_CLUSTER') }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Styles -->
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="{{ url('css/app.css') }}">
        <link rel="stylesheet" href="{{ url('css/print.css') }}" media="print">

        <!-- Open Graph Tags -->
        <meta property="og:title" content="@yield('og:title', 'Default Title')">
        <meta property="og:description" content="@yield('og:description', 'Default Description')">
        <meta property="og:image" content="@yield('og:image', asset('storage/default-image.png'))">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}">

        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer>
        </script>
    </head>
    <body class="grid grid-rows-[auto_1fr_auto] min-h-screen">
        @include('partials.header')
        <main class="grid lg:grid-cols-[1fr_5fr] lg:flex-grow sm:grid-cols-[1fr]">
            <!-- Sidebar -->
            @include('partials.sidebar')
            <!-- Content -->
            <section id="content" class="p-4 bg-gray-100">
                @yield('content')
            </section>
        </main>
        @include('partials.footer')
    </body>
</html>