<header id="header" class="flex justify-between items-center p-4 bg-primary">
    <section class="flex items-center space-x-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset("storage/" . 'images/logo.svg') }}" alt="Logo" class="h-10">
        </a>
        <h1 class="text-white font-bold text-xl">Raffia</h1>
    </section>
    <form class="relative mx-4 w-1/3">
        <fieldset>
            <legend class="sr-only">Search</legend>
            <input type="text" id="search-input" placeholder="Search..." class="w-full px-4 py-2 border rounded-lg">
            <button type="submit" class="absolute right-0 top-0 mt-2 mr-2 text-primary">
                <i class="fa fa-search text-primary hover:text-gray-300"></i>
            </button>
            <section id="search-container" class="absolute left-0 right-0 bg-white border rounded-lg shadow-lg hidden z-50">
                <fieldset>
                    <legend class="sr-only">Search Type</legend>
                    <section class="flex items-center justify-center mt-1">
                        <select id="search-type" class="bg-white border rounded-lg">
                            <option value="exact-match">Exact Match Search</option>
                            <option value="full-text">Full-text Search</option>
                        </select>
                    </section>
                </fieldset>
                <fieldset>
                    <legend class="sr-only">Search Controls</legend>
                    <section id="search-controls" class="flex justify-center mt-2 mb-2">
                        <button type="button" id="search-users" class="bg-white text-primary font-bold px-4 py-2 rounded-l-lg border-r">Users</button>
                        <button type="button" id="search-posts" class="bg-white text-primary font-bold px-4 py-2 border-r">Posts</button>
                        <button type="button" id="search-comments" class="bg-white text-primary font-bold px-4 py-2 border-r">Comments</button>
                        <button type="button" id="search-groups" class="bg-white text-primary font-bold px-4 py-2 rounded-r-lg">Groups</button>
                    </section>
                </fieldset>
                <section id="search-results" class="bg-white border rounded-lg shadow-lg"></section>
            </section>
        </fieldset>
    </form>
    <nav class="flex items-center space-x-4">
        @auth
            <a href="{{ route('notifications.index') }}" class="relative text-white">
                <i class="fa fa-bell text-white hover:text-gray-300"></i>
                <span id="notification-count" class="absolute bottom-3 left-2 bg-red-500 text-white rounded-full text-xs px-1 hidden">0</span>
            </a>
        @endauth
    </nav>
</header>