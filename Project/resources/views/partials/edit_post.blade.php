<!-- Edit Post Modal -->
<div id="edit-post-modal-{{ $post->id }}" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl">
        <form id="edit_post_{{ $post->id }}" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" onsubmit="sendEditPostRequest(event, {{ $post->id }})">
            @csrf
            @method('PUT')
            
            <fieldset class="form-group mb-4">
                <legend class="block text-gray-700 mb-2">Post Content</legend>
                <textarea name="content" placeholder="Edit your post..." class="w-full p-2 border rounded" maxlength="1800" required>{{ $post->postDetails->content }}</textarea>
            </fieldset>
            
            <fieldset class="form-group mb-4 relative">
                <legend class="block text-gray-700 mb-2">Upload Images</legend>
            
                <input type="file" name="images[]" id="uploaded-images-{{ $post->id }}" accept="image/*" multiple class="hidden" onchange="showUploadedImages(event, {{ $post->id }})">
                <label for="uploaded-images-{{ $post->id }}" class="cursor-pointer flex items-center bg-blue-500 text-white px-4 py-2 rounded-full shadow-md">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Choose Images
                </label>
                <div id="image-preview-{{ $post->id }}" class="mt-4 grid grid-cols-3 gap-2"></div>
            </fieldset>
            
            @if($post instanceof \App\Models\ReviewPost)
                <fieldset class="form-group mb-4">
                    <legend class="block text-gray-700 mb-2">Rating:</legend>
                   
                    <div id="rating-{{ $post->id }}" class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" data-value="{{ $i }}" onclick="setRating({{ $i }}, {{ $post->id }})">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                            </svg>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-value-{{ $post->id }}" value="{{ $post->rating }}" required>
                </fieldset>
            @endif
            
            <fieldset class="form-group mb-4">
                <legend class="sr-only">Actions</legend>
                <div class="flex items-center justify-between">
                    <button type="button" onclick="closeEditModal({{ $post->id }})" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>