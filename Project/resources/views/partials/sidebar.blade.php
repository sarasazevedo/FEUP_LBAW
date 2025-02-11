<aside id="sidebar" class="top-0 left-0 h-full bg-tertiary lg:py-4 sm:py-0 flex flex-col no-print">
    <nav class="flex flex-col space-y-4 w-full">
        <a href="/" class="flex items-center w-4/5 bg-primary text-tertiary font-bold px-4 py-3 rounded-r-full">
            <i class="fa fa-home mr-2"></i> Home
        </a>
        @guest
        <a href="{{ route('login') }}" class="flex items-center w-4/5 bg-primary text-tertiary font-bold px-4 py-3 rounded-r-full">
            <i class="fa fa-user mr-2"></i> Login
        </a>
        @else
        <a href="{{ route('profile.show', Auth::user()->id) }}" class="flex items-center w-4/5 bg-primary text-tertiary font-bold px-4 py-3 rounded-r-full">
            <i class="fa fa-user mr-2"></i> Profile
        </a>
        @if (Auth::user()->castToSubclass() instanceof \App\Models\Client)
        <a href="{{ route('requests.show') }}" class="flex items-center w-4/5 bg-primary text-tertiary font-bold px-4 py-3 rounded-r-full">
            <i class="fa fa-user-check mr-2"></i> Follow Requests
        </a>
        <a href="{{ route('groups.index') }}" class="flex items-center w-4/5 bg-primary text-tertiary font-bold px-4 py-3 rounded-r-full">
            <i class="fa fa-users mr-2"></i> Groups
        </a>
        @endif
        @if (Auth::check() && Auth::user()->is_admin)
        <a href="{{ route('manage.users') }}" class="flex items-center w-4/5 bg-primary text-tertiary font-bold px-4 py-3 rounded-r-full">
            <i class="fa fa-user-times mr-2"></i> Manage Users
        </a>
        @endif
        @endguest
        @auth
        <form method="POST" action="{{ route('logout') }}" class="w-4/7">
            @csrf
            <fieldset>
                <legend class="sr-only">Logout</legend>
                <button type="submit" class="flex items-center w-4/5 bg-primary text-tertiary font-bold px-4 py-3 rounded-r-full">
                    <i class="fa fa-sign-out mr-2 text-white"></i> Logout
                </button>
            </fieldset>
        </form>
        @endauth
    </nav>
</aside>