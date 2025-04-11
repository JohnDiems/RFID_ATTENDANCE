@extends('Layout.Admin')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
          <!-- Add filter controls -->
          <div class="row mb-3">
            <div class="col-sm-6">
                <label class="text-success" for="nameFilter">Filter by Name:</label>
                <input type="text" id="nameFilter" class="form-control" placeholder="Name of Student">
            </div>
            <div class="col-sm-6">
              <label  class="text-success"for="calendarFilter">Filter by Date:</label>
              <input type="date" id="calendarFilter" class="form-control">
          </div>
            <div class="col-sm-6 mt-3">
                <label class="text-success" for="GradeFilter">Filter by Grade:</label>
                <select id="gradeFilter" class="form-control">
                    <option value="">All Grades</option>
                    <option value="GRADE 7">Grade 7</option>
                    <option value="GRADE 8">Grade 8</option>
                    <option value="GRADE 9">Grade 9</option>
                    <option value="GRADE 10">Grade 10</option>
                </select>
            </div>
            <div class="col-sm-6 mt-3">
                <label class="text-success" for="SectionsFilter">Filter by Sections:</label>
                <select id="sectionFilter" class="form-control">
                    <option value="">All Sections</option>
                    <!-- Options will be dynamically added based on the selected grade level -->
                </select>
            </div>
        </div>
            <table id="filter2" class="table table-hover  table-sm">
                <thead class="text-black">
                    <tr>
                        <th>NAME</th>
                        <th>GRADE LEVEL</th>
                        <th>SECTION</th>
                        <th>LUNCH IN</th>
                        <th>LUNCH OUT</th>
                        <th>DATE</th>
                    </tr>
                </thead>
                </tbody>
                    @foreach ($lunchs as $lunch)
                        <tr class="monitoring">
                            <td class="p-2">{{ $lunch->FullName }}</td>
                            <td class="p-2">{{ $lunch->YearLevel }}</td>
                            <td class="p-2">{{ $lunch->Course }}</td>
                            <td class="p-2">{{ $lunch->time_in }}</td>
                            <td class="p-2">{{ $lunch->time_out }}</td>
                            <td class="p-2">{{ $lunch->date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
