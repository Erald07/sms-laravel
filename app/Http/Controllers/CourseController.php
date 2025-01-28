<?php

namespace App\Http\Controllers;

use App\Models\CourseParticipants;
use DB;
use App\Models\Course;
use App\Models\CourseTimetable;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CourseController extends Controller
{
    public function subjectAdd()
    {
        return view('subject.add-subject');
    }

    public function saveRecord(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'course_id' => 'required|string',
            'course_name' => 'required|string|max:255',
        ]);

        $user = User::where('id', Auth::id())->with('teacher')->first();
        try {
            $course = Course::create([
                'course_id' => $validated['course_id'],
                'course_name' => $validated['course_name'],
                'teacher_id' => $user->teacher->id,
            ]);

            Toastr::success('Has been add successfully :)','Success');
            return redirect()->back();
        } catch (AuthException $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('fail, Add new record  :)','Error');
            return redirect()->back();
        }
    }

    public function getCoursesByTeacher()
    {
        try {
            $user = Auth::user();
            if ($user->role_name === 'Student') {
                $student = $user->student;
                $courses = $student->courseParticipants()->with('course.teacher')->get();

                $coursesWithTeachers = $courses->map(function ($courseParticipant) {
                    // Get course and teacher details
                    $course = $courseParticipant->course;
                
                    // Check if the course exists before accessing it
                    if (!$course) {
                        // Handle the case where course is not available
                        return null; // Or log the error, depending on how you want to handle it
                    }
                
                    $teacher = $course->teacher;
                
                    // Check if the teacher exists before accessing it
                    if (!$teacher) {
                        // Handle the case where teacher is not available
                        return [
                            'id' => $course->id,
                            'course_id' => $course->course_id,
                            'course_name' => $course->course_name,
                            'teacher' => null,  // No teacher data if no teacher is found
                        ];
                    }
                
                    // Return an array with course details and teacher info
                    return [
                        'id' => $course->id,
                        'course_id' => $course->course_id,
                        'course_name' => $course->course_name,
                        'teacher' => [
                            'teacher_id' => $teacher->id,
                            'full_name' => $teacher->full_name,  // Assuming you have a full_name attribute in Teacher model
                        ],
                    ];
                });
                $courses = $coursesWithTeachers;
                // echo json_encode( $coursesWithTeachers);exit;
            } else {
                $courses = Course::where('teacher_id', $user->teacher->id)->with('teacher')->get();
            }
            // echo json_encode($courses);exit;
            return view('subject.list-subjects', compact('courses', 'user'));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            Toastr::error('Failed to fetch courses.', 'Error');
            return redirect()->back();
        }
    }

    public function subjectEdit($id)
    {
        $course = Course::where('id',$id)->with('teacher')->first();

        return view('subject.edit-subject',compact('course'));
    }

    /** update record */
    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $updateRecord = [
                'course_name' => $request->course_name,
            ];

            Course::where('id',$request->id)->update($updateRecord);
            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('Fail, update record:)','Error');
            return redirect()->back();
        }
    }

    public function getCoursesParticipants($courseId) {
        $course = Course::with('participants')->findOrFail($courseId);
        $participants = $course->participants;

        return view('subject.participants-list-page',compact('participants','course'));
    }

    public function subjectAddParticipant(Request $request, $courseId) {
        $course = Course::findOrFail($courseId);
        return view('subject.subject-add-participant',compact('course'));
    }

    public function subjectSaveParticipant(Request $request) {
        $request->validate([
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'date_of_birth' => 'required|string',
            'email'         => 'required|email',
            'phone_number'  => 'required',
            'password'        => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
        
            $dt        = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();
            
            $userName = $request->first_name . ' ' . $request->last_name;
            $user = User::where('name', '=', $userName)->where('email', '=', $request->email)->first();

            if (!$user) {
                User::create([
                    'name'      => $request->first_name . ' ' . $request->last_name,
                    'email'     => $request->email,
                    'join_date' => $todayDate,
                    'role_name' => 'Student',
                    'password'  => Hash::make($request->password),
                ]);
                $user_id = DB::table(table: 'users')->select('user_id')->orderBy('id','DESC')->first();
                
                $saveRecord = new Student;
                $saveRecord->student_user_id    = $user_id->user_id;
                $saveRecord->first_name         = $request->first_name;
                $saveRecord->last_name          = $request->last_name;
                $saveRecord->date_of_birth      = $request->date_of_birth;
                $saveRecord->phone_number       = $request->phone_number;
                $saveRecord->save();
                
                $student_id = DB::table(table: 'students')->select('id')->orderBy('id','DESC')->first();
            } else {
                $student_id = DB::table(table: 'students')->where('student_user_id', $user->user_id)->select('id')->orderBy('id','DESC')->first();
            }

            CourseParticipants::firstOrCreate([
                'course_id' => $request->id,
                'student_id' => $student_id->id,
            ]);
            Toastr::success('Has been add successfully :)','Success');
            return redirect()->route('subject/participants/list/page', ['courseId' => $request->id]);
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('fail, Add new record  :)','Error');
            return redirect()->back();
        }
    }

    public function subjectEditParticipant($courseId, $studentId) {

        $student = Student::with('user')->findOrFail($studentId);
        
        return view('subject.subject-edit-participant',compact('student', 'courseId'));
    }
    
    public function subjectUpdateParticipant(Request $request) {
        $request->validate([
            'id' => 'required|exists:students,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        DB::beginTransaction();
        try {

            DB::table('students')
            ->join('users', 'students.student_user_id', '=', 'users.user_id')
            ->where('students.id', $request->id)
            ->update([
                'users.name' => $request->first_name.''. $request->last_name,
                'users.email' => $request->email,
                'students.first_name' => $request->first_name,
                'students.last_name' => $request->last_name,
                'students.date_of_birth' => $request->date_of_birth,
                'students.phone_number' => $request->phone_number,
            ]);
            
            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('fail, update record  :)','Error');
            return redirect()->back();
        }
    }

    public function getCoursesTimetables($courseId) {
        $user = Auth::user();
        $course = Course::with('courseTimetables')->with('teacher')->findOrFail($courseId);
        $timetables = $course->courseTimetables;
        // echo json_encode ($course);exit;
        return view('subject.timetables-list-page',compact('timetables','course', 'user'));
    }

    public function subjectAddTimetable($courseId) {
        $course = Course::findOrFail($courseId);
        return view('subject.subject-add-timetable',compact('course'));
    }

    public function subjectSaveTimetable(Request $request) {
        $request->validate([
            'date'    => 'required|date',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
            'room'         => 'required|string',
        ]);

        try {

            $courseTimetable = new CourseTimetable;
            $courseTimetable->course_id   = $request->id;
            $courseTimetable->date    = $request->date;
            $courseTimetable->start= $request->start;
            $courseTimetable->end        = $request->end;
            $courseTimetable->room = $request->room;
            $courseTimetable->teacher_id = Auth::id();
            $courseTimetable->save();
            
            Toastr::success('Has been add successfully :)','Success');
            return redirect()->route('subject/timetables/list/page', ['courseId' => $request->id]);
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('fail, Add new record  :)','Error');
            return redirect()->back();
        }
    }

    public function subjectEditTimetable($courseId, $timetableId) {
        // $courseParticipants = CourseParticipants::where(['course_id' => $courseId])->with('participant')->get();

        $timetable = DB::table('course_participants')
            ->join('students', 'course_participants.student_id', '=', 'students.id') // Join participants with students
            ->join('course_timetables', 'course_participants.course_id', '=', 'course_timetables.course_id') // Join with course_timetables
            ->leftJoin('timetable_presence', function ($join) use ($timetableId) {
                $join->on('course_participants.student_id', '=', 'timetable_presence.student_id')
                    ->where('timetable_presence.timetable_id', '=', $timetableId); // Match by student_id and timetable_id
            })
            ->leftJoin('grades', function ($join) use ($timetableId) {
                $join->on('course_participants.student_id', '=', 'grades.student_id') // Match by student_id
                    ->on('course_participants.course_id', '=', 'grades.course_id') // Match by course_id
                    ->on('course_timetables.id', '=', 'grades.timetable_id'); // Match by timetable_id
            })
            ->where('course_participants.course_id', $courseId) // Filter by course ID
            ->where('course_timetables.id', $timetableId) // Filter by timetable ID
            ->select([
                'students.id as student_id',
                'students.first_name',
                'students.last_name',
                'timetable_presence.presence', // Presence value (null if not present)
                'grades.grade', // Grade value (null if not present)
            ])
            ->groupBy('students.id', 'students.first_name', 'students.last_name', 'timetable_presence.presence', 'grades.grade')
            ->get();
        // echo json_encode($timetable);exit;
        return view('subject.subject-edit-timetable',compact('timetable', 'timetableId', 'courseId'));

    }

    public function updatePresenceAndGrade(Request $request)
    {
        $courseId = $request->input('course_id');
        $timetableId = $request->input('timetable_id');

        if ($request->has('presence') && $request->has('grade')) {
            foreach ($request->presence as $participantId => $presence) {
                if (!is_null($presence) && $presence !== '') {
                    // Update or Insert Presence
                    DB::table('timetable_presence')->updateOrInsert(
                        [
                            'timetable_id' => $timetableId,
                            'student_id' => $participantId
                        ],
                        ['presence' => $presence]
                    );
    
                    // Only update grade if the presence value is set
                    $grade = $request->grade[$participantId] ?? null;
                    if (!is_null($grade) && $grade !== '') {
                        DB::table('grades')->updateOrInsert(
                            [
                                'course_id' => $courseId,
                                'timetable_id' => $timetableId,
                                'student_id' => $participantId
                            ],
                            ['grade' => $grade]
                        );
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Presence and grades updated successfully.');
    }

    public function subjectDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            Course::where('id',$request->id)->delete();
            DB::commit();
            Toastr::success('Deleted record successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Deleted record fail :)','Error');
            return redirect()->back();
        }
    }
}
