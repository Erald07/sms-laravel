
@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Timetabes for {{ $course->course_name }}</h3>
                </div>
            </div>
        </div>

        <div class="student-group-form">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by ID ...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Name ...">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Phone ...">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="search-student-btn">
                        <button type="btn" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Timetables </h3>
                                </div>
                                @if (Session::get('role_name') !== 'Student')
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    {{-- <a href="subjects.html" class="btn btn-outline-gray me-2 active"><i
                                            class="feather-list"></i></a> --}}
                                    {{-- <a href="{{ route('subject/grid/page') }}" class="btn btn-outline-gray me-2"><i
                                            class="feather-grid"></i></a> --}}
                                    <a href="{{ url('subject/'.$course->id.'/add/timetable') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="DataList" class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread"> 
                                    <tr>
                                        <th>Date</th>
                                        <th>Teacher</th>
                                        <th>Room</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timetables as $timetable)
                                    <tr style="cursor: pointer;" onmouseover="this.style.backgroundColor='#f3f3f2'" onmouseout="this.style.backgroundColor=''">
                                        <td hidden class="id">{{ $timetable->id }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($timetable->date)->format('D d-m-Y') }} <br>
                                            {{ \Carbon\Carbon::parse($timetable->start)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($timetable->end)->format('H:i') }}
                                        </td>
                                        <td>{{ $course->teacher->full_name }}</td>
                                        <td>{{ $timetable->room }}</td>
                                        <td class="text-end">
                                            <div class="actions">
                                                <a href="{{ url('subject/'.$course->id.'/edit/timetable/'.$timetable->id) }}" class="btn btn-sm bg-danger-light">
                                                    <i class="far fa-edit me-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- model teacher delete --}}
<div class="modal fade contentmodal" id="teacherDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content doctor-profile">
            <div class="modal-header pb-0 border-bottom-0  justify-content-end">
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i
                    class="feather-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('teacher/delete') }}" method="POST">
                    @csrf
                    <div class="delete-wrap text-center">
                        <div class="del-icon">
                            <i class="feather-x-circle"></i>
                        </div>
                        <input type="hidden" name="id" class="e_id" value="">
                        <h2>Sure you want to delete</h2>
                        <div class="submit-section">
                            <button type="submit" class="btn btn-success me-2">Yes</button>
                            <a class="btn btn-danger" data-bs-dismiss="modal">No</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('script')
    {{-- delete js --}}
    <script>
        $(document).on('click','.teacher_delete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>
@endsection

@endsection
