@extends('Layout.Admin')

@section('content')
    <style>
        buttons {
            padding: 0.6em 2em;
            border: none;
            outline: none;
            color: rgb(255, 255, 255);
            background: #111;
            cursor: pointer;
            position: relative;
            z-index: 0;
            border-radius: 10px;
        }

        buttons:before {
            content: "";
            background: linear-gradient(45deg,
                    #ff0000,
                    #ff7300,
                    #fffb00,
                    #48ff00,
                    #00ffd5,
                    #002bff,
                    #7a00ff,
                    #ff00c8,
                    #ff0000);
            position: absolute;
            top: -2px;
            left: -2px;
            background-size: 400%;
            z-index: -1;
            filter: blur(5px);
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            animation: glowingbn5 20s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            border-radius: 10px;
        }

        @keyframes glowingbn5 {
            0% {
                background-position: 0 0;
            }

            50% {
                background-position: 400% 0;
            }

            100% {
                background-position: 0 0;
            }
        }

        buttons:active {
            color: #000;
        }

        buttons:active:after {
            background: transparent;
        }

        buttons:hover:before {
            opacity: 1;
        }

        buttons:after {
            z-index: -1;
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: #191919;
            left: 0;
            top: 0;
            border-radius: 10px;
        }
    </style>
    <!--MASTER NEEDED-->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="row">
                <!-- Total Students Card -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $profilesCount }}</h3>
                            <p>Total Students</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('admin.profiles.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Today's Attendance Card -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $todayAttendanceCount }}</h3>
                            <p>Today's Attendance</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ route('admin.attendance.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Late Students Card -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $todayLateCount }}</h3>
                            <p>Late Today</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <a href="{{ route('admin.attendance.index', ['status' => 'late']) }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Active Schedules Card -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $activeScheduleCount }}</h3>
                            <p>Active Schedules</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="{{ route('admin.schedules.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
                            <h1 class="m-b-5 font-strong" style="color: #059669">{{ $profilesCount }}</h1>
                            <div class="m-b-5">New profile's</div><i class="fas fa-user-graduate fa-lg custom-icon1"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card br ">
                        <div class="card-body text-dark">
                            <h1 class="m-b-5 font-strong" style="color: #337AB7">{{ $UserCount }}</h1>
                            <div class="m-b-5">New user's</div><i class="fas fa-users fa-lg custom-icon2"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card br">
                        <div class="card-body text-dark">
                            <h1 class="m-b-5 font-strong" style="color: #F4B400">{{ $time_in }}</h1>
                            <div class="m-b-5">Total entry</div><i class="fas fa-sign-in-alt fa-lg custom-icon3"></i>
                        </div>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card br">
                        <div class="card-body text-dark">
                            <h1 class="m-b-5 font-strong" style="color: #DB4437">{{ $time_out }}</h1>
                            <div class="m-b-5">Total exit</div><i class="fas fa-sign-out-alt fa-lg custom-icon4"></i>
                        </div>

                    </div>
                </div>
            </div><br>


            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recent Profile's</h4>
                        </div>
                        <div class="card-body md-8">
                            <div class="table-responsive">
                                <table id="filterDashboard" class="table table-hover  table-sm" style="width: 100%">
                                    <thead class="text-black">
                                        <tr>
                                            <th>RFID CODE</th>
                                            <th>NAME</th>
                                            <th>GRADE LEVEL</th>
                                            <th>SECTION</th>
                                            <th>EMAIL</th>
                                            <th>CONTACT NUMBER</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($profiles as $profiles)
                                            <tr>
                                                <td style="color: #059669" class="p-2">{{ $profiles->StudentRFID }}</td>
                                                <td class="p-2">{{ $profiles->FullName }}</td>
                                                <td class="p-2">{{ $profiles->YearLevel }}</td>
                                                <td class="p-2">{{ $profiles->Course }}</td>
                                                <td class="p-2">{{ $profiles->EmailAddress }}</td>
                                                <td class="p-2">{{ $profiles->ContactNumber }}</td>
                                                <td class="p-2" style="position: relative;">
                                                    <div class="status-dot">
                                                        @if ($profiles->Status == 1)
                                                            <span class="dot de" data-status="Active"
                                                                data-profile-id="{{ $profiles->id }}"></span>
                                                            <span class="status-text">Active</span>
                                                        @else
                                                            <span class="dot pe" data-status="Inactive"
                                                                data-profile-id="{{ $profiles->id }}"></span>
                                                            <span class="status-text">Inactive</span>
                                                        @endif
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
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Recent User's</h4>
                        </div>
                        <div class="card-body mb-4 Dashboard">
                            <table id="recentUsersFilter"class="table table-hover  table-sm">
                                <thead class="text-black">
                                    <tr>
                                        <th class="text-center">NAME</th>
                                        <th class="text-center">ROLE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $User)
                                        <tr class="RecentUser">
                                            <td class="text-center p-2">{{ $User->name }}</td>
                                            <td class="text-center text-success p-2">{{ $User->role }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>User and Profile Monitor</h4>
                            <div id="Download"></div>
                        </div>
                        <div class="card-body mb-4">
                            <div id="line_top_x"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Time In-Out Monitor</h4>
                            <div id="Download"></div>
                        </div>
                        <div class="card-body mb-4">
                            <div id="donutchart" style="height: 530px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
        <script>
            google.charts.load('current', {
                'packages': ['line', 'corechart']
            });

            google.charts.setOnLoadCallback(drawPieChart);
            google.charts.setOnLoadCallback(drawLineChart);

            function drawPieChart() {
                var Timein = {{ $time_in }};
                var Timeout = {{ $time_out }};

                var dataPie = google.visualization.arrayToDataTable([
                    ['Task', 'Current Month'],
                    ['Time in', Timein],
                    ['Time out', Timeout],
                ]);

                var optionsPie = {
                    title: 'Daily Time in, Time out',
                    pieHole: 0.4,
                };

                var chartPie = new google.visualization.PieChart(document.getElementById('donutchart'));
                chartPie.draw(dataPie, optionsPie);

                var downloadBtn = document.createElement('buttons');
                downloadBtn.innerText = 'Download Data';
                downloadBtn.addEventListener('click', function() {
                    downloadCSV(dataPie);
                });
                /// Append the button to your container
                document.getElementById('donutchart').appendChild(downloadBtn);

                // Function to download CSV
                function downloadCSV(dataTable) {
                    var csvData = google.visualization.dataTableToCsv(dataTable);

                    // Add column names to CSV
                    var columnNames = [];
                    for (var i = 0; i < dataTable.getNumberOfColumns(); i++) {
                        columnNames.push(dataTable.getColumnLabel(i));
                    }
                    var csv = columnNames.join(',') + '\n' + csvData;

                    // Create a Blob and create a download link
                    var blob = new Blob([csv], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    var link = document.createElement('a');
                    var url = URL.createObjectURL(blob);

                    link.setAttribute('href', url);
                    link.setAttribute('download', 'chart_data.csv');
                    document.body.appendChild(link);

                    // Trigger the download
                    link.click();

                    // Cleanup
                    document.body.removeChild(link);
                }
            }


            function drawLineChart() {
                var CountUser = {{ $UserCount }};
                var CountProfile = {{ $profilesCount }};

                // Get the current month dynamically
                var currentMonth = new Date().toLocaleString('default', {
                    month: 'long'
                });

                // Get the number of days in the current month
                var daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();

                // Create an array to store data for each day in the current month
                var currentMonthData = Array.from({
                    length: daysInMonth
                }, (_, day) => [`${currentMonth} - ${day + 1}`, CountUser, CountProfile]);

                // Create an array for the next month with placeholder data
                var nextMonthData = Array.from({
                    length: daysInMonth
                }, (_, day) => [`Next Month - ${day + 1}`, 0, 0]);

                // Combine the data arrays for current and next month
                var combinedData = [
                    ['Month', 'New Users', 'New Profiles'], ...currentMonthData, ...nextMonthData
                ];

                var data = google.visualization.arrayToDataTable(combinedData);

                var options = {
                    height: 505,
                    hAxis: {
                        title: 'Month',
                        titleTextStyle: {
                            italic: false
                        },
                        slantedText: false
                    }
                };

                // Draw the chart
                var chart = new google.charts.Line(document.getElementById('line_top_x'));
                chart.draw(data, google.charts.Line.convertOptions(options));

                // download btn
                var downloadBtn = document.createElement('buttons');
                downloadBtn.innerText = 'Download Data';
                downloadBtn.addEventListener('click', function() {
                    downloadCSV(data);
                });

                // Append the button to your container
                document.getElementById('line_top_x').appendChild(downloadBtn);

                // Function to download CSV
                function downloadCSV(dataTable) {
                    var csvData = google.visualization.dataTableToCsv(dataTable);

                    // Add column names to CSV
                    var columnNames = [];
                    for (var i = 0; i < dataTable.getNumberOfColumns(); i++) {
                        columnNames.push(dataTable.getColumnLabel(i));
                    }
                    var csv = columnNames.join(',') + '\n' + csvData;

                    // Create a Blob and create a download link
                    var blob = new Blob([csv], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    var link = document.createElement('a');
                    var url = URL.createObjectURL(blob);

                    link.setAttribute('href', url);
                    link.setAttribute('download', 'chart_data.csv');
                    document.body.appendChild(link);

                    // Trigger the download
                    link.click();

                    // Cleanup
                    document.body.removeChild(link);
                }
            }
        </script>
