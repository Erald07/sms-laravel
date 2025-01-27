<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetablePresence extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id', 'student_id', 'presence'
    ];

    public function timetable()
    {
        return $this->belongsTo(CourseTimetable::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
