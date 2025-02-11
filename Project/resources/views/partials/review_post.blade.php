<article class="post border border-gray-300 p-6 mx-auto my-8 max-w-xl rounded-lg shadow-lg" data-id="{{ $post->id }}">
    @include('partials.post_header', ['post' => $post])
    <a href="/profile/{{ $post->restaurant->userDetails->id }}" class="block">
        <section class="post-restaurant mb-4 flex justify-between items-center bg-[#405865] p-4 rounded-lg">
            <section class="flex items-center">
                <img src="{{ asset("storage/" . $post->restaurant->userDetails->image) }}" alt="Restaurant Picture"
                    class="profile-picture w-12 h-12 rounded-full mr-4">
                <span class="text-xl font-bold text-white">{{ $post->restaurant->userDetails->name }}</span>
            </section>
            <section class="flex items-center">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $post->rating)
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                        </svg>
                    @endif
                @endfor
            </section>
        </section>
    </a>
    @include('partials.post_content_images', ['post' => $post])
    @if (isset($group) && !$group->members->contains(Auth::user()->castToSubclass()->id))
        <div class="hidden hidden-likes">
            @include('partials.post_likes', ['post' => $post])
        </div>
    @else
        <div class="hidden-likes">
            @include('partials.post_likes', ['post' => $post])
        </div>
    @endif
</article>
@if (isset($group) && !$group->members->contains(Auth::user()->castToSubclass()->id))
    <div class="hidden hidden-comments">
        @include('partials.comment', ['post' => $post])
    </div>
@else
    <div class="hidden-comments">
        @include('partials.comment', ['post' => $post])
    </div>
@endif
@include('partials.deletePost')
@include('partials.edit_post', ['post' => $post])