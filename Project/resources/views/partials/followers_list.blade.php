<!-- resources/views/partials/followers_list.blade.php -->
@if($followers->isEmpty())
    <p class="text-gray-500">There are no followers.</p>
@else
    <ul id="followersList" class="max-h-64 overflow-y-auto">
        @foreach($followers as $follower)
            <li class="flex justify-between items-center mb-4">
                <span class="mr-4">{{ $follower->name }}</span>
            </li>
        @endforeach
    </ul>
@endif