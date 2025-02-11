<section class="post-actions flex justify-start items-center space-x-4 text-center">
    @auth
        <button type="button" id="like-button-{{ $post->id }}"
            class="like-button text-gray-500 px-4 py-2" data-post-id="{{ $post->id }}"
            onclick="like_unlike_post({{ $post->id }}, this)">
            <i class="fa fa-heart text-2xl {{ $post->isLikedByUser() ? 'text-red-500' : 'text-gray-500' }}"></i>
        </button>
        <span id="likes-count-{{ $post->id }}" class="likes-count text-secondary font-bold">{{ $post->likes->count() ?? 0 }}</span>
        <button type="button" onclick="openModal({{ $post->id }})"
            class="comments-button text-gray-500 px-4 py-2">
            <i class="fa fa-comment text-2xl"></i>
        </button>    
        <span id="comment-count-{{ $post->id }}" class="likes-count text-secondary font-bold">{{ $post->comments->count() ?? 0 }}</span>
    @endauth
</section>