
@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Edit Course</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('subject/list/page') }}">Courses</a></li>
                        <li class="breadcrumb-item active">Edit Course</li>
                    </ul>
                </div>
            </div>
        </div>
        {{-- message --}}
        {!! Toastr::message() !!}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('subject/update') }}" method="POST">
                            @csrf
                            <input type="hidden" class="form-control" name="id" value="{{ $course->id }}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Course Details</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Course Name <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('course_name') is-invalid @enderror" name="course_name" placeholder="Enter Course Name" value="{{ $course->course_name }}">
                                        @error('course_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
