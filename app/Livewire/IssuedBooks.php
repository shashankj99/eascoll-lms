<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Student;

class IssuedBooks extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';

    public function render()
    {
        $search = trim($this->search);

        $query = Book::query()
            ->whereHas('students', function ($q) {
                $q->where('status', 'borrowed');
            });

        if (strlen($search) > 2) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhereHas('students', function ($q) use ($search) {
                        $q->where('status', 'borrowed')
                            ->where(function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($this->statusFilter) {
            $query->whereHas('students', function ($q) {
                $q->where('status', $this->statusFilter);
            });
        }

        if ($this->dateFilter) {
            $query->whereHas('students', function ($q) {
                switch ($this->dateFilter) {
                    case 'overdue':
                        $q->where('return_date', '<', now()->format('Y-m-d'));
                        break;
                    case 'due_soon':
                        $q->whereBetween('return_date', [
                            now()->format('Y-m-d'),
                            now()->addDays(3)->format('Y-m-d')
                        ]);
                        break;
                }
            });
        }

        $query->with(['students' => function ($q) {
            $q->where('status', 'borrowed')
                ->with('department');
        }]);

        return view('livewire.issued-books', [
            'issuedBooks' => $query->paginate(50),
        ]);
    }
}
