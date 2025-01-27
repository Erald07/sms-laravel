
@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Courses</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ul>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('subject/list/page') }}">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <input type="text" class="form-control" name="search_id" placeholder="Search by ID..." value="{{ request('search_id') }}">
                </div>
                <div class="col-lg-3 col-md-6">
                    <input type="text" class="form-control" name="search_name" placeholder="Search by Name..." value="{{ request('search_name') }}">
                </div>
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Courses</h3>
                                </div>
                                @if (Session::get('role_name' !== 'Student'))
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="subjects.html" class="btn btn-outline-gray me-2 active"><i
                                            class="feather-list"></i></a>
                                    <a href="{{ route('subject/grid/page') }}" class="btn btn-outline-gray me-2"><i
                                            class="feather-grid"></i></a>
                                    <a href="#" class="btn btn-outline-primary me-2"><i
                                            class="fas fa-download"></i> Download</a>
                                    <a href="{{ route('subject/add/page') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="DataList" class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread"> 
                                    <tr>
                                        <th>Course ID</th>
                                        <th>Course Name</th>
                                        <th>Teacher</th>
                                        @if ($user->role_name !== 'Student') <th class="text-end">Action</th> @endif
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                    {{-- <?php print_r($course);exit; ?> --}}
                                    @if ($user->role_name === 'Student')
                                    <tr style="cursor: pointer;" onmouseover="this.style.backgroundColor='#f3f3f2'" onmouseout="this.style.backgroundColor=''">
                                        <td hidden class="id">{{ $course['id'] }}</td>
                                        <td>{{ $course['course_id'] }}</td>
                                        <td>{{ $course['course_name'] }}</td>
                                        <td>{{ $course['teacher']['full_name'] }}</td>
                                        @if (Session::get('role_name' !== 'Student')) 
                                        <td class="text-end">
                                            <div class="actions">
                                                <a href="{{ url('subject/edit/'.$course['id']) }}" class="btn btn-sm bg-danger-light">
                                                    <i class="far fa-edit me-2"></i>
                                                </a>
                                                {{-- <a class="btn btn-sm bg-danger-light delete" data-bs-toggle="modal" data-bs-target="#delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a> --}}
                                            </div>
                                        </td>
                                        @endif
                                        <td class="text-end">
                                            <div class="d-inline-flex justify-content-center">
                                                {{-- <a href="{{ url('subject/'. $course->id .'/participants/list/page') }}" class="btn btn-outline-primary me-2" style="background-color: #3d5ee1; color: white;">
                                                Participants
                                                </a> --}}
                                                <a href="{{ url('subject/'. $course['id'] .'/timetables/list/page') }}" class="btn btn-outline-primary me-2" style="background-color: #3d5ee1; color: white;">
                                                    Timetables
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif

                                    @if ($user->role_name === 'Teacher')
                                    <tr style="cursor: pointer;" onmouseover="this.style.backgroundColor='#f3f3f2'" onmouseout="this.style.backgroundColor=''">
                                        <td hidden class="id">{{ $course->id }}</td>
                                        <td>{{ $course->course_id }}</td>
                                        <td>{{ $course->course_name }}</td>
                                        <td>{{ $course->teacher->full_name }}</td>
                                        <td class="text-end">
                                            <div class="actions">
                                                <a href="{{ url('subject/edit/'.$course->id) }}" class="btn btn-sm bg-danger-light">
                                                    <i class="far fa-edit me-2"></i>
                                                </a>
                                                {{-- <a class="btn btn-sm bg-danger-light delete" data-bs-toggle="modal" data-bs-target="#delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a> --}}
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex justify-content-center">
                                                <a href="{{ url('subject/'. $course->id .'/participants/list/page') }}" class="btn btn-outline-primary me-2" style="background-color: #3d5ee1; color: white;">
                                                Participants
                                                </a>
                                                <a href="{{ url('subject/'. $course->id .'/timetables/list/page') }}" class="btn btn-outline-primary me-2" style="background-color: #3d5ee1; color: white;">
                                                    Timetables
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
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

        // document.addEventListener('DOMContentLoaded', function() {
        //     const searchIdInput = document.querySelector('input[name="search_id"]');
        //     const searchNameInput = document.querySelector('input[name="search_name"]');
        //     const tableRows = document.querySelectorAll('.table tbody tr');

        //     searchIdInput.addEventListener('input', filterCourses);
        //     searchNameInput.addEventListener('input', filterCourses);

        //     function filterCourses() {
        //         const searchId = searchIdInput.value.toLowerCase();
        //         const searchName = searchNameInput.value.toLowerCase();

        //         tableRows.forEach(row => {
        //             const courseId = row.querySelector('.course-id').textContent.toLowerCase();
        //             const courseName = row.querySelector('.course-name').textContent.toLowerCase();

        //             if (
        //                 (searchId && courseId.includes(searchId)) || 
        //                 (searchName && courseName.includes(searchName))
        //             ) {
        //                 row.style.display = '';
        //             } else {
        //                 row.style.display = 'none';
        //             }
        //         });
        //     }
        // });

    </script>
@endsection

@endsection
