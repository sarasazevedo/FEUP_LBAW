@extends('layouts.app')

@section('title', 'Unread Notifications')

@section('og:title', 'Unread Notifications')
@section('og:description', 'View your unread notifications on Raffia.')
@section('og:image', asset('storage/default-notifications-image.png'))
@section('og:url', route('notifications.index'))

@section('content')
<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

<section class="p-4 bg-tertiary rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-[#1d3647]">Unread Notifications</h2>
    @if($notifications->isEmpty())
        <p class="text-gray-600">You have no unread notifications.</p>
    @else
        <ul class="follow-requests-list">
            @foreach($notifications as $notification)
                <?php
                    $subClassNotification = $notification->castToSubClass();
                ?>
                <li class="flex justify-between items-center mb-4 p-4 bg-white rounded-lg shadow" data-notification-id="{{ $notification->id }}">
                    <div>
                        @if($subClassNotification instanceof App\Models\LikePostNotification)
                            <p>
                                <a href="{{ route('profile.show', ['id' => $subClassNotification->user->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->user->name }}</a> 
                                liked your 
                                <a href="{{ route('posts.show', ['id' => $subClassNotification->post->id]) }}" class="font-bold text-[#1d3647]">post</a>
                            </p>
                        @elseif($subClassNotification instanceof App\Models\LikeCommentNotification)
                            <p>
                                <a href="{{ route('profile.show', ['id' => $subClassNotification->userThatLiked->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->userThatLiked->name }}</a> 
                                liked your  
                                <a href="{{ route('posts.show', ['id' => $subClassNotification->comment->post->id]) }}" class="font-bold text-[#1d3647]">comment</a>
                            </p>
                        @elseif($subClassNotification instanceof App\Models\FollowNotification)
                            <p>
                                <a href="{{ route('profile.show', ['id' => $subClassNotification->sender->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->sender->userDetails->name }}</a> 
                                sent you a 
                                <a href="{{ route('requests.show') }}" class="font-bold text-[#1d3647]">follow request</a>
                            </p>
                        @elseif($subClassNotification instanceof App\Models\CommentNotification)
                            <p>
                                <a href="{{ route('profile.show', ['id' => $subClassNotification->comment->user->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->comment->user->name }}</a> 
                                commented on your
                                <a href="{{ route('posts.show', ['id' => $subClassNotification->comment->post->id]) }}" class="font-bold text-[#1d3647]">post</a>
                            </p>
                        @elseif($subClassNotification instanceof App\Models\JoinGroupNotification)
                            <p>
                                <a href="{{ route('profile.show', ['id' => $subClassNotification->client->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->client->name }}</a> 
                                requested to join your group 
                                <a href="{{ route('groups.show', ['id' => $subClassNotification->group->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->group->name }}</a>.
                            </p>
                        @elseif($subClassNotification instanceof App\Models\GroupNotification)
                            <p>
                                <a href="{{ route('groups.show', ['id' => $subClassNotification->group->id]) }}" class="font-bold text-[#1d3647]">
                                    {{ $notification->content }}
                                </a>
                            </p>
                        @elseif($subClassNotification instanceof App\Models\StartedFollowingClientNotification)
                            <p>
                                <a href="{{ route('profile.show', ['id' => $subClassNotification->sender->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->sender->name }}</a> 
                                accepted your follow request
                            </p>
                        @elseif($subClassNotification instanceof App\Models\StartedFollowingRestaurantNotification)
                            <p>
                                <a href="{{ route('profile.show', ['id' => $subClassNotification->sender->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->sender->name }}</a> 
                                started following you
                            </p>
                        @elseif($subClassNotification instanceof App\Models\AppealUnblockNotification)
                            <p>
                                <a href="{{ route('profile.show', ['id' => $subClassNotification->userBlocked->id]) }}" class="font-bold text-[#1d3647]">{{ $subClassNotification->userBlocked->name }}</a> 
                                <a href="/manage-users">appealed for <strong>unblock</strong></a>
                            </p>
                            <p>
                                <strong>Message: </strong> {{$notification->content}}
                            </p>
                        @else
                            <p>{{ $notification->content }}</p>
                        @endif
                        <small class="text-gray-500">{{ $notification->datetime->diffForHumans() }}</small>
                    </div>
                    <div class="flex space-x-2">
                        <button class="mark-as-viewed-button bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</section>
@endsection