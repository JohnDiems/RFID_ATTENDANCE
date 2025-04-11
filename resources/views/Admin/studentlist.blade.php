@extends('Layout.Admin')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <!---------------------STUDENT FORM REQUEST STORED DATA---------------------------->
            <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- MODAL MAIN CONTENT STUDENT DATA -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="text-center mt-2">
                                <!-- HEADEER STUDENT PROFILES -->
                                <div class="modal-header">
                                    <h4 class="modal-title fs-3 text-center" id="staticBackdropLabel"
                                        style="color: #198754; margin-left: 260px;">STUDENT PROFILES</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <!-- IMAGE STUDENT PROFILES -->
                                            <div class="upload">
                                                <input type="file" id="photo" name="photo"
                                                    accept="image/jpeg, image/png, image/jpg," class="file-input">
                                                <label for="photo" id="uploadLabel">
                                                    <img id="upload" src="{{ asset('images/picture.png') }}"
                                                        alt="Upload Image">
                                                    <div class="small mb-3 photo">Add Photo</div>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- STUDENT RFID STUDENT PROFILES -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="StudentRFID" class="student-profiles">Student RFID</label>
                                                <input type="text" id="StudentRFID" name="StudentRFID"
                                                    placeholder="RFID code" required class="form-control">
                                            </div>
                                        </div>
                                        <!-- FULL NAME STUDENT PROFILES -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="FullName" class="student-profiles">Full Name</label>
                                                <input type="text" id="FullName" name="FullName"
                                                    placeholder="Last Name, First Name, Middle Name" required
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- GENDER STUDENT PROFILES -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="Gender" class="student-profiles">Gender</label>
                                                <select id="Gender" name="Gender" required class="form-control">
                                                    <option selected disabled>Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- EMAIL STUDENT PROFILES -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="EmailAddress" class="student-profiles">Your Email</label>
                                                <input type="email" id="EmailAddress" name="EmailAddress"
                                                    placeholder="Email" required class="form-control">
                                            </div>
                                        </div>
                                        <!-- PHONE STUDENT PROFILES -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ContactNumber" class="student-profiles">Your Phone</label>
                                                <input type="tel" id="ContactNumber" name="ContactNumber" value="+63"
                                                    required class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- ADDRESS STUDENT PROFILES -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="CompleteAddress" class="student-profiles">Your Address</label>
                                                <textarea id="CompleteAddress" name="CompleteAddress" rows="2" placeholder="Street Address" required
                                                    class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="modal-title fs-3 text-center" id="staticBackdropLabel"
                                        style="color: #198754">STUDENT DEPARTMENTS</h4>
                                    <hr>
                                    <div class="row">
                                        <!-- GRADE STUDENT PROFILES -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="YearLevel" class="student-profiles">GRADE</label>
                                                <select id="YearLevel" name="YearLevel" required class="form-control">
                                                    <option selected>Select Grade Level</option>
                                                    <option value="GRADE 7">GRADE 7</option>
                                                    <option value="GRADE 8">GRADE 8</option>
                                                    <option value="GRADE 9">GRADE 9</option>
                                                    <option value="GRADE 10">GRADE 10</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- SECTION STUDENT PROFILES -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Course" class="student-profiles">Your Section</label>
                                                <select id="Course" name="Course" required class="form-control">
                                                    <option selected disabled>Select Section</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <h4 class="modal-title fs-3 text-center" id="staticBackdropLabel"
                                        style="color: #198754">EMERGENCY CONTACT</h4>
                                    <hr>

                                    <div class="row">
                                        <!-- PARENT/GUARDIAN STUDENT PROFILES -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="Parent" class="student-profiles">Parent/Guardian
                                                    Name</label>
                                                <input type="text" id="Parent" name="Parent" placeholder="Name"
                                                    required class="form-control">
                                            </div>
                                        </div>
                                        <!-- EMERGENCY ADDRESS STUDENT PROFILES -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="EmergencyAddress" class="student-profiles">Emergency
                                                    Address</label>
                                                <input type="text" id="EmergencyAddress" name="EmergencyAddress"
                                                    placeholder="Address" required class="form-control">
                                            </div>
                                        </div>
                                        <!-- EMERGENCY NUMBER STUDENT PROFILES -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="EmergencyNumber" class="student-profiles">Emergency
                                                    Number</label>
                                                <input type="tel" id="EmergencyNumber" name="EmergencyNumber"
                                                    value="+63" required class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- REGISTER/CLOSE STUDENT PROFILES -->
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                                <button type="submit" id="submit" name="submit" class="btn  btn-block text-white"
                                    style="background-color: #337AB7;">Register</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!----------------------HANDLE SUCCESS VALIDATION---------------------------->

            @if (session('success'))
                <div id="studentProfile" class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <!----------------------HANDLE SUCCESS VALIDATION---------------------------->



            <!----------------------HANDLE ERROR VALIDATION---------------------------->
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div id="studentProfileError" class="alert alert-danger text-center" role="alert">
                        {{ $error }}</div>
                @endforeach
            @endif
            <!----------------------HANDLE ERROR VALIDATION---------------------------->



            <!--------------------------BUTTON TO TRIGGER MODAL------------------------------->
            <div class="card-header py-3 align-items-center">
                <!-- Add Profile Button -->
                <button type="button" class="btn  text-white" style="background-color: #337AB7"
                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="bi bi-person-plus-fill fa-lg"></i> Add Profile
                </button>

                <!-- Export Button -->
                <a href="{{route('ImportUser')}}" class="btn btn-bg text-white" style="background-color: #198754">
                    <i class="bi bi-box-arrow-up fa-lg"></i> Export to Excel CSV
                </a>

                <!-- Import Form -->
                <form action="{{ route('UploadUser') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mt-4">
                        <div class="custom-file">
                            <input type="file" name="file" accept=".xlsx, .xls, .csv" class="form-control form-control-lg custom-file-input">
                            <label class="custom-file-label" for="excel">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-bg text-white" style="background-color: #198754">
                                <i class="bi bi-box-arrow-down fa-lg"></i> Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>



            <!-------------TABLE TABLE-PRIMARY STUDENT LIST------------------------------------->
            <div class="row">
                <div class="col mt-3 ">
                    <div class="card-body table-responsive">
                        <!----------------------------DATATABLE #FILDER DATATABLE BORDER----------------------------------->
                        <table id="filter" class="table table-hover studentlist table-sm ">
                            <thead class="text-dark" >
                                <!----------------------------DATATABLE THEAD----------------------------------->
                                <tr>
                                    <th>PHOTO</th>
                                    <th>NAME</th>
                                    <th>RFID CODE</th>
                                    <th>GENDER</th>
                                    <th>CONTACT NUMBER</th>
                                    <th>ADDRESS</th>
                                    <th>EMAIL ADDRESS</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!----------------------------PROFILES INFORMATION DISPLAY TO DATATABLE----------------------------------->
                                @foreach ($profiles as $profiles)
                                    <tr class="studentlist vertical">
                                        <td>
                                            <img src="{{ asset($profiles->photo) }}" alt="Profile Image" width= "50"
                                                height="50" class="card-image1 circular-image">
                                        </td>
                                        <td>{{ $profiles->FullName }}</td>
                                        <td>{{ $profiles->StudentRFID }}</td>
                                        <td>{{ $profiles->Gender }}</td>
                                        <td>{{ $profiles->ContactNumber }}</td>
                                        <td>{{ $profiles->CompleteAddress }}</td>
                                        <td>{{ $profiles->EmailAddress }}</td>
                                        <!---------ACTIVE/INACTIVE---------->
                                        <td scope="col" width="70px" style="position: relative;">
                                            <div class="status-dot text-center">
                                                <!----------------------------ACTIVE BUTTON----------------------------------->
                                                @if ($profiles->Status == 1)
                                                    <span class="dot de" data-status="Active"
                                                        data-profile-id="{{ $profiles->id }}"></span>
                                                    <span class="status-text">Active</span>
                                                    <!----------------------------INACTIVE BUTTON------------------------------------>
                                                @else
                                                    <span class="dot pe" data-status="Inactive"
                                                        data-profile-id="{{ $profiles->id }}"></span>
                                                    <span class="status-text">Inactive</span>
                                                @endif
                                            </div>
                                        </td>
                                        <!----------------------------DROP DOWN TOGGLE BUTTON SHOW/ UPDATE------------------------------------>
                                        <td style="vertical-align: middle;">
                                            <div class="btn-group">
                                                <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-dark">
                                                    <!----------------------------DROP DOWN TOGGLE BUTTON SHOW------------------------------------>
                                                    <li>
                                                        <a class="dropdown-item text-center" href="#"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#View{{ $profiles->id }}">
                                                            <i class="bi bi-eye icon-lg text-info"></i><span
                                                                class="dots-vertical"> Show</span></a>
                                                    </li>
                                                    <!----------------------------DROP DOWN TOGGLE BUTTON UPDATE------------------------------------>
                                                    <li>
                                                        <a class="dropdown-item text-center"
                                                            href="ProfileEdit/{{ $profiles->id }}"
                                                            onclick="return confirm('Are you sure to UPDATE')">
                                                            <i class="bi bi-pencil icon-lg text-warning"></i><span
                                                                class="dots-vertical"> Update</span></a>
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
                                                    <!----------------------------ADMIN/INCLUDE/STUDENTLIST SHOW INFORMATION------------------------------------>
                                                    <div class="modal-body">
                                                        @include('Admin.include.StudentlistShow')
                                                    </div>
                                                    <!----------------------------ADMIN/INCLUDE/STUDENTLIST SHOW INFORMATION------------------------------------>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
