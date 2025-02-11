<section id="appealUnblockPopupContainer" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
  <section class="bg-white rounded-lg shadow-lg p-6 w-1/3">
    <h2 class="text-primary font-bold text-xl mb-4">Appeal Unblock</h2>
    <form id="appealUnblockForm">
      <fieldset>
        <legend class="block text-gray-700 mb-2">Your Message to Admin <span class="text-red-500">*</span></legend>
        <section class="relative">
          <input type="text" id="appealMessage" name="appealMessage" placeholder="Your message here" maxlength="50" class="w-full border rounded-lg p-2 mb-2 text-secondary" required>
          <span class="absolute right-2 top-2 cursor-pointer" title="Maximum 50 characters">
            <i class="fas fa-info-circle text-gray-500"></i>
          </span>
        </section>
      </fieldset>
      <fieldset class="flex justify-end space-x-4 mt-4">
        <legend class="sr-only">Actions</legend>
        <button type="button" id="cancelUnblockAppealButton" class="bg-tertiary text-secondary font-bold px-4 py-2 rounded-lg">Cancel</button>
        <button type="button" id="sendUnblockAppealButton" class="bg-secondary text-tertiary font-bold px-4 py-2 rounded-lg">Send</button>
      </fieldset>
    </form>
  </section>
</section>