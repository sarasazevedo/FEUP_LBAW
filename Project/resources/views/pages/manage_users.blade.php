@extends('layouts.app')

@section('og:title', 'Manage Users')
@section('og:description', 'Administer user accounts on Raffia.')
@section('og:image', asset('storage/default-manage-users-image.png'))
@section('og:url', route('manage.users'))

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4 text-primary">Manage Users</h1>
    
    <div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Name</th>
                    <th class="py-2 px-4 border-b text-left">Email</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    <th class="py-2 px-4 border-b text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                        @if (!$user->is_deleted)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->is_blocked ? 'Blocked' : 'Active' }}</td>
                                <td class="py-2 px-4 border-b">
                                    <button class="block-user-button bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2" data-user-id="{{ $user->id }}">
                                        {{ $user->is_blocked ? 'Unblock' : 'Block' }}
                                    </button>
                                    <button class="delete-user-button bg-tertiary text-secondary px-4 py-2 rounded-full shadow-md mt-2" data-user-id="{{ $user->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection