<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'teacher_id',
        'full_name',
        'date_of_birth',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    // Define relationship with Course
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }


    public function timetables()
    {
        return $this->hasManyThrough(CourseTimetable::class, Course::class, 'teacher_id', 'course_id');
    }
}
