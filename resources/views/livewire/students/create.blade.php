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
              <label class="block text-gray-700 text-sm font-bold mb-1">Department:</label>
              <select wire:model="department_id" class="w-full rounded px-3 py-2 focus:outline-none focus:ring">
                <option value="">Select Department</option>
                @foreach($departments as $department)
                  <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
              </select>
              @error('department_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Name:</label>
              <input type="text" wire:model="name" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter Name">
              @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Email:</label>
              <input type="email" wire:model="email" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter Email">
              @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Phone:</label>
              <input type="text" wire:model="phone" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter Phone Number">
              @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Library ID:</label>
              <input type="text" wire:model="library_id" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" placeholder="Enter Library ID (optional)">
              @error('library_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block text-gray-700 text-sm font-bold mb-1">Address:</label>
              <textarea wire:model="address" class="w-full rounded px-3 py-2 focus:outline-none focus:ring" rows="2" placeholder="Enter Address (optional)"></textarea>
              @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
