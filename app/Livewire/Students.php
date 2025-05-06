<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Models\Student;
use App\Models\Department;

class Students extends Component
{
    use WithPagination;

    public $name, $email, $phone, $address, $library_id, $student_id, $department_id, $delete_id;
    public $isOpen = false;
    public $confirmingDelete = false;

    public function render()
    {
        return view('livewire.students', [
            'students' => Student::with('department')->orderBy('id', 'desc')->paginate(50),
            'departments' => Department::all(),
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
        $this->department_id = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->library_id = '';
    }

    public function store()
    {
        $this->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('students')->ignore($this->student_id),
            ],
            'library_id' => [
                'nullable',
                Rule::unique('students')->ignore($this->student_id)->whereNotNull('library_id'),
            ],
            'phone' => [
                'nullable',
                'numeric',
                'digits_between:10,15',
                Rule::unique('students')->ignore($this->student_id)->whereNotNull('phone'),
            ],
            'address' => 'nullable',
        ]);

        Student::updateOrCreate(
            [
                'id' => $this->student_id
            ],
            [
                'department_id' => $this->department_id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'library_id' => $this->library_id,
            ]
        );

        session()->flash('message', 
            $this->student_id ? 'Student Updated Successfully.' : 'Student Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $this->student_id = $id;
        $this->department_id = $student->department_id;
        $this->name = $student->name;
        $this->email = $student->email;
        $this->phone = $student->phone;
        $this->address = $student->address;
        $this->library_id = $student->library_id;
    
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->delete_id = $id;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->delete_id = null;
    }

    public function deleteConfirmed()
    {
        Student::find($this->delete_id)->delete();
        session()->flash('message', 'Student Deleted Successfully.');
        $this->cancelDelete();
    }
}
