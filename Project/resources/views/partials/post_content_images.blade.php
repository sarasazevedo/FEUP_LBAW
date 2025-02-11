<section class="post-content mb-4 text-center">
    <p>{{ $post->postDetails->content }}</p>
</section>
<section class="post-images mb-4">
    <section class="image-slider relative" data-images='@json($post->postDetails->images)'>
        <img id="current-image-{{ $post->id }}" src="{{ asset("storage/" . $post->postDetails->images[0]) }}" alt="Post Image"
            class="mx-auto mb-4 w-full h-64 object-contain rounded-lg shadow-md">
        @if(count($post->postDetails->images) > 1)
            <button onclick="prev{{ $post instanceof \App\Models\ReviewPost ? 'Review' : 'Info' }}Image{{ $post->id }}()"
                class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white px-2 py-1 rounded-full shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="next{{ $post instanceof \App\Models\ReviewPost ? 'Review' : 'Info' }}Image{{ $post->id }}()"
                class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white px-2 py-1 rounded-full shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <section class="absolute bottom-0 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach($post->postDetails->images as $index => $image)
                    <span id="dot-{{ $post->id }}-{{ $index }}"
                        class="dot w-3 h-3 rounded-full {{ $index === 0 ? 'bg-blue-500' : 'bg-gray-300' }}"></span>
                @endforeach
            </section>
        @endif
    </section>
</section>