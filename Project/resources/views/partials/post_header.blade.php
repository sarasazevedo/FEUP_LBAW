<header id="post-{{ $post->id }}" class="mb-4 flex justify-between items-center bg-[#405865] p-4 rounded-t-lg">
    <section class="flex items-center">
        @if ($post instanceof \App\Models\ReviewPost)
            <a href="/profile/{{ $post->client->userDetails->id }}" class="flex items-center">
                <img src="{{ asset("storage/" . $post->client->userDetails->image) }}" alt="Profile Picture"
                    class="profile-picture w-12 h-12 rounded-full mr-4">
                <section class="flex flex-col">
                    <span class="text-xl font-bold text-white">{{ $post->client->userDetails->name }}</span>
                    <span class="exact_time hidden">{{ $post->postDetails->datetime }}</span>
                    <span class="text-sm text-gray-300">{{ $post->postDetails->datetime->diffForHumans() }}</span>
                </section>
            </a>
        @elseif ($post instanceof \App\Models\InformationalPost)
            <a href="/profile/{{ $post->restaurant->userDetails->id }}" class="flex items-center">
                <img src="{{ asset("storage/" . $post->restaurant->userDetails->image) }}" alt="Profile Picture"
                    class="profile-picture w-12 h-12 rounded-full mr-4">
                <section class="flex flex-col">
                    <span class="text-xl font-bold text-white">{{ $post->restaurant->userDetails->name }}</span>
                    <span class="exact_time hidden">{{ $post->postDetails->datetime }}</span>
                    <span class="text-sm text-gray-300">{{ $post->postDetails->datetime->diffForHumans() }}</span>
                </section>
            </a>
        @endif
    </section>
    <section class="flex items-center space-x-4 post-header-icons"> 
        @if ($post->pinned)
            <i class="pinned_icon fa fa-thumbtack text-white ml-2" title="Pinned"></i>
        @endif
        @auth
            @if ((Auth::check() && Auth::user()->is_admin) || auth()->id() == ($post->client->userDetails->id ?? $post->restaurant->userDetails->id))
                <section id="toggle-dropdown" class="relative">
                    <button onclick="toggleDropdown({{ $post->id }})" class="dropdown-button text-white focus:outline-none p-2"> 
                        <i class="fa fa-ellipsis-v"></i>
                    </button>
                    <section id="dropdown-{{ $post->id }}" class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                        <button type="button" onclick="showDeleteConfirmation({{ $post->id }})"
                            class="delete-button text-red-500 px-4 py-2 w-full text-left">
                            <i class="fa fa-trash text-2xl"></i> Delete
                        </button>
                        <button type="button" onclick="openEditModal({{ $post->id }}, '{{ $post instanceof \App\Models\ReviewPost ? 'review' : 'informational' }}', {{ $post->rating ?? 'null' }})"
                            class="edit-button text-blue-500 px-4 py-2 w-full text-left">
                            <i class="fa fa-edit text-2xl"></i> Edit
                        </button>
                        @if ($post instanceof \App\Models\InformationalPost)
                            <button type="button" onclick="togglePinPost({{ $post->id }}, '{{ $post->pinned ? 'unpin' : 'pin' }}')"
                                class="pin-button text-green-500 px-4 py-2 w-full text-left">
                                <i class="fa fa-thumbtack text-2xl"></i> {{ $post->pinned ? 'Unpin' : 'Pin' }}
                            </button>
                        @endif
                    </section>
                </section>
            @endif
        @endauth
    </section>
</header>