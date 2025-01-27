<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseParticipants;
use App\Models\CourseTimetable;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    /** home dashboard */
    public function index()
    {
        if (Session::get('role_name') === 'Teacher') {
            // return view('dashboard.teacher_dashboard');
            return $this->teacherDashboardIndex();
        } else if (Session::get('role_name') === 'Student') {
            return $this->studentDashboardIndex();
        }
        return view('dashboard.home');
    }

    /** profile user */
    public function userProfile()
    {
        return view('dashboard.profile');
    }

    /** teacher dashboard */
    public function teacherDashboardIndex()
    {
        $user = Auth::user();
        $courses = Course::where('teacher_id', $user->teacher->id)->get();
        
        $teacher = $user->teacher;
        $timetables = $teacher->timetables()->with('subject')->get();

        $timetablesWithCourseName = $timetables->map(function ($timetable) {
            $course = Course::where('id', $timetable->course_id)->first();
            return (object) [
                'id' => $timetable->id,
                'date' => $timetable->date,
                'start' => $timetable->start,
                'end' => $timetable->end,
                'room' => $timetable->room,
                'course_name' => $course->course_name
            ];
        });

        $students = DB::table('users')
            ->join('teachers', 'users.user_id', '=', 'teachers.teacher_id')
            ->join('courses', 'teachers.id', '=', 'courses.teacher_id')
            ->join('course_participants', 'courses.id', '=', 'course_participants.course_id')
            ->join('students', 'course_participants.student_id', '=', 'students.id')
            ->select('students.*')
            ->distinct()
            ->get();
        
        $participantsPerCourse = DB::table('courses')
            ->join('course_participants', 'courses.id', '=', 'course_participants.course_id')
            ->select('courses.course_name', DB::raw('COUNT(course_participants.student_id) as participant_count'))
            ->groupBy('courses.course_name')
            ->get();
        
        $participantsPerCourse = Course::withCount('participants')->get();

        $courseData = $participantsPerCourse->map(function($course) {
            // echo json_encode($course);exit;
            return [
                'course_name' => $course->course_name,
                'participants_count' => $course->participants_count,
            ];
        });                

        // echo json_encode($courseData);exit;
        return view('dashboard.teacher_dashboard', compact('courses', 'timetablesWithCourseName', 'students', 'courseData'));
    }

    /** student dashboard */
    public function studentDashboardIndex()
    {
        $studentCourses = DB::table('users')
            ->join('students', 'users.user_id', '=', 'students.student_user_id')
            ->join('course_participants', 'students.id', '=', 'course_participants.student_id')
            ->where('users.id', Auth::id())
            ->get();
        
        $user = Auth::user();
        
        $student = $user->student;

        $courseParticipants = $student->courseParticipants;

        $timetables = [];

        foreach ($courseParticipants as $courseParticipant) {
            foreach ($courseParticipant->course->courseTimetables as $timetable) {
                // $teacher = User::where('id', $timetable->teacher_id)->first();
                // echo json_encode($timetable);exit;
                $course = Course::where('id', $timetable->course_id)->first();

                $userGrade = Grade::where('timetable_id', $timetable->id)->first();
                $timetables[] = [
                    'id' => $timetable->id,
                    'room' => $timetable->room,
                    'date' => $timetable->date,
                    'start' => $timetable->start,
                    'end' => $timetable->end,
                    'course_name' => $course->course_name,
                    'user_grade' => $userGrade->grade ?? null
                ];
            }
        }

        // $flattenedTimetables = $timetables->flatten();
        // echo json_encode($timetables);exit;
        // $allTimetables = $timetables[0];
        return view('dashboard.student_dashboard', compact('studentCourses', 'timetables'));
    }
}
