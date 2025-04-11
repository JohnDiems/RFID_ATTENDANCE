@extends('Layout.Admin')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="col-sm-2">
                <form action="{{ route('Admin.register') }}" method="POST">
                @csrf
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Adviser Registration</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-4">
                                {{-- <label class="form-group edit" for="name">Name:</label> --}}
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name:"   required>
                            </div>
                            <div class="form-group mb-4">
                                {{-- <label class="form-group edit" for="username">Username:</label> --}}
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username:"   required>
                            </div>
                            <div class="form-group mb-4">
                                {{-- <label class="form-group edit" for="password">Password:</label> --}}
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password:"    required>
                            </div>
                            <div class="form-group mb-4">
                                {{-- <label class="form-group edit" for="role">Role:</label> --}}
                                <select class="form-control" id="role" name="role"  required>
                                {{-- <option>ROLE:</option>
                                <option value="admin">ADMIN</option> --}}
                                <option value="teacher">Adviser</option>
                                </select>
                            </div><br>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger btn-bg text-white" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-bg text-white" style="background-color: #337AB7">Save</button>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
            </div><br>



            <!----------------------HANDLE ERROR VALIDATION---------------------------->
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger text-center" role="alert">{{$error}}</div>
                @endforeach
            @endif
            <!----------------------HANDLE ERROR VALIDATION---------------------------->

            <!-- Button trigger modal -->
                <div class="card-header">
                    <button type="button" class="btn btn-bg text-white" style="background-color: #337AB7"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="bi bi-person-plus-fill fa-lg"></i> Add Adviser Account
                    </button>
                </div><br>

                <div class="role">
                    <label class="text-success" for="roleFilter">Select by Role:</label>
                    <select id="roleFilter" class="form-control">
                        <option value="all">Select Role:</option>
                        <option value="Admin">Admin</option>
                        <option value="Teacher">Teacher</option>
                        <option value="User">User</option>
                    </select>
                </div>







            <div class="row">
                <div class="col mt-3 ">
                    <table id="filter" class="table table-striped" >
                        <thead class="text-black">
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>USERNAME</th>
                                <th>PASSWORD</th>
                                <th>ROLE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <!-------------TABLE TABLE REGISTRATION LIST---------------->
                        <tbody>
                            @foreach($users as $User)
                            <tr class="register virtical">
                                <td >{{ $User->id }}</td>
                                <td>{{ $User->name }}</td>
                                <td>{{ $User->username }}</td>
                                <td>{{ $User->password }}</td>
                                <td>{{ $User->role }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li>
                                                <li>
                                                    <a class="dropdown-item text-center" href="RegisterEdit/{{ $User->id }}" onclick="return confirm('Are you sure to UPDATE')">
                                                    <i class="bi bi-pencil icon-lg text-warning"></i><span class="dots-vertical"> Update</span></a>
                                                </li>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-center" href="deleteUser/{{ $User->id }}" onclick="return confirm('Are you sure to DELETE?')">
                                                <i class="bi bi-trash icon-lg text-danger"></i><span class="dots-vertical"> delete</span></a>
                                            </li>
                                        </ul>
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
@endsection




