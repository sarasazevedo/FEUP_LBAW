@extends('layouts.app')

@section('title', 'Profile')

@section('og:title', $user->userDetails->name . "'s Profile")
@section('og:description', $user->userDetails->description)
@section('og:image', asset('storage/' . $user->userDetails->image))
@section('og:url', route('profile.show', ['id' => $user->id]))

@section('content')
<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

<section class="p-4 bg-tertiary rounded-lg shadow-md relative grid grid-cols-6 gap-4">
    @if (Auth::check() && Auth::id() === $user->id)
        <section class="absolute top-4 right-4">
            <button id="edit-button" class="text-fourth">
                <i class="fa fa-edit"></i>
            </button>
            <button id="confirm-button" class="text-green-500 hidden">
                <i class="fa fa-check"></i>
            </button>
            <button id="cancel-button" class="text-red-500 hidden">
                <i class="fa fa-times"></i>
            </button>
            <button id="delete-account-button" class="text-red-500" data-user-id="{{ $user->id }}">
                <i class="fa fa-trash"></i>
            </button>
        </section>
    @endif
    <section class="col-span-1 flex flex-col items-center">
        <img id="profile-image" src="{{ asset('storage/' . $user->userDetails->image) }}" alt="User Photo"
            class="w-48 h-48 rounded-full object-cover">
        <input type="file" id="image-input" class="hidden mt-2">
    </section>
    <section class="col-span-5 flex flex-col justify-between">
        <section id="profile-info">
            <section class="mt-4 grid grid-cols-4 items-center">
                <h2 id="profile-name" class="text-2xl font-bold col-span-3 text-[#1d3647]">
                    {{ $user->userDetails->name }}
                </h2>
                @if (Auth::check() && Auth::user()->castToSubclass() instanceof App\Models\Client && Auth::id() !== $user->id)
                    <section class="col-span-1">
                        @if ($user instanceof App\Models\Client)
                            @if ($followRequestPending)
                                <button id="pending-button" data-user-id="{{ $user->id }}"
                                    class="bg-gray-500 text-white font-bold px-4 py-2 rounded-full">Pending</button>
                            @elseif (Auth::user()->castToSubclass()->followed->contains($user->id))
                                <button id="unfollow-button" data-user-id="{{ $user->id }}"
                                    class="bg-primary text-white font-bold px-4 py-2 rounded-full">Unfollow</button>
                            @else
                                <button id="follow-button" data-user-id="{{ $user->id }}"
                                    class="bg-primary text-white font-bold px-4 py-2 rounded-full">Follow</button>
                            @endif
                        @elseif ($user instanceof App\Models\Restaurant)
                            @if (Auth::user()->castToSubclass()->followedRestaurants->contains($user->id))
                                <button id="unfollow-button" data-user-id="{{ $user->id }}"
                                    class="bg-primary text-white font-bold px-4 py-2 rounded-full">Unfollow</button>
                            @else
                                <button id="follow-button" data-user-id="{{ $user->id }}"
                                    class="bg-primary text-white font-bold px-4 py-2 rounded-full">Follow</button>
                            @endif
                        @endif
                    </section>
                @endif
            </section>
            <p id="profile-description" class="text-gray-600">{{ $user->userDetails->description }}</p>
        </section>
        <section id="edit-info" class="hidden flex space-x-4">
            <section class="flex flex-col w-1/2">
                <label for="name-input" class="text-gray-700">Name</label>
                <input type="text" id="name-input" class="mt-2 p-2 border rounded"
                    value="{{ $user->userDetails->name }}">
            </section>
            <section class="flex flex-col w-1/2">
                <label for="description-input" class="text-gray-700">Description</label>
                <textarea id="description-input"
                    class="mt-2 p-2 border rounded h-12">{{ $user->userDetails->description }}</textarea>
            </section>
        </section>
        <section class="mt-4 flex justify-around">
            @if ($user instanceof App\Models\Restaurant)
                <section class="text-center">
                    <p id="post-count" class="text-gray-600">{{ $user->posts->count() }}</p>
                    <p class="text-gray-600">Posts</p>
                </section>
                <section class="text-center">
                    <p class="text-gray-600">{{ $user->followers->count() }}</p>
                    <p class="text-gray-600">Followers</p>
                </section>
                <section class="text-center">
                    <p class="text-gray-600">{{ $user->rating_average ?? 'N/A' }}</p>
                    <p class="text-gray-600">Rating</p>
                </section>
                <section class="text-center">
                    <p class="text-gray-600">{{ $user->capacity }}</p>
                    <p class="text-gray-600">Capacity</p>
                </section>
                <section class="text-center">
                    <p class="text-gray-600">{{ $user->type->name }}</p>
                    <p class="text-gray-600">Type</p>
                </section>
            @elseif ($user instanceof App\Models\Client)
                <section class="text-center">
                    <p class="text-gray-600">{{ $user->posts->count() }}</p>
                    <p class="text-gray-600">Reviews</p>
                </section>
                <section class="text-center">
                    <p class="text-gray-600" data-user-id="{{ $user->id }}">{{ $user->followers->count() }}</p>
                    <p class="list-followers text-gray-600 cursor-pointer" data-user-id="{{ $user->id }}">Followers</p>
                </section>
                <section class="text-center">
                    <p class="following-count text-gray-600">{{ $user->followedCount() }}</p>
                    <p class="list-following text-gray-600 cursor-pointer" data-user-id="{{ $user->id }}">Following</p>
                </section>
            @endif
        </section>
    </section>
</section>

@if ((Auth::check() && Auth::user()->is_admin) || (Auth::check() && Auth::user()->castToSubclass() instanceof App\Models\Restaurant) || $user instanceof App\Models\Restaurant || (Auth::check() && Auth::user()->castToSubclass() instanceof App\Models\Client && Auth::user()->castToSubclass()->followed->contains($user->id)) || Auth::check() && Auth::id() === $user->id)
    <section class="mt-4" id="posts" data-offset="{{ $offset }}" data-user-id="{{ $user->id }}" data-base-url="/profile">
        @if($posts->isEmpty())
            <section class="flex justify-center items-center h-32">
                <p class="text-gray-500">{{ $user->userDetails->name }} does not have any post yet!</p>
            </section>
        @else
            <h3 id="posts_header_title" class="text-xl font-semibold text-[#1d3647]">Posts</h3>
            @foreach ($posts as $post)
                @if ($post instanceof App\Models\InformationalPost && $post->pinned)
                    @include('partials.informational_post', ['post' => $post])
                @endif
            @endforeach
            @foreach ($posts as $post)
                @if ($post instanceof App\Models\InformationalPost && !$post->pinned)
                    @include('partials.informational_post', ['post' => $post])
                @endif
            @endforeach
            @foreach ($posts as $post)
                @if ($post instanceof App\Models\ReviewPost)
                    @include('partials.review_post', ['post' => $post])
                @endif
            @endforeach
        @endif
    </section>
@endif

<!-- Delete Account Confirmation Modal -->
<section id="delete-account-confirmation-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
    <section class="bg-white rounded-lg overflow-hidden w-11/12 md:w-1/3 lg:w-1/4 relative z-50 shadow-lg">
        <section class="p-4">
            <h2 class="text-xl font-bold mb-4">Confirm Account Deletion</h2>
            <p>Are you sure you want to delete your account? This action cannot be undone.</p>
            <section class="mt-4 flex justify-end space-x-2">
                <button id="cancel-delete-account" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button id="confirm-delete-account" class="bg-red-500 text-white px-4 py-2 rounded" data-user-id="{{ $user->id }}">Delete</button>
            </section>
        </section>
    </section>
</section>

<!-- Modal Structure -->
@include('partials.followers_modal')
@include('partials.following_modal')

@endsection