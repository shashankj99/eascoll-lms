<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
        <h2 class="text-lg font-semibold mb-4">Issue Book(s) to Student</h2>
        <input 
            type="text" 
            wire:model.live.debounce.500ms="studentSearch" 
            placeholder="Search student by name or email..." 
            class="w-full border rounded px-3 py-2 mb-2"
        >
        @if($studentResults)
            <ul class="border rounded mb-4 max-h-40 overflow-y-auto">
                @foreach($studentResults as $student)
                    <li 
                        class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                        wire:click="selectStudent({{ $student['id'] }})"
                    >
                        {{ $student['name'] }} ({{ $student['email'] }} | {{ $student['department']['name'] }})
                    </li>
                @endforeach
            </ul>
        @endif

        @if($selectedStudentId)
            <div class="mb-4 text-green-700">
                Selected Student: {{ $studentSearch }}
            </div>
            <div class="mb-4">
                <label for="returnDate" class="block text-sm font-medium text-gray-700 mb-1">Expected Return Date</label>
                <input 
                    type="date" 
                    id="returnDate"
                    wire:model="returnDate" 
                    class="w-full border rounded px-3 py-2"
                    min="{{ date('Y-m-d') }}"
                >
                @error('returnDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="flex justify-end space-x-2">
            <button wire:click="closeIssueModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            <button 
                wire:click="issueBooks" 
                class="px-4 py-2 bg-[#2f348f] text-white rounded hover:bg-black"
                @if(!$selectedStudentId) disabled class="opacity-50 cursor-not-allowed" @endif
            >Issue</button>
        </div>
    </div>
</div>