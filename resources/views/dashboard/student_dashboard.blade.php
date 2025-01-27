
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
                            <h3 class="page-title">Welcome <?php echo auth()->user()->name ?></h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active">Student</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>All Courses</h6>
                                    <h3><?php echo count($studentCourses) ?></h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ URL::to('assets/img/icons/teacher-icon-01.svg') }}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>All Projects</h6>
                                    <h3>40/60</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{URL::to('assets/img/icons/teacher-icon-02.svg')}}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Test Attended</h6>
                                    <h3>30/50</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{URL::to('assets/img/icons/student-icon-01.svg')}}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Test Passed</h6>
                                    <h3>15/20</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{URL::to('assets/img/icons/student-icon-02.svg')}}" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="card-title">Upcoming Timetables</h5>
                                </div>
                                <table class="table table-center">
                                    <tbody>
                                        @php
                                            $hasUpcomingTimetable = false;
                                        @endphp
                                        @foreach ($timetables as $index => $timetable)
                                            <?php 
                                            $parsedDate = \Carbon\Carbon::createFromFormat('d-m-Y', $timetable['date']);
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
                                                            <b>{{$timetable['course_name']}}</b><br>
                                                            <p style="font-size: 16px; padding-bottom: 8px;">Room {{ $timetable['room'] }}</p>
                                                        </div>
                                                        <ul class="teacher-date-list">
                                                            <li>
                                                                <i class="fas fa-calendar-alt me-2"></i>
                                                                {{ \Carbon\Carbon::parse($timetable['date'])->format('D d-m-Y') }}
                                                            </li>
                                                            <li>|</li>
                                                            <li>
                                                                <i class="fas fa-clock me-2"></i>
                                                                {{ \Carbon\Carbon::parse($timetable['start'])->format('H:i') }} - 
                                                                {{ \Carbon\Carbon::parse($timetable['end'])->format('H:i') }}
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
                    <div class="row">
                        <div class="col-12 col-lg-12 col-xl-12 d-flex">
                            <div class="card flex-fill comman-shadow">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <canvas id="myLineChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-12 col-lg-12 col-xl-12 d-flex">
                            <div class="card flex-fill comman-shadow">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="card-title">Teaching History</h5>
                                    <ul class="chart-list-out student-ellips">
                                        <li class="star-menus"><a href="javascript:;"><i
                                                    class="fas fa-ellipsis-v"></i></a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="teaching-card">
                                        <ul class="steps-history">
                                            <li>Sep22</li>
                                            <li>Sep23</li>
                                            <li>Sep24</li>
                                        </ul>
                                        <ul class="activity-feed">
                                            <li class="feed-item d-flex align-items-center">
                                                <div class="dolor-activity">
                                                    <span class="feed-text1"><a>Mathematics</a></span>
                                                    <ul class="teacher-date-list">
                                                        <li><i class="fas fa-calendar-alt me-2"></i>September 5,
                                                            2022</li>
                                                        <li>|</li>
                                                        <li><i class="fas fa-clock me-2"></i>09:00 am - 10:00 am (60
                                                            Minutes)</li>
                                                    </ul>
                                                </div>
                                                <div class="activity-btns ms-auto">
                                                    <button type="submit" class="btn btn-info">In Progress</button>
                                                </div>
                                            </li>
                                            <li class="feed-item d-flex align-items-center">
                                                <div class="dolor-activity">
                                                    <span class="feed-text1"><a>Geography </a></span>
                                                    <ul class="teacher-date-list">
                                                        <li><i class="fas fa-calendar-alt me-2"></i>September 5,
                                                            2022</li>
                                                        <li>|</li>
                                                        <li><i class="fas fa-clock me-2"></i>09:00 am - 10:00 am (60
                                                            Minutes)</li>
                                                    </ul>
                                                </div>
                                                <div class="activity-btns complete ms-auto">
                                                    <button type="submit" class="btn btn-info">Completed</button>
                                                </div>
                                            </li>
                                            <li class="feed-item d-flex align-items-center">
                                                <div class="dolor-activity">
                                                    <span class="feed-text1"><a>Botony</a></span>
                                                    <ul class="teacher-date-list">
                                                        <li><i class="fas fa-calendar-alt me-2"></i>September 5,
                                                            2022</li>
                                                        <li>|</li>
                                                        <li><i class="fas fa-clock me-2"></i>09:00 am - 10:00 am (60
                                                            Minutes)</li>
                                                    </ul>
                                                </div>
                                                <div class="activity-btns ms-auto">
                                                    <button type="submit" class="btn btn-info">In Progress</button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                {{-- <div class="col-12 col-lg-12 col-xl-4 d-flex">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-body">
                            <div id="calendar-doctor" class="calendar-container"></div>
                            <div class="calendar-info calendar-info1">
                                <div class="up-come-header">
                                    <h2>Upcoming Events</h2>
                                    <span><a href="javascript:;"><i class="feather-plus"></i></a></span>
                                </div>
                                <div class="upcome-event-date">
                                    <h3>10 Jan</h3>
                                    <span><i class="fas fa-ellipsis-h"></i></span>
                                </div>
                                <div class="calendar-details">
                                    <p>08:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>Botony</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>08:00 - 09:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>09:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>Botony</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>09:00 - 10:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>10:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>Botony</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>10:00 - 11:00 am</span>
                                    </div>
                                </div>
                                <div class="upcome-event-date">
                                    <h3>10 Jan</h3>
                                    <span><i class="fas fa-ellipsis-h"></i></span>
                                </div>
                                <div class="calendar-details">
                                    <p>08:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>English</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>08:00 - 09:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>09:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>Mathematics </h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>09:00 - 10:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>10:00 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>History</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>10:00 - 11:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>11:00 am</p>
                                    <div class="calendar-box break-bg">
                                        <div class="calandar-event-name">
                                            <h4>Break</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>11:00 - 12:00 am</span>
                                    </div>
                                </div>
                                <div class="calendar-details">
                                    <p>11:30 am</p>
                                    <div class="calendar-box normal-bg">
                                        <div class="calandar-event-name">
                                            <h4>History</h4>
                                            <h5>Lorem ipsum sit amet</h5>
                                        </div>
                                        <span>11:30 - 12:00 am</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const timetables = @json($timetables);

    const filteredTimetables = timetables.filter(t => t.user_grade !== null);
    console.log(filteredTimetables)
    const grades = filteredTimetables.map(t => t.user_grade); 
    const courseLabels = filteredTimetables.map(t => `${t.date} / ${t.course_name}`);

    const data = {
        labels: courseLabels,
        datasets: [{
            label: 'Grade', 
            data: grades,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2, 
            fill: true,
            tension: 0.4
        }]
    };

    const optionss = {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Grade'
                }
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    };

    const ctx = document.getElementById('myLineChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',  // Line chart type
        data: data,   // Data for the chart
        options: optionss  // Chart options
    });
</script>
@endsection
