<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'library_id',
        'phone',
        'email',
        'address',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_students', 'student_id', 'book_id');
    }
}

