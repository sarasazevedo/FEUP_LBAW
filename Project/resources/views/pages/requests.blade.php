
@extends('layouts.app')

@section('title', 'Follow Requests')

@section('og:title', 'Follow Requests')
@section('og:description', 'Manage your follow requests on Raffia.')
@section('og:image', asset('storage/default-requests-image.png'))
@section('og:url', route('requests.show'))

@section('content')
<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

<section class="p-4 bg-tertiary rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-[#1d3647]">Follow Requests</h2>
    @if ($followRequests->isEmpty())
        <p class="text-gray-600">No follow requests at the moment.</p>
    @else
        <ul class="follow-requests-list">
            @foreach ($followRequests as $request)
                <li class="flex justify-between items-center mb-4 p-4 bg-white rounded-lg shadow" data-requester-id="{{ $request->requester_client_id }}">
                    <div>
                        <p class="text-xl font-bold text-[#1d3647]">{{ $request->requester->userDetails->name }}</p>
                        <p class="text-gray-600">{{ $request->requester->userDetails->email }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="accept-follow-button bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2">Accept</button>
                        <button class="reject-follow-button bg-tertiary text-secondary px-4 py-2 rounded-full shadow-md mt-2">Reject</button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</section>
@endsection