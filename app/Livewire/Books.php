<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use Illuminate\Validation\Rule;

class Books extends Component
{
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

    public function render()
    {
        return view('livewire.books', [
            'books' => Book::orderBy('id', 'desc')->paginate(50),
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
}
