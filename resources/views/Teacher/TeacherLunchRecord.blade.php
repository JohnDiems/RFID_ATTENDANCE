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
                    <label class="text-success" for="calendarFilter">Filter by Date:</label>
                    <input type="date" id="calendarFilter" class="form-control">
                </div>
                <div class="col-sm-6 mt-3 ">
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
            </div>
            <table id="filter2" class="table table-hover  table-sm">
                <thead class="text-white" style="background-color: #337AB7;">
                    <tr>
                        <th class="text-center">NAME</th>
                        <th class="text-center">GRADE LEVEL</th>
                        <th class="text-center">SECTION</th>
                        <th class="text-center">LUNCH START</th>
                        <th class="text-center">LUNCH DONE</th>
                        <th class="text-center">DATE</th>
                    </tr>
                </thead>
                </tbody>
                @foreach ($lunchs as $lunch)
                    <tr class="monitoring">
                        <td class="p-2">{{ $lunch->FullName }}</td>
                        <td class="p-2">{{ $lunch->YearLevel }}</td>
                        <td class="p-2">{{ $lunch->Course }}</td>
                        <td class="p-2 text-success">{{ $lunch->time_in }}</td>
                        <td class="p-2 text-success">{{ $lunch->time_out }}</td>
                        <td class="p-2">{{ $lunch->date }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
