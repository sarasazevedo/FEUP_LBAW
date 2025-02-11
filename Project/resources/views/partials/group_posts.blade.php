<section id="posts" class="group_posts bg-white p-6 rounded-lg shadow-lg" data-offset="{{ $offset }}" data-group-id="{{ $group->id }}" data-base-url="{{ route('groups.show', ['id' => $group->id]) }}">
    <h2 class="text-xl font-bold mb-4">Group Posts</h2>
    @forelse ($posts as $post)
        @if($post instanceof App\Models\ReviewPost)
            @include('partials.review_post', ['post' => $post])
        @endif
    @empty
        <h2 class="no_results">There are no posts in this group yet.</h2>
    @endforelse
</section>



