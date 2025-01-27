<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'course_name',
        'teacher_id',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $getUser = self::orderBy('course_id', 'desc')->first();

            if ($getUser) {
                $latestID = intval(substr($getUser->course_id, 5));
                $nextID = $latestID + 1;
            } else {
                $nextID = 1;
            }
            $model->course_id = 'PRE' . sprintf("%03s", $nextID);
            while (self::where('course_id', $model->course_id)->exists()) {
                $nextID++;
                $model->course_id = 'PRE' . sprintf("%03s", $nextID);
            }
        });
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function courseTimetables()
    {
        return $this->hasMany(CourseTimetable::class);
    }

    public function participants()
    {
        return $this->belongsToMany(Student::class, 'course_participants', 'course_id', 'student_id');
    }
}
