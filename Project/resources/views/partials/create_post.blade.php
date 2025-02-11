<form id="create_post" action="{{ route('posts.create') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($group))
        <input type="hidden" name="group_id" value="{{ $group->id }}">
    @endif

    <fieldset class="form-group mb-4">
        <legend class="block text-gray-700 mb-2">Post Content <span class="text-red-500">*</span></legend>
        <textarea name="content" placeholder="Write your post..." class="w-full p-2 border rounded" maxlength="1800" required></textarea>
    </fieldset>

    <fieldset class="form-group mb-4 relative">
        <legend class="block text-gray-700 mb-2">Upload Images <span class="text-red-500">*</span></legend>
        <input type="file" name="images[]" id="uploaded-images" accept="image/*" multiple class="hidden" onchange="showUploadedImages(event)">
        <label for="uploaded-images" class="cursor-pointer flex items-center bg-blue-500 text-white px-4 py-2 rounded-full shadow-md">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Choose Images
        </label>
        <div id="image-preview" class="mt-4 grid grid-cols-3 gap-2"></div>
    </fieldset>

    @if(Auth::check() && Auth::user()->castToSubclass() instanceof App\Models\Client)
        <fieldset class="form-group mb-4 relative">
            <legend class="block text-gray-700 mb-2">Restaurant: <span class="text-red-500">*</span></legend>
            <input type="text" id="restaurant_search" placeholder="Search for a restaurant..." autocomplete="off" class="w-full p-2 border rounded" required>
            <input type="hidden" id="restaurant_id" name="restaurant_id" required>
            <div id="restaurant_suggestions" class="absolute w-full bg-white border rounded mt-1 z-10 max-h-48 overflow-y-auto"></div>
        </fieldset>

        <fieldset class="form-group mb-4">
            <legend class="block text-gray-700 mb-2">Rating: <span class="text-red-500">*</span></legend>
            <div id="rating" class="flex space-x-1">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-6 h-6 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" data-value="{{ $i }}" onclick="setRating({{ $i }})">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                    </svg>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating-value" required>
        </fieldset>
    @endif

    <fieldset class="form-group mb-4">
        <legend class="sr-only">Submit</legend>
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Post</button>
    </fieldset>
</form>