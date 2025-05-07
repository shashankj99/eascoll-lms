<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Books
    </h2>
</x-slot>

<div class="container mx-auto px-4 py-8">
    @if (session()->has('message'))
        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
            <div class="flex">
            <div>
                <p class="text-sm">{{ session('message') }}</p>
            </div>
            </div>
        </div>
    @endif

    <button wire:click="create()" class="bg-[#2f348f] hover:bg-black text-white font-bold py-2 px-4 my-3 rounded">Add New Book</button>
    <button 
        wire:click="openIssueModal" 
        class="bg-[#2f348f] hover:bg-black text-white font-bold py-2 px-4 ml-3 my-3 rounded {{ $this->canIssue ? '' : 'opacity-50 cursor-not-allowed' }}"
        {{ $this->canIssue ? '' : 'disabled' }}
    >
        Issue Book
    </button>
    <input
        type="text"
        wire:model.live.debounce.500ms="bookSearch"
        placeholder="Search books..."
        class="border rounded px-4 py-2 md:ml-3 lg:ml-3 xl:ml-3 my-3 w-full md:w-1/4 lg:w-1/4 xl:w-1/4"
    />

    @if($isOpen)
        @include('livewire.books.create')
    @endif

    @if($confirmingDelete)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm text-center">
                <h2 class="text-lg font-semibold mb-4">Confirm Deletion</h2>
                <p class="text-gray-700 mb-6">Are you sure you want to delete this book?</p>
                <div class="flex justify-end space-x-4">
                    <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button wire:click="deleteConfirmed" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    @endif

    @if($showIssueModal)
        @include('livewire.books.issue')
    @endif

    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
            <thead class="bg-gray-300 text-gray-700 uppercase tracking-wider text-xs">
            <tr>
                <th class="px-6 py-3"></th>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">Title</th>
                <th class="px-6 py-3">Author</th>
                <th class="px-6 py-3">Publisher</th>
                <th class="px-6 py-3">ISBN</th>
                <th class="px-6 py-3">Quantity</th>
                <th class="px-6 py-3">Description</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
            @foreach ($books as $index => $book)
            <tr wire:key="book-{{ $book->id }}" class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <input 
                        type="checkbox" 
                        wire:model="selectedBooks" 
                        value="{{ $book->id }}" 
                        wire:change="$refresh"
                    />
                </td>
                <td class="px-6 py-4 font-medium text-gray-900">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">{{ $book->title }}</td>
                <td class="px-6 py-4">{{ $book->author }}</td>
                <td class="px-6 py-4">{{ $book->publisher }}</td>
                <td class="px-6 py-4">{{ $book->isbn }}</td>
                <td class="px-6 py-4">{{ $book->quantity }}</td>
                <td class="px-6 py-4">{{ $book->description }}</td>
                <td class="px-6 py-4">
                    <button wire:click="edit({{ $book->id }})" class="text-blue-600 hover:text-blue-900 font-semibold mr-2">Edit</button>
                    <button wire:click="confirmDelete({{ $book->id }})" class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $books->links() }}
    </div>

</div>