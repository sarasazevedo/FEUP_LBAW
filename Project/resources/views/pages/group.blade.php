@extends('layouts.app')

@section('title', 'Group Details')

@section('og:title', $group->name)
@section('og:description', $group->description)
@section('og:url', route('groups.show', ['id' => $group->id]))

@section('content')
<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

<section class="container mx-auto py-8">
    <!-- Group Header -->
    <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-primary" id="group-name">{{ $group->name }}</h1>
            <div class="flex space-x-2">
                @if ($group->members->contains(Auth::user()->castToSubclass()->id))
                    <button id="leave-group-button" class="bg-tertiary text-secondary px-4 py-2 rounded-full shadow-md mt-2"
                        data-group-id="{{ $group->id }}" data-is-public="{{ $group->is_public }}">Leave Group</button>
                @elseif ($group->is_public)
                    <button id="join-group-button" class="bg-primary text-white px-4 py-2 rounded-full shadow-md"
                        data-group-id="{{ $group->id }}" data-is-public="{{ $group->is_public }}">Join Group</button>
                @else
                    @if ($hasRequestedToJoin)
                        <button id="cancel-join-request-button" class="bg-primary text-white px-4 py-2 rounded-full shadow-md"
                            data-group-id="{{ $group->id }}">Cancel Request</button>
                    @else
                        <button id="request-join-group-button" class="bg-primary text-white px-4 py-2 rounded-full shadow-md"
                            data-group-id="{{ $group->id }}">Request to Join Group</button>
                    @endif
                @endif
                @if ($group->owner_id == Auth::id())
                    <button id="edit-description-button"
                        class="bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2 flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit Group
                    </button>
                @endif
            </div>
        </div>
        <p class="text-gray-600 mb-4" id="group-description">{{ $group->description }}</p>
        @if ($group->owner_id == Auth::id())
            <form id="edit-description-form" class="hidden mt-4" data-group-id="{{ $group->id }}">
                <fieldset>
                    <legend class="block text-sm font-medium text-primary">Edit Group Details</legend>
                    <input type="text" id="name-input" class="w-full p-2 border border-gray-300 rounded-lg mb-2"
                        value="{{ $group->name }}">
                    <textarea id="description-input"
                        class="w-full p-2 border border-gray-300 rounded-lg">{{ $group->description }}</textarea>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-full shadow-md mt-2">Save</button>
                    <button type="button" id="cancel-edit-description"
                        class="bg-tertiary text-secondary px-4 py-2 rounded-full shadow-md mt-2">Cancel</button>
                </fieldset>
            </form>
        @endif
        <p class="text-gray-600"><strong>Group Type:</strong> {{ $group->is_public ? 'Public' : 'Private' }}</p>
        <p class="text-gray-600"><strong>Owner:</strong> {{ $group->owner->userDetails->name }}</p>
    </section>

    <!-- Group Members -->
    <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
        <h2 class="text-xl font-bold mb-4">Group Members</h2>
        <ul id="group-members-list">
            @foreach ($members as $member)
                <li class="mb-4 p-4 bg-gray-100 rounded-lg shadow flex justify-between items-center" data-member-id="{{ $member->id }}">
                    <div>
                        <h3 class="text-lg font-bold">{{ $member->userDetails->name ?? 'Name not available' }}</h3>
                        <p class="text-gray-600">{{ $member->userDetails->email ?? 'Email not available' }}</p>
                    </div>
                    @if ($group->owner_id == Auth::id() && $member->id != Auth::id())
                        <button class="delete-member-button text-secondary px-4 py-2" data-group-id="{{ $group->id }}"
                            data-member-id="{{ $member->id }}">
                            <i class="fas fa-trash mr-2"></i>
                        </button>
                    @endif
                </li>
            @endforeach
        </ul>
        <div class="flex justify-between mt-4">
            @if ($members->previousPageUrl())
                <button id="prev-page-members" class="bg-primary text-white px-4 py-2 rounded-full shadow-md"
                    data-prev-page="{{ $members->currentPage() - 1 }}" data-group-id="{{ $group->id }}">Previous</button>
            @endif
            @if ($members->nextPageUrl())
                <button id="next-page-members" class="bg-primary text-white px-4 py-2 rounded-full shadow-md"
                    data-next-page="{{ $members->currentPage() + 1 }}" data-group-id="{{ $group->id }}">Next</button>
            @endif
        </div>
    </section>

    <!-- Invite Users to Group -->
    @if ($group->owner_id == Auth::id())
        <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-xl font-bold mb-4">Invite Users to Group</h2>
            <ul id="all-users-list">
                @foreach ($allUsers as $user)
                    @php
                        $invitation = \App\Models\GroupInvitation::where('group_id', $group->id)
                            ->where('client_id', $user->id)
                            ->where('status', 'pending')
                            ->first();
                    @endphp
                    <li class="mb-4 p-4 bg-gray-100 rounded-lg shadow flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold">{{ $user->userDetails->name ?? 'Name not available' }}</h3>
                            <p class="text-gray-600">{{ $user->userDetails->email ?? 'Email not available' }}</p>
                        </div>
                        @if ($invitation)
                            <button class="cancel-invite-user-button bg-primary text-white px-4 py-2 rounded-full shadow-md"
                                data-group-id="{{ $group->id }}" data-user-id="{{ $user->id }}">Cancel Invite</button>
                        @else
                            <button class="invite-user-button bg-primary text-white px-4 py-2 rounded-full shadow-md"
                                data-group-id="{{ $group->id }}" data-user-id="{{ $user->id }}">Invite</button>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="flex justify-between mt-4">
                <button id="prev-page-users" class="bg-primary text-white px-4 py-2 rounded-full shadow-md" data-page="1"
                    data-group-id="{{ $group->id }}">Previous</button>
                <button id="next-page-users" class="bg-primary text-white px-4 py-2 rounded-full shadow-md" data-page="2"
                    data-group-id="{{ $group->id }}">Next</button>
            </div>
        </section>
    @endif

    <!-- Join Requests -->
    @if ($group->owner_id == Auth::id() && !$group->is_public)
        <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-xl font-bold mb-4">Join Requests</h2>
            @if ($group->joinRequests->isEmpty())
                <p class="text-gray-600">No join requests for this group.</p>
            @else
                <ul class="join-requests-list">
                    @foreach ($group->joinRequests as $request)
                        <li class="mb-4 p-4 bg-gray-100 rounded-lg shadow">
                            <h3 class="text-lg font-bold">{{ $request->client->userDetails->name }}</h3>
                            <p class="text-gray-600">{{ $request->client->userDetails->email }}</p>
                            <button class="accept-join-request bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2"
                                data-group-id="{{ $group->id }}" data-client-id="{{ $request->client->id }}">Accept</button>
                            <button class="decline-join-request bg-tertiary text-secondary px-4 py-2 rounded-full shadow-md mt-2"
                                data-group-id="{{ $group->id }}" data-client-id="{{ $request->client->id }}">Decline</button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    @endif

    <!-- Group Posts -->
    @if ($group->is_public || $group->members->contains(Auth::user()->castToSubclass()->id))
        @include('partials.group_posts', ['posts' => $posts])
    @endif

    <!-- Create Post Button and Modal -->
    @if ($group->members->contains(Auth::user()->castToSubclass()->id))
        <button onclick="openCreatePostModal()" class="fixed bottom-4 right-4 bg-fourth text-white p-4 rounded-full shadow-lg no-print">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>
        <section id="create-post-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
            <section class="bg-white rounded-lg overflow-hidden w-11/12 md:w-3/4 lg:w-1/2 relative z-50 shadow-lg">
                <section class="p-4">
                    <h2 class="text-xl font-bold mb-4">Create Post</h2>
                    @include('partials.create_post', ['group' => $group])
                    <button onclick="closeCreatePostModal()" class="absolute top-0 right-0 m-4 text-black z-60 text-2xl px-4 py-2">&times;</button>
                </section>
            </section>
        </section>
    @endif

</section>
@endsection