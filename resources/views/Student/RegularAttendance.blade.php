@extends('Layout.user')

@section('content')
    <div class="content-wrapper">     
        <div class="content-header"> 
             <!-- Add filter controls -->
             <div class="row mb-3">
                <div class="col-sm-6">
                    <label for="statusFilter">Filter by Status:</label>
                    <select id="statusFilter" class="form-control">
                        <option value="">All</option>
                        <option value="late">Late</option>
                        <option value="present">Present</option>
                    </select>
                </div>

                <div class="col-sm-6">
                    <label for="calendarFilter">Filter by Date:</label>
                    <input type="text" id="calendarFilter" class="form-control">
                </div>

            </div>
            <div class="row">
                <!------------DATATABLE #FILTER LOG STUDENT------------>
                <table id="Studentfilter"  class="table table-striped" >         
                    <thead class="text-white" style="background-color: #337AB7;">
                        <tr>
                            <th class="text-center">TIME IN</th>
                            <th class="text-center">TIME OUT</th>
                            <th class="text-center">DATE</th> 
                            <th class="text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>                       
                    <!------------FOREACH ATTENDANCES DISPLAY DATA TO DATATABLE------------>
                        @foreach ($attendances as $attendances)
                            <tr>
                                <td class="text-center p-2">{{ $attendances->time_in ? \Carbon\Carbon::parse($attendances->time_in)->format('g:i A') : '-' }}</td>
                                <td class="text-center p-2">{{ $attendances->time_out ? \Carbon\Carbon::parse($attendances->time_out)->format('g:i A') : '-' }}</td>
                                <td class="text-center p-2">{{ \Carbon\Carbon::parse($attendances->date)->format('M d, Y') }}</td>
                                <td class="text-center p-2"><span class="badge badge-{{ $attendances->Status == 'late' ? 'warning' : 'success' }}">{{ ucfirst($attendances->Status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>   
            </div>                                                                                                                         
        </div>
    </div>
@endsection