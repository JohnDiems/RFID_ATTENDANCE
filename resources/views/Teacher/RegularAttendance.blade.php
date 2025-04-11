@extends('Layout.teacher')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <!-- Add filter controls -->
            <div class="row mb-3">
              

                <div class="col-sm-6">
                    <label class="text-success" for="nameFilter">Filter by Name:</label>
                    <input type="text" id="nameFilter" class="form-control" placeholder="Student Name:">
                </div>
                <div class="col-sm-6">
                    <label class="text-success" for="statusFilter">Filter by Status:</label>
                    <select id="statusFilter" class="form-control">
                        <option value="">Select Status</option>
                        <option value="late">Late</option>
                        <option value="present">Present</option>
                    </select>
                </div>
                <div class="col-sm-6 mt-3">
                    <label class="text-success" for="GradeFilter">Filter by Grade:</label>
                    <select id="gradeFilter" class="form-control">
                        <option value="">Select Grades</option>
                        <option value="GRADE 7">Grade 7</option>
                        <option value="GRADE 8">Grade 8</option>
                        <option value="GRADE 9">Grade 9</option>
                        <option value="GRADE 10">Grade 10</option>
                    </select>
                </div>
                <div class="col-sm-6 mt-3">
                    <label class="text-success" for="SectionsFilter">Filter by Sections:</label>
                    <select id="sectionFilter" class="form-control">
                        <option value="">Select Sections</option>
                        <!-- Options will be dynamically added based on the selected grade level -->
                    </select>
                </div>
                <div class="col-sm-6 mt-3">
                    <label class="text-success" for="calendarFilter">Filter by Date:</label>
                    <input type="date" id="calendarFilter" class="form-control">
                </div>

            </div>
            <table id="filter2" class="table table-hover  table-sm">
                <thead class="text-white" style="background-color: #337AB7;">
                    <tr>
                        <th class="text-center">NAME</th>
                        <th class="text-center">GRADE LEVEL</th>
                        <th class="text-center">SECTION</th>
                        <th class="text-center">IN</th>
                        <th class="text-center">OUT</th>
                        <th class="text-center">DATE</th>
                        <th class="text-center">STATUS</th>
                    </tr>
                    </tbody>
                </thead>
                @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->FullName }}</td>
                        <td>{{ $attendance->YearLevel }}</td>
                        <td>{{ $attendance->Course }}</td>
                        <td>{{ $attendance->time_in }}</td>
                        <td>{{ $attendance->time_out }}</td>
                        <td>{{ $attendance->date }}</td>
                        <td>{{ $attendance->Status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
