<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'isbn',
        'quantity',
        'description',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'book_students', 'book_id', 'student_id');
    }
}
