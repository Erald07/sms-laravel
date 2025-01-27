
@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Welcome <?php echo auth()->user()->name ?>!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Teacher</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-sm-8 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Courses</h6>
                                    <h3><?php echo count($courses) ?></h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-01.svg') }}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Students</h6>
                                    <h3><?php echo count($students) ?></h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/dash-icon-01.svg') }}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Timetables</h6>
                                    <h3><?php echo count($timetablesWithCourseName) ?></h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-02.svg') }}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Upcoming Timetables Card -->
                <div class="col-12 col-md-6 col-lg-6 col-xl-6 d-flex">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="card-title">Upcoming Timetables</h5>
                                </div>
                            </div>
                        </div>
                        <div class="pt-3 pb-3">
                            <div class="table-responsive lesson">
                                <table class="table table-center">
                                    <tbody>
                                        @php
                                            $hasUpcomingTimetable = false;
                                        @endphp
                                        @foreach ($timetablesWithCourseName as $index => $timetable)
                                            <?php 
                                                $parsedDate = \Carbon\Carbon::createFromFormat('d-m-Y', $timetable->date);
                                                $today = \Carbon\Carbon::today();
                                            ?>
                                            @if ($parsedDate->gt($today))
                                            @php
                                                $hasUpcomingTimetable = true;
                                            @endphp
                                            <tr style="{{ $loop->last ? '' : 'border-bottom: 1px solid #ccc;' }}">
                                                <td>
                                                    <div class="date" style="margin-top: 8px;">
                                                        <div class="d-flex justify-content-between">
                                                            <b>{{$timetable->course_name}}</b><br>
                                                            <p style="font-size: 16px; padding-bottom: 8px;">Room {{ $timetable->room }}</p>
                                                        </div>
                                                        <ul class="teacher-date-list">
                                                            <li>
                                                                <i class="fas fa-calendar-alt me-2"></i>
                                                                {{ \Carbon\Carbon::parse($timetable->date)->format('D d-m-Y') }}
                                                            </li>
                                                            <li>|</li>
                                                            <li>
                                                                <i class="fas fa-clock me-2"></i>
                                                                {{ \Carbon\Carbon::parse($timetable->start)->format('H:i') }} - 
                                                                {{ \Carbon\Carbon::parse($timetable->end)->format('H:i') }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        @if (!$hasUpcomingTimetable)
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <p>No upcoming timetable</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Number of Students for Course Card -->
                <div class="col-12 col-md-6 col-lg-6 col-xl-6 d-flex">
                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <h5 class="card-title">Number of Students per Course</h5>
                                </div>
                                {{-- <div class="col-6">
                                    <ul class="chart-list-out">
                                        <li class="star-menus">
                                            <a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a>
                                        </li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="participantsChart" width="550" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Data passed from the server
        const labels = @json($courseData->pluck('course_name'));
        const data = @json($courseData->pluck('participants_count'));

        const ctx = document.getElementById('participantsChart').getContext('2d');
        const maxParticipants = Math.max(...data);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Participants',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Bar color
                    borderColor: 'rgba(75, 192, 192, 1)', // Border color
                    borderWidth: 0,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true, 
                        min: 0,
                        max: maxParticipants,
                        stepSize: 1,
                        ticks: {
                            callback: function(value) {
                                return Number.isInteger(value) ? value : ''; // Only show integers
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
