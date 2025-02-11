@extends('layouts.app')

@section('title', 'Groups')

@section('og:title', 'Groups')
@section('og:description', 'Explore and manage groups on Raffia.')
@section('og:image', asset('storage/default-groups-image.png'))
@section('og:url', route('groups.index'))

@section('content')
<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

<section class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4 text-primary">Groups</h1>

    <div class="tabs mb-4 flex">
        <button id="created-groups-tab" class="tab-button flex-1 p-4 bg-gray-200 text-gray-700 font-bold"
            onclick="showTab('created-groups')">Groups You Have Created</button>
        <button id="joined-groups-tab" class="tab-button flex-1 p-4 bg-gray-200 text-gray-700 font-bold"
            onclick="showTab('joined-groups')">Groups You Have Joined</button>
        <button id="public-groups-tab" class="tab-button flex-1 p-4 bg-gray-200 text-gray-700 font-bold"
            onclick="showTab('public-groups')">Public Groups</button>
        <button id="private-groups-tab" class="tab-button flex-1 p-4 bg-gray-200 text-gray-700 font-bold"
            onclick="showTab('private-groups')">Private Groups</button>
        <button id="invitations-tab" class="tab-button flex-1 p-4 bg-gray-200 text-gray-700 font-bold"
            onclick="showTab('invitations')">Invitations</button>
    </div>

    <div id="created-groups" class="tab-content">
        <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
            @if (isset($createdGroups) && $createdGroups->isEmpty())
                <p class="text-gray-600">You have not created any groups.</p>
            @elseif (isset($createdGroups))
                <ul>
                    @foreach ($createdGroups as $group)
                        <li class="mb-4 p-4 bg-gray-100 rounded-lg shadow">
                            <h3 class="text-lg font-bold">{{ $group->name }}</h3>
                            <p class="text-gray-600">{{ $group->description }}</p>
                            <a href="{{ route('groups.show', ['id' => $group->id]) }}"
                                class="text-blue-500 hover:underline">View Group</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">You have not created any groups.</p>
            @endif
        </section>
    </div>

    <div id="joined-groups" class="tab-content hidden">
        <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
            @if (isset($joinedGroups) && $joinedGroups->isEmpty())
                <p class="text-gray-600">You have not joined any groups.</p>
            @elseif (isset($joinedGroups))
                <ul>
                    @foreach ($joinedGroups as $group)
                        <li class="mb-4 p-4 bg-gray-100 rounded-lg shadow">
                            <h3 class="text-lg font-bold">{{ $group->name }}</h3>
                            <p class="text-gray-600">{{ $group->description }}</p>
                            <a href="{{ route('groups.show', ['id' => $group->id]) }}"
                                class="text-blue-500 hover:underline">View Group</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">You have not joined any groups.</p>
            @endif
        </section>
    </div>
    <div id="public-groups" class="tab-content hidden">
        <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
            @if (isset($publicGroups) && $publicGroups->isEmpty())
                <p class="text-gray-600">There are no public groups available.</p>
            @elseif (isset($publicGroups))
                <ul>
                    @foreach ($publicGroups as $group)
                        <li class="mb-4 p-4 bg-gray-100 rounded-lg shadow">
                            <h3 class="text-lg font-bold">{{ $group->name }}</h3>
                            <p class="text-gray-600">{{ $group->description }}</p>
                            <a href="{{ route('groups.show', ['id' => $group->id]) }}"
                                class="text-blue-500 hover:underline">View Group</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">There are no public groups available.</p>
            @endif
        </section>
    </div>
    <div id="private-groups" class="tab-content hidden">
        <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
            @if (isset($privateGroups) && $privateGroups->isEmpty())
                <p class="text-gray-600">There are no private groups available.</p>
            @elseif (isset($privateGroups))
                <ul>
                    @foreach ($privateGroups as $group)
                        <li class="mb-4 p-4 bg-gray-100 rounded-lg shadow">
                            <h3 class="text-lg font-bold">{{ $group->name }}</h3>
                            <p class="text-gray-600">{{ $group->description }}</p>
                            <a href="{{ route('groups.show', ['id' => $group->id]) }}"
                                class="text-blue-500 hover:underline">View Group</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">There are no private groups available.</p>
            @endif
        </section>
    </div>

    <div id="invitations" class="tab-content hidden">
        <section class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-xl font-bold mb-4">Invitations</h2>
            <ul id="invitations-list">
                <!-- Invitations will be loaded here -->
            </ul>
        </section>
    </div>

</section>

<!-- Plus Icon Button -->
<button id="open-create-group-modal" class="fixed bottom-4 right-4 bg-fourth text-white p-4 rounded-full shadow-lg">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
    </svg>
</button>

<!-- Create Group Modal -->
<div id="create-group-modal"
    class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2" id="create-group-modal-content">
        <h2 class="text-xl font-bold mb-4">Create a New Group</h2>
        <form id="create-group-form" method="POST" action="{{ route('groups.store') }}">
            @csrf
            <fieldset class="mb-4">
                <legend class="block text-gray-700">Group Name <span class="text-red-500">*</span></legend>
                <input type="text" id="name" name="name"
                    class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm"
                    required>
            </fieldset>
            <fieldset class="mb-4">
                <legend class="block text-gray-700">Description <span class="text-red-500">*</span></legend>
                <textarea id="description" name="description"
                    class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm"
                    required></textarea>
            </fieldset>
            <fieldset class="mb-4">
                <legend class="block text-gray-700">Group Type <span class="text-red-500">*</span></legend>
                <select id="is_public" name="is_public"
                    class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm"
                    required>
                    <option value="1">Public</option>
                    <option value="0">Private</option>
                </select>
            </fieldset>
            <div class="flex justify-end">
                <button type="button" id="close-create-group-modal"
                    class="bg-gray-500 text-white px-4 py-2 rounded-full shadow-md mr-2">Cancel</button>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-full shadow-md">Create
                    Group</button>
            </div>
        </form>
    </div>
</div>
@endsection