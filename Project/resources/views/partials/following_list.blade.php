<!-- resources/views/partials/following_list.blade.php -->
@if($followingClients->isEmpty() && $followingRestaurants->isEmpty())
    <p id="noFollowingMessage" class="text-gray-500">You are not following anyone.</p>
@else
    <p id="noFollowingMessage" class="text-gray-500 hidden">You are not following anyone.</p>
    <ul id="followingList" class="max-h-48 overflow-y-auto">
        @foreach($followingClients->take(6) as $followedClient)
            <li class="flex justify-between items-center mb-4">
                <span class="mr-4">{{ $followedClient->userDetails->name }}</span>
                <button class="unfollow-btn bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2" data-followed-id="{{ $followedClient->id }}">Unfollow</button>
            </li>
        @endforeach
        @foreach($followingRestaurants->take(6) as $followedRestaurant)
            <li class="flex justify-between items-center mb-4">
                <span class="mr-4">{{ $followedRestaurant->userDetails->name }}</span>
                <button class="unfollow-btn bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2" data-followed-id="{{ $followedRestaurant->id }}">Unfollow</button>
            </li>
        @endforeach
    </ul>
    <div id="loading" class="text-center hidden">Loading...</div>
@endif