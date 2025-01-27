<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;

class TeacherController extends Controller
{
    /** add teacher page */
    public function teacherAdd()
    {
        return view('teacher.add-teacher');
    }

    /** teacher list */
    public function teacherList()
    {
        $listTeacher = DB::table('users')
            ->join('teachers','teachers.teacher_id','users.user_id')
            ->select('users.user_id', 'users.email', 'users.name','users.avatar','teachers.id','teachers.date_of_birth' ,'teachers.country')
            ->get();
        return view('teacher.list-teachers',compact('listTeacher'));
    }

    /** teacher Grid */
    public function teacherGrid()
    {
        $teacherGrid = Teacher::all();
        return view('teacher.teachers-grid',compact('teacherGrid'));
    }

    /** save record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'full_name'       => 'required|string',
            'date_of_birth'   => 'required|string',
            'email'           => 'required|string',
            'password'        => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'country'         => 'required|string',
        ]);

        try {
        
            $dt        = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();
            
                 
            User::create([
                'name'      => $request->full_name,
                'email'     => $request->email,
                'join_date' => $todayDate,
                'role_name' => 'Teacher',
                'password'  => Hash::make($request->password),
            ]);
            $user_id = DB::table('users')->select('user_id')->orderBy('id','DESC')->first();
            
            $saveRecord = new Teacher;
            $saveRecord->teacher_id    = $user_id->user_id;
            $saveRecord->full_name     = $request->full_name;
            $saveRecord->date_of_birth = $request->date_of_birth;
            $saveRecord->country       = $request->country;
            $saveRecord->save();
   
            Toastr::success('Has been add successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('fail, Add new record  :)','Error');
            return redirect()->back();
        }
    }

    /** edit record */
    public function editRecord($id)
    {
        $teacher = Teacher::where('id',$id)->with('user')->first();

        return view('teacher.edit-teacher',compact('teacher'));
    }

    /** update record teacher */
    public function updateRecordTeacher(Request $request)
    {
        DB::beginTransaction();
        try {

            $updateRecord = [
                'full_name'     => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'country'      => $request->country,
            ];
            Teacher::where('id',$request->id)->update($updateRecord);
            
            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('fail, update record  :)','Error');
            return redirect()->back();
        }
    }

    /** delete record */
    public function teacherDelete(Request $request)
    {
        DB::beginTransaction();
        try {

            Teacher::destroy($request->id);
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
