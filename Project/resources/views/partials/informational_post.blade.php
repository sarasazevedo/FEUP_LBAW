<article class="post border border-gray-300 p-6 mx-auto my-8 max-w-xl rounded-lg shadow-lg" data-id="{{ $post->id }}">
    @include('partials.post_header', ['post' => $post])
    @include('partials.post_content_images', ['post' => $post])
    @include('partials.post_likes', ['post' => $post])
</article>

@include('partials.comment')
@include('partials.deletePost')
@include('partials.edit_post', ['post' => $post])