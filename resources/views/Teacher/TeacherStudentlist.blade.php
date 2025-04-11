@extends('Layout.teacher')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="table-responsive">
                <table id="filter" class="table table-hover">
                    <thead class="text-white" style="background-color: #337AB7;">
                        <tr>
                            <th class="text-center">PHOTO</th>
                            <th class="text-center">NAME</th>
                            <th class="text-center">RFID CODE</th>
                            <th class="text-center">GRADE LEVEL</th>
                            <th class="text-center">SECTION</th>
                            <th class="text-center">GENDER</th>
                            <th class="text-center">EMERGENCY ADDRESS</th>
                            <th class="text-center">EMERGENCY NUMBER</th>
                            <th class="text-center">EMAIL ADDRESS</th>
                            <th class="text-center">ACTION</th>
                        </tr>
                        </tbody>
                    </thead>
                    <!----------------------------DATATABLE------------------------------------>
                    @foreach ($profiles as $profiles)
                        <tr class="TeacherStudentlist" style="text-transform:lowercase">
                            <td>
                                <img src="{{ asset($profiles->photo) }}" alt="Profile Image"width="50" height="35"
                                    class="card-image1 circular-image"/>
                            </td>
                            <td>{{ $profiles->FullName }}</td>
                            <td>{{ $profiles->StudentRFID }}</td>
                            <td>{{ $profiles->YearLevel }}</td>
                            <td>{{ $profiles->Course }}</td>
                            <td>{{ $profiles->Gender }}</td>
                            <td>{{ $profiles->EmergencyAddress }}</td>
                            <td>{{ $profiles->EmergencyNumber }}</td>
                            <td>{{ $profiles->EmailAddress }}</td>
                            <td style="vertical-align: middle;">
                                <div class="btn-group">
                                    <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <!----------------------------DROP DOWN TOGGLE BUTTON SHOW------------------------------------>
                                        <li>
                                            <a class="dropdown-item text-center" href="#" data-bs-toggle="modal"
                                                data-bs-target="#View{{ $profiles->id }}">
                                                <i class="bi bi-eye icon-lg text-info"></i><span class="dots-vertical "
                                                    style="text-transform:uppercase;"> SHOW</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <!----------------------------MODAL MAIN CONTENT SHOW STUDENT INFORMATION------------------------------------>
                            <div class="modal fade" id="View{{ $profiles->id }}" tabindex="-1"
                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <!----------------------------TEACHER/INCLUDE/STUDENTLIST SHOW INFORMATION------------------------------------>
                                        <div class="modal-body">
                                            @include('Teacher.include.TeacherStudentlistShow')
                                        </div>
                                        <!----------------------------TEACHER/INCLUDE/STUDENTLIST SHOW INFORMATION------------------------------------>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
