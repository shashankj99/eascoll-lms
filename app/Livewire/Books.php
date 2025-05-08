<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
use App\Models\Student;

class Books extends Component
{
    use WithPagination;

    public $title;
    public $author;
    public $publisher;
    public $isbn;
    public $quantity;
    public $description;
    public $isOpen = false;
    public $confirmingDelete = false;
    public $bookId;
    public $deleteId;
    public $selectedBooks = [];
    public $showIssueModal = false;
    public $studentSearch = '';
    public $bookSearch = '';
    public $studentResults = [];
    public $selectedStudentId = null;
    public $returnDate;

    public function getBooksProperty()
    {
        $query = Book::query();

        $searchTerm = trim($this->bookSearch);
        if (strlen($searchTerm) > 2) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('author', 'like', "%{$searchTerm}%")
                ->orWhere('publisher', 'like', "%{$searchTerm}%")
                ->orWhere('isbn', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        return $query->orderBy('id', 'desc')->paginate(50);
    }

    public function render()
    {
        return view('livewire.books', [
            'books' => $this->books,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInputFields()
    {
        $this->title = '';
        $this->author = '';
        $this->publisher = '';
        $this->isbn = '';
        $this->quantity = '';
        $this->description = '';
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'isbn' => [
                'required',
                'numeric',
                'digits:13',
                Rule::unique('books', 'isbn')->ignore($this->bookId)
            ],
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable',
        ]);

        Book::updateOrCreate(
            [
                'id' => $this->bookId
            ],
            [
                'title' => $this->title,
                'author' => $this->author,
                'publisher' => $this->publisher,
                'isbn' => $this->isbn,
                'quantity' => $this->quantity,
                'description' => $this->description,
            ]
        );

        session()->flash('message', 
            $this->bookId ? 'Book Updated Successfully.' : 'Book Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $this->bookId = $id;
        $this->title = $book->title;
        $this->author = $book->author;
        $this->publisher = $book->publisher;
        $this->isbn = $book->isbn;
        $this->quantity = $book->quantity;
        $this->description = $book->description;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->deleteId = null;
    }

    public function deleteConfirmed()
    {
        Book::find($this->deleteId)->delete();
        session()->flash('message', 'Book Deleted Successfully.');
        $this->cancelDelete();
    }

    public function openIssueModal()
    {
        $this->showIssueModal = true;
        $this->studentSearch = '';
        $this->returnDate = now()->addDays(14)->format('Y-m-d');
    }

    public function closeIssueModal()
    {
        $this->showIssueModal = false;
        $this->studentSearch = '';
        $this->returnDate = null;
    }

    public function getCanIssueProperty()
    {
        return count($this->selectedBooks) > 0;
    }

    public function updatedStudentSearch()
    {
        $search = trim($this->studentSearch);
        if (strlen($search) > 2) {
            $this->studentResults = Student::with('department')->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->studentResults = [];
        }
    }

    public function selectStudent($studentId)
    {
        $this->selectedStudentId = $studentId;
        $student = Student::find($studentId);
        $this->studentSearch = $student ? "{$student->name} | {$student->email} | {$student->department->name}" : '';
        $this->studentResults = [];
    }

    public function issueBooks()
    {
        if (!$this->selectedStudentId || empty($this->selectedBooks)) {
            session()->flash('message', 'Please select a student and at least one book.');
            return;
        }

        $this->validate([
            'returnDate' => 'required|date|after:today',
        ]);

        $student = Student::find($this->selectedStudentId);
        if ($student) {
            $books = Book::whereIn('id', $this->selectedBooks)->get();
            
            foreach ($books as $book) {
                $isAlreadyBorrowed = $book->students()
                    ->wherePivot('student_id', $student->id)
                    ->wherePivot('status', 'borrowed')
                    ->exists();

                if ($isAlreadyBorrowed) {
                    session()->flash('error', "Student already has '{$book->title}' borrowed.");
                    return;
                }

                if ($book->quantity <= 0) {
                    session()->flash('error', "Book '{$book->title}' is out of stock.");
                    return;
                }
                $book->decrement('quantity');
                $book->students()->attach($student->id, [
                    'borrow_date' => now()->format('Y-m-d'),
                    'return_date' => $this->returnDate,
                    'status' => 'borrowed',
                ]);
            }
            session()->flash('message', 'Books issued successfully!');
        } else {
            session()->flash('message', 'Student not found.');
        }

        $this->closeIssueModal();
        $this->selectedBooks = [];
        $this->selectedStudentId = null;
        $this->returnDate = null;
    }
}
