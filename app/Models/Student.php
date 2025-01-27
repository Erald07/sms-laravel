<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'phone_number',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_participants', 'student_id', 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_user_id', 'user_id');
    }

    public function courseParticipants()
{
    return $this->hasMany(CourseParticipants::class, 'student_id');
}
} 
