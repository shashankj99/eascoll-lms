<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Issued Books
    </h2>
</x-slot>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search by book title, author, or student..." 
                    class="w-full border rounded px-3 py-2"
                >
            </div>
            <div>
                <select wire:model.live="statusFilter" class="w-full border rounded px-3 py-2">
                    <option value="">All Status</option>
                    <option value="borrowed">Borrowed</option>
                    <option value="returned">Returned</option>
                </select>
            </div>
            <div>
                <select wire:model.live="dateFilter" class="w-full border rounded px-3 py-2">
                    <option value="">All Dates</option>
                    <option value="overdue">Overdue</option>
                    <option value="due_soon">Due Soon (3 days)</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($issuedBooks as $book)
                        @foreach($book->students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $book->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $book->author }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->department->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($student->pivot->borrow_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $returnDate = \Carbon\Carbon::parse($student->pivot->return_date);
                                        $isOverdue = $returnDate->isPast();
                                        $isDueSoon = $returnDate->isFuture() && $returnDate->diffInDays(now()) <= 3;
                                    @endphp
                                    <span class="text-sm {{ $isOverdue ? 'text-red-600' : ($isDueSoon ? 'text-yellow-600' : 'text-gray-500') }}">
                                        {{ $returnDate->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $student->pivot->status === 'borrowed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($student->pivot->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No issued books found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $issuedBooks->links() }}
        </div>
    </div>
</div>
