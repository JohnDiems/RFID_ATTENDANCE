@extends('Layout.Admin')

@section('content')
    <style>
        .dataTables_wrapper,
        .dataTables_filter input {
            margin-bottom: 0;
        }
    </style>
    <div class="content-wrapper">
        <div class="content-header">
            <div class=" justify-content-center align-items-center">
                <!----------------------HANDLE ERROR VALIDATION---------------------------->
                @if (session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif
                <!----------------------HANDLE ERROR VALIDATION---------------------------->
                <!-- Button trigger modal -->
                <div class="card-header py-3">
                    <button type="button" class="btn btn-bg text-white" style="background-color: #337AB7"
                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-person-plus-fill fa-lg"></i> Add Schedule
                    </button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Event Schedule</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('StoreSchedule') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <label>Event Content</label>
                                    <div class="form-group mb-4">
                                        <input type="text" name="EventSchedule" id="EventSchedule"
                                            class="form-control form-control-lg" placeholder="Event Content" required>
                                    </div>

                                    <label>Time in Start</label>
                                    <div class="form-group mb-4">
                                        <input type="time" name="EventTimein" id="EventTimein"
                                            class="form-control form-control-lg" >
                                    </div>

                                    <label>Time out Start</label>
                                    <div class="form-group mb-4">
                                        <input type="time" name="EventTimeout" id="EventTimeout"
                                            class="form-control form-control-lg" required>
                                    </div>

                                    <label>Event Date</label>
                                    <div class="form-group mb-4">
                                        <input type="date" name="EventDate" id="EventDate"
                                            class="form-control form-control-lg" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger btn-bg text-white"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-bg text-white"
                                        style="background-color: #337AB7">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mt-3 ">
                        <table id="filter" class="table table-hover  table-sm">
                            <thead class="text-black">
                                <tr>
                                    <th>ID</th>
                                    <th>EVENT CONTENT</th>
                                    <th>EVENT TIMEIN</th>
                                    <th>EVENT TIMEOUT</th>
                                    <th>EVENT DATE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            @foreach ($schedule as $schedule)
                                <tbody>
                                    <tr>
                                        <td>{{ $schedule->id }}</td>
                                        <td>{{ $schedule->EventSchedule }}</td>
                                        <td>{{ $schedule->EventTimein }}</td>
                                        <td>{{ $schedule->EventTimeout }}</td>
                                        <td>{{ $schedule->EventDate }}</td>

                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-dark">
                                                    <li>
                                                    <li>
                                                        <a class="dropdown-item text-center"
                                                            href="ScheduleEdit/{{ $schedule->id }}"
                                                            onclick="return confirm('Are you sure to UPDATE')">
                                                            <i class="bi bi-pencil icon-lg text-warning"></i><span
                                                                class="dots-vertical"> Update</span></a>
                                                    </li>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-center" href="deleteSchedule/{{ $schedule->id }}"
                                                            onclick="return confirm('Are you sure to DELETE?')">
                                                            <i class="bi bi-trash icon-lg text-danger"></i><span
                                                                class="dots-vertical"> delete</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                        <h2 class="text-center" style="font-weight: 800"><span class="AnnouncementInstruction"></span></h2>
                        <label>
                            <h4>Add Schedule:</h4>
                        </label>
                        <div class="AddSchedule:">
                            <ul>
                                <li>Navigate to the appropriate section.</li>
                                <li>Click on the "Add Schedule" button.</li>
                                <li>Ensure that the required fields such as title, event content, event date, event time in, and event time out are filled in.
                                </li>
                                <li>Only one schedule can be added.</li>
                                <li>Once added, the schedule will be visible to all users.</li>
                            </ul>
                        </div>

                        <div class="UpdateSchedule">
                            <label>
                                <h4>Update Schedule:</h4>
                            </label>
                            <ul>
                                <li>Find the existing schedule you wish to modify.</li>
                                <li>Click on the "Edit" button.</li>
                                <li>Make necessary changes to the title, event content, event date, event time in, or event time out.</li>
                                <li>Save the changes to update the schedule.</li>
                            </ul>
                        </div>

                        <div class="Deleteschedule">
                            <label>
                                <h4>Delete schedule:</h4>
                            </label>
                            <ul>
                                <li>Deleting an schedule is a permanent action. Please exercise caution.</li>
                                <li>To delete an schedule, locate the schedule you want to remove and click on the
                                    "Delete" button.</li>
                                <li>Confirm the Alert deletion.</li>
                            </ul>
                        </div>

                        <div class="ImportantNote">
                            <label>
                                <h4>Important Note:</h4>
                            </label>
                            <ul>
                                <li>Only authorized Admin have the ability to add, delete, or update announcements.</li>
                                <li>Ensure that the information provided in announcements is accurate and relevant.</li>
                                <li>Contact the administrator if you encounter any issues or need further assistance.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
