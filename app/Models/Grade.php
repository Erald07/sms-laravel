<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'timetable_id', 'student_id', 'grade'
    ];

    public function subject()
    {
        return $this->belongsTo(Course::class);
    }

    public function timetable()
    {
        return $this->belongsTo(CourseTimetable::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
