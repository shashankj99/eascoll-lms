<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use App\Models\Book;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function getTotalStudentsProperty()
    {
        return Student::count();
    }

    public function getTotalBooksProperty()
    {
        return Book::count();
    }

    public function getTotalBooksBorrowedProperty()
    {
        return DB::table('book_students')
            ->where('status', 'borrowed')
            ->count();
    }

    public function getTotalBooksReturnedProperty()
    {
        return DB::table('book_students')
            ->where('status', 'returned')
            ->count();
    }

    public function getOverdueBooksProperty()
    {
        return DB::table('book_students')
            ->where('status', 'borrowed')
            ->where('return_date', '<', now()->format('Y-m-d'))
            ->count();
    }

    public function getDueSoonBooksProperty()
    {
        return DB::table('book_students')
            ->where('status', 'borrowed')
            ->whereBetween('return_date', [
                now()->format('Y-m-d'),
                now()->addDays(3)->format('Y-m-d')
            ])
            ->count();
    }

    public function getMonthlyBorrowingDataProperty()
    {
        $currentYear = now()->year;

        // Fetch the records for the current year
        $data = DB::table('book_students')
            ->whereYear('borrow_date', $currentYear)  // Filter by current year
            ->get();

        // Initialize months (1 to 12) for the labels and counts
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->format('M');  // Short month name (e.g., Jan, Feb, etc.)
        });

        // Count the occurrences of each month
        $counts = $months->map(function ($month, $index) use ($data) {
            // Format the month to match the two-digit format (e.g., '01' for January)
            $monthNumber = str_pad($index + 1, 2, '0', STR_PAD_LEFT);

            // Count how many times this month occurs in the data
            $monthCount = $data->filter(function ($item) use ($monthNumber) {
                return Carbon::parse($item->borrow_date)->format('m') === $monthNumber;
            })->count();

            return $monthCount;
        });

        // Return the results in the format requested
        return [
            'labels' => $months->values(),
            'data' => $counts->values(),
        ];
    }

    public function getDepartmentWiseDataProperty()
    {
        return DB::table('book_students')
            ->join('students', 'book_students.student_id', '=', 'students.id')
            ->join('departments', 'students.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('COUNT(*) as count'))
            ->where('book_students.status', 'borrowed')
            ->groupBy('departments.name')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name ?: 'Unknown Department',
                    'count' => $item->count
                ];
            });
    }

    public function getBookCategoryDataProperty()
    {
        return DB::table('books')
            ->select('publisher', DB::raw('COUNT(*) as count'))
            ->groupBy('publisher')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'publisher' => $item->publisher ?: 'Unknown Publisher',
                    'count' => $item->count
                ];
            });
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'totalStudents' => $this->totalStudents,
            'totalBooks' => $this->totalBooks,
            'totalBooksBorrowed' => $this->totalBooksBorrowed,
            'totalBooksReturned' => $this->totalBooksReturned,
            'overdueBooks' => $this->overdueBooks,
            'dueSoonBooks' => $this->dueSoonBooks,
            'monthlyBorrowingData' => $this->monthlyBorrowingData,
            'departmentWiseData' => $this->departmentWiseData,
            'bookCategoryData' => $this->bookCategoryData,
        ]);
    }
}
