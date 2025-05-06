<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            ['name' => 'B.E. Computer', 'color_code' => 'bg-yellow-50', 'text_color' => 'text-yellow-800'],
            ['name' => 'B.E. Civil', 'color_code' => 'bg-blue-50', 'text_color' => 'text-blue-700'],
            ['name' => 'B.E. Electronics, Communication & Automation', 'color_code' => 'bg-green-50', 'text_color' => 'text-green-700'],
        ]);
    }
}
