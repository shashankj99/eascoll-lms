<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    
    <div class="fixed inset-0 transition-opacity">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
      <form>
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="space-y-4">
            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Title:</label>
              <input type="text" wire:model="title" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter Title">
              @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Author:</label>
              <input type="text" wire:model="author" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter Author">
              @error('author') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Publisher:</label>
              <input type="text" wire:model="publisher" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter Publisher">
              @error('publisher') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">ISBN:</label>
              <input type="text" wire:model="isbn" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter ISBN">
              @error('isbn') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Quantity:</label>
              <input type="number" wire:model="quantity" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter Quantity">
              @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Description:</label>
              <textarea wire:model="description" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" rows="2" placeholder="Enter Description (optional)"></textarea>
              @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

          </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-4">
          <button wire:click.prevent="store" type="button" class="inline-flex justify-center w-full sm:w-auto rounded-md border-transparent px-4 py-2 bg-[#2f348f] text-white font-medium hover:bg-[#222831] focus:outline-none transition">
            Save
          </button>
          <button wire:click="closeModal" type="button" class="mt-3 sm:mt-0 inline-flex justify-center w-full sm:w-auto rounded-md border border-gray-300 px-4 py-2 bg-white text-gray-700 hover:text-gray-500 focus:outline-none transition">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
