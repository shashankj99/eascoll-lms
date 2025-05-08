<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class IssuedBooks extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';

    public function returnBook($bookId, $studentId)
    {
        try {
            DB::beginTransaction();

            $bookStudent = DB::table('book_students')
                ->where('book_id', $bookId)
                ->where('student_id', $studentId)
                ->where('status', 'borrowed')
                ->first();

            if (!$bookStudent) {
                throw new \Exception('Book is not currently borrowed by this student.');
            }

            DB::table('book_students')
                ->where('book_id', $bookId)
                ->where('student_id', $studentId)
                ->where('status', 'borrowed')
                ->update([
                    'status' => 'returned',
                    'return_date' => now()->format('Y-m-d H:i:s')
                ]);

            Book::where('id', $bookId)->increment('quantity');

            DB::commit();
            session()->flash('message', 'Book returned successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage() ?: 'Failed to return book. Please try again.');
        }
    }

    public function render()
    {
        $search = trim($this->search);

        $query = Book::query();

        if (strlen($search) > 2) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhereHas('students', function ($q) use ($search) {
                        $q->where(function ($q) use ($search) {
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
            $q->with('department');
        }]);

        return view('livewire.issued-books', [
            'issuedBooks' => $query->paginate(50),
        ]);
    }
}
