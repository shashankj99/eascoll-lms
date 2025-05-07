<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manage Students
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

    <button wire:click="create()" class="bg-[#2f348f] hover:bg-black text-white font-bold py-2 px-4 rounded my-3">Add New Student</button>

    @if($isOpen)
        @include('livewire.students.create', ['departments' => $departments])
    @endif

    @if($confirmingDelete)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm text-center">
                <h2 class="text-lg font-semibold mb-4">Confirm Deletion</h2>
                <p class="text-gray-700 mb-6">Are you sure you want to delete this student?</p>
                <div class="flex justify-end space-x-4">
                    <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button wire:click="deleteConfirmed" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
            <thead class="bg-gray-300 text-gray-700 uppercase tracking-wider text-xs">
            <tr>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">Library Id</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Phone</th>
                <th class="px-6 py-3">Department</th>
                <th class="px-6 py-3">Address</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
            @foreach ($students as $index => $student)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-gray-900">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">{{ $student->library_id }}</td>
                <td class="px-6 py-4">{{ $student->name }}</td>
                <td class="px-6 py-4">{{ $student->email }}</td>
                <td class="px-6 py-4">{{ $student->phone }}</td>
                <td class="px-6 py-4 ">
                    <span class="px-2 py-1 inline-flex items-center rounded-md {{ $student->department->color_code }} text-xs font-medium {{ $student->department->text_color }} ring-inset">{{ $student->department->name }}</span>
                </td>
                <td class="px-6 py-4">{{ $student->address }}</td>
                <td class="px-6 py-4">
                    <button wire:click="edit({{ $student->id }})" class="text-blue-600 hover:text-blue-900 font-semibold mr-2">Edit</button>
                    <button wire:click="confirmDelete({{ $student->id }})" class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $students->links() }}
    </div>

</div>