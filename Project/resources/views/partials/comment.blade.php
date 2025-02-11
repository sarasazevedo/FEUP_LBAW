<section id="comment-modal-{{ $post->id }}" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
    <section class="bg-white rounded-lg overflow-hidden w-11/12 md:w-3/4 lg:w-1/2 relative z-50 shadow-lg">
        <section class="flex">
            <section class="w-1/2 relative">
                <section class="h-full flex items-center justify-center">
                    <img id="modal-current-image-{{ $post->id }}" src="{{ asset("storage/" . $post->postDetails->images[0]) }}" alt="Post Image" class="object-contain max-h-full max-w-full rounded-lg shadow-md">
                </section>
                @if(count($post->postDetails->images) > 1)
                    <button onclick="prevModalImage{{ $post->id }}()" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white px-2 py-1 rounded-full shadow-md z-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="nextModalImage{{ $post->id }}()" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white px-2 py-1 rounded-full shadow-md z-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @endif
            </section>
            <section class="w-1/2 p-4">
                <section class="comments-section break-words h-64 overflow-y-auto" id="comments-section-{{ $post->id }}" data-post-id="{{ $post->id }}">
                </section>
                <section id="loading-indicator-{{ $post->id }}" class="hidden text-center py-2">Loading...</section>
                <section class="mt-4">
                    <textarea id="comment-box-{{ $post->id }}" class="w-full p-2 border rounded-lg" rows="3" placeholder="Add a comment..."></textarea>
                    <button type="button" id="comment-button-{{ $post->id }}"
                        class="comment-button bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2" data-post-id="{{ $post->id }}" data-method="POST"
                        onclick="submitComment({{ $post->id }}, this)">
                        Post
                    </button>
                </section>
            </section>
        </section>
        <button onclick="closeModal({{ $post->id }})" class="absolute top-0 left-0 m-4 text-black z-60 text-2xl px-4 py-2">&times;</button>
    </section>
</section>

<!-- Delete Comment Confirmation Modal -->
<section id="delete-comment-confirmation-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
    <section class="bg-white rounded-lg overflow-hidden w-11/12 md:w-1/3 lg:w-1/4 relative z-50 shadow-lg">
        <section class="p-4">
            <h2 class="text-xl font-bold mb-4">Confirm Deletion</h2>
            <p>Are you sure you want to delete this comment?</p>
            <section class="mt-4 flex justify-end space-x-2">
                <button id="cancel-delete-comment" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button id="confirm-delete-comment" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
            </section>
        </section>
    </section>
</section>