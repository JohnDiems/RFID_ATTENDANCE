@extends('Layout.Admin')
@section('content')

<style>
    #FullName,
    #Gender,
    #EmailAddress,
    #Parent,
    #EmergencyAddress,#CompleteAddress{
        text-transform: lowercase;
    }
</style>

    <div class="content-wrapper">
        <div class="content-header">
            <div class=" justify-content-center align-items-center" >

            <!----------------------HANDLE ERROR VALIDATION---------------------------->
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif
            <!----------------------HANDLE ERROR VALIDATION---------------------------->

            <!----------------------STUDENTLIST EDIT FORM---------------------------->
            <div class="card" style="border: none;">

                <div class="card-body">
                <form action="{{ route('profile.update', $id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @error('photo')
                            <div class="alert alert-danger sm">{{ $message }}</div>
                        @enderror
                        <div class="col-md-4 py-5 text-center">
                                <div class="form-group edit">
                                    <input type="file" id="photo" name="photo" accept="image/jpeg, image/png, image/jpg," class="file-input">
                                    <label for="photo" id="uploadLabel" >
                                        <img id="upload" src="{{ asset($profiles->photo) }}" alt="Upload Image">
                                        <div class="text-center py-3" style="font-size: 20px; font-weight: 700;">Add Photo</div>
                                    </label>
                                </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group edit">
                                <label for="StudentRFID" >Student RFID</label>
                                <input type="text" id="StudentRFID" name="StudentRFID" placeholder="RFID code:" value="{{ $profiles->StudentRFID }}" required class="form-control form-control-lg" >
                            </div>

                            @error('FullName')
                                <div class="alert alert-danger sm">{{ $message }}</div>
                            @enderror
                            <div class="form-group edit">
                                <label for="FullName">Full Name</label>
                                <input type="text" id="FullName" name="FullName" placeholder="Last Name, First Name, Middle Name" value="{{ $profiles->FullName }}" required class="form-control form-control-lg">
                            </div>

                            @error('Gender')
                                <div class="alert alert-danger sm">{{ $message }}</div>
                            @enderror
                            <div class="form-group edit">
                                <label for="Gender">Gender</label>
                                <select id="Gender" name="Gender" required class="form-control form-control-lg">
                                    <option value="Male" {{ $profiles->Gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $profiles->Gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                             @error('Course')
                                    <div class="alert alert-danger sm">{{ $message }}</div>
                                @enderror
                            <div class="form-group edit">
                                <label for="Course">Section</label>
                                <select id="Course" name="Course" class="form-control form-control-lg">
                                    <option >{{ $profiles->Course }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @error('EmailAddress')
                                <div class="alert alert-danger sm">{{ $message }}</div>
                            @enderror
                            <div class="form-group edit">
                                <label for="EmailAddress">Email</label>
                                <input type="email" id="EmailAddress" name="EmailAddress" placeholder="Example@yahoo.com" value="{{ $profiles->EmailAddress }}" required class="form-control form-control-lg">
                            </div>

                            @error('ContactNumber')
                                <div class="alert alert-danger sm">{{ $message }}</div>
                            @enderror
                            <div class="form-group edit">
                                <label for="ContactNumber">Phone</label>
                                <input type="tel" id="ContactNumber" name="ContactNumber" placeholder="+63" value="{{ $profiles->ContactNumber }}" required class="form-control form-control-lg">
                            </div>

                            @error('CompleteAddress')
                                <div class="alert alert-danger sm">{{ $message }}</div>
                            @enderror
                            <div class="form-group edit">
                                <label for="CompleteAddress">Address</label>
                                <input type="text" id="CompleteAddress" name="CompleteAddress" placeholder="Complete Address" required class="form-control form-control-lg" value="{{ $profiles->CompleteAddress }}">
                            </div>

                            @error('YearLevel')
                                    <div class="alert alert-danger sm">{{ $message }}</div>
                                @enderror
                                <div class="form-group edit">
                                    <label for="YearLevel">GRADE</label>
                                    <select id="YearLevel" name="YearLevel" required class="form-control form-control-lg">
                                        <option disabled>Select Grade Level</option>
                                        <option value="GRADE 7" {{ $profiles->YearLevel == 'GRADE 7' ? 'selected' : $profiles->Course }}>GRADE 7</option>
                                        <option value="GRADE 8" {{ $profiles->YearLevel == 'GRADE 8' ? 'selected' : $profiles->Course }}>GRADE 8</option>
                                        <option value="GRADE 9" {{ $profiles->YearLevel == 'GRADE 9' ? 'selected' : $profiles->Course }}>GRADE 9</option>
                                        <option value="GRADE 10" {{ $profiles->YearLevel == 'GRADE 10' ? 'selected' : $profiles->Course }}>GRADE 10</option>
                                    </select>
                                </div>
                        </div>
                    </div>
                    {{-- <h4 class="text-center mt-3">STUDENT DEPARTMENT</h4>
                    <hr> --}}
                    {{-- <div class="row">
                        <div class="col-md-6">
                                @error('YearLevel')
                                    <div class="alert alert-danger sm">{{ $message }}</div>
                                @enderror
                                <div class="form-group edit">
                                    <label for="YearLevel">GRADE</label>
                                    <select id="YearLevel" name="YearLevel" required class="form-control form-control-lg">
                                        <option disabled>Select Grade Level</option>
                                        <option value="GRADE 7" {{ $profiles->YearLevel == 'GRADE 7' ? 'selected' : $profiles->Course }}>GRADE 7</option>
                                        <option value="GRADE 8" {{ $profiles->YearLevel == 'GRADE 8' ? 'selected' : $profiles->Course }}>GRADE 8</option>
                                        <option value="GRADE 9" {{ $profiles->YearLevel == 'GRADE 9' ? 'selected' : $profiles->Course }}>GRADE 9</option>
                                        <option value="GRADE 10" {{ $profiles->YearLevel == 'GRADE 10' ? 'selected' : $profiles->Course }}>GRADE 10</option>
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-6">
                            @error('Course')
                                    <div class="alert alert-danger sm">{{ $message }}</div>
                                @enderror
                            <div class="form-group edit">
                                <label for="Course">Section</label>
                                <select id="Course" name="Course" class="form-control form-control-lg">
                                    <option >{{ $profiles->Course }}</option>
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-4">
                            @error('Parent')
                                <div class="alert alert-danger sm">{{ $message }}</div>
                            @enderror
                            <div class="form-group edit">
                                <label for="Parent">Guardian Name</label>
                                <input type="text" id="Parent" name="Parent" placeholder="Guardian Name" value="{{ $profiles->Parent }}" required class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @error('EmergencyAddress')
                                <div class="alert alert-danger sm">{{ $message }}</div>
                            @enderror
                            <div class="form-group edit">
                                <label for="EmergencyAddress">Emergency Address</label>
                                <input type="text" id="EmergencyAddress" name="EmergencyAddress" placeholder="Emergency Address" value="{{ $profiles->EmergencyAddress }}" required class="form-control form-control-lg">
                            </div>
                        </div>
                        <div class="col-md-4">
                            @error('EmergencyNumber')
                                <div class="alert alert-danger sm">{{ $message }}</div>
                            @enderror
                            <div class="form-group edit">
                                <label for="EmergencyNumber">Emergency Number</label>
                                <input type="text" id="EmergencyNumber" name="EmergencyNumber" placeholder="Emergency Address" value="{{ $profiles->EmergencyNumber }}" required class="form-control form-control-lg">
                            </div>
                        </div>
                    </div><br>

                    {{-- <div class="form-group">
                        <div class="text-center">
                            <button type="submit" name="submit" class="btn btn-big btn-success" style="width: 200px;">UPDATE</button>
                        </div>
                    </div> --}}

                    <div class="pt-1 mt-2">
                        <button type="submit"  class="btn  btn-block text-white" style="background-color: #337AB7;"><span
                            class="h3">UPDATE</span></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
