
@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add Timetable</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="timetables.html">Timetables</a></li>
                        <li class="breadcrumb-item active">Add Timetable</li>
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
                        <form action="{{ route('subject/save/timetable') }}" method="POST">
                            @csrf
                            <input type="hidden" class="form-control" name="id" value="{{ $course->id }}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Timetable Details</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Date <span class="login-danger">*</span></label>
                                        <input class="form-control datetimepicker @error('date') is-invalid @enderror" name="date" type="text" placeholder="DD-MM-YYYY" value="{{ old('date') }}">
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 local-forms">
                                    <div class="form-group">
                                        <label>Start <span class="login-danger">*</span></label>
                                        <input type="time" class="form-control @error('start') is-invalid @enderror" name="start" value="{{ old('start') }}">
                                        @error('start')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 local-forms">
                                    <div class="form-group">
                                        <label>End <span class="login-danger">*</span></label>
                                        <input type="time" class="form-control @error('end') is-invalid @enderror" name="end" value="{{ old('end') }}">
                                        @error('end')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 local-forms">
                                    <div class="form-group">
                                        <label>Room <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control @error('room') is-invalid @enderror" name="room" placeholder="Room" value="{{ old('room') }}">
                                        @error('room')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="course-submit">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
