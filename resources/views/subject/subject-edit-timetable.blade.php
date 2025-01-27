@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Edit Student</h3>
                        </div>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('subject/edit/participant/presence') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" class="form-control" name="timetable_id" value="{{ $timetableId }}" readonly>
                                <input type="hidden" class="form-control" name="course_id" value="{{ $courseId }}" readonly>
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title student-info">Participants</h5>
                                    </div>
                                    <div class="col-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Participant Name</th>
                                                    <th>Presence</th>
                                                    <th>Grade</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($timetable as $participant)
                                                    <tr>
                                                        <td style="align-content: center;">{{ $participant->first_name }} {{ $participant->last_name }}</td>
                                                        <td>
                                                            <select @if (Session::get('role_name') === 'Student') disabled @endif name="presence[{{ $participant->student_id }}]" class="form-control w-50">
                                                                <option value=""></option>
                                                                <option value="1" {{ $participant->presence == '1' ? 'selected' : '' }}>Present</option>
                                                                <option value="0" {{ $participant->presence == '0' ? 'selected' : '' }}>Not Present</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input @if (Session::get('role_name') === 'Student') disabled @endif type="number" name="grade[{{ $participant->student_id }}]" class="form-control w-50" 
                                                                   value="{{ $participant->grade }}" min="4" max="10">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if (Session::get('role_name') !== 'Student')
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
