<!-- resources/views/partials/following_modal.blade.php -->
<div id="followingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-1/2 max-w-3xl">
        <div class="p-6">
            <h3 class="text-xl leading-6 font-medium text-gray-900 text-center">Following</h3>
            <div class="mt-4">
                <ul id="followingList" class="text-left max-h-96 overflow-y-auto">
                    <!-- Following will be appended here -->
                </ul>
            </div>
            <div class="mt-6 flex justify-center">
                <button id="closeFollowingModal" class="bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>