@extends('Layout.Admin')

@section('content')

    <!--MASTER NEEDED-->
    <div class="content-wrapper">
      <div class="content-header">  
          <div class=" justify-content-center align-items-center" style="margin-top: 2%">           
            <div class="card">
              <form action="{{ route('register.update', $id) }}" method="POST">
                @csrf
                <div class="card-header" style="background-color: #337AB7;">
                    <h3 class="mb-0 text-white text-center">Update Account User</h3>
                </div>
                <div class="card-body announcements">
                  <div class="form-group mb-4">
                    <label class="form-group edit">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Name" 
                    class="form-control form-control-lg" value="{{$user->name}}" required/>
                  </div>
                  <div class="form-group mb-4">
                    <label class="form-group edit">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Username"  
                    class="form-control form-control-lg" value="{{$user->username}}" required/>
                  </div>
                  <div class="form-group mb-4">
                    <label class="form-group edit">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" 
                    class="form-control form-control-lg" value="{{$user->password}}" required/>
                  </div>
                  <!--<div class="form-group mb-4">-->
                  <!--  <label class="form-group edit" for="role">Role:</label>-->
                  <!--  <select class="form-control mb-4" id="role" name="role" class="form-control form-control-lg"  required>-->
                  <!--    {{-- <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option> --}}-->
                  <!--    <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Teacher</option>-->
                  <!--    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>-->
                  <!--  </select>-->
                  <!--</div>-->

                  <div class="pt-1 mb-3">
                    <button type="submit" class="btn btn-bg text-white" style="background-color: #337AB7" >UPDATE</button>
                    <a type="button" href="{{route('RegistrationForm')}}" class="btn btn-danger btn-bg text-white">BACK</a>
                  </div>                
                </div>   
              </form>  
            </div>  <br>
            <h2 class="text-center" style="font-weight: 800"><span class="AnnouncementInstruction"></span></h2>
            <label><h4>Name:</h4></label>
            <div class="Name">
              <ul>
                <li>Adjust your displayed name by entering your preferred name in the "Name" field.</li>
              </ul>
            </div>

            <label><h4>Username:</h4></label>
            <div class="Username">
              <ul>
                <li>Modify your username to make it more suitable or reflective of your identity.</li>
              </ul>
            </div>

            <label><h4>Password:</h4></label>
            <div class="Password">
              <ul>
                <li>Change your password regularly for enhanced security. Use a mix of uppercase, lowercase, numbers, and special characters for a strong password.</li>
              </ul>
            </div>

            <label><h4>Role:</h4></label>
            <div class="Role">
              <ul>
                <li>Update your role to reflect your current responsibilities or privileges.</li>
                <li>Choose from available roles such as "Admin" or "Teacher" based on your assigned tasks.</li>
              </ul>
            </div>

            <label><h4>Additional Tips:</h4></label>
            <div class="AdditionalTips">
              <ul>
                <li>Ensure your updated information aligns with the platform's guidelines and policies.</li>
                <li>Double-check the accuracy of the changes before saving them.</li>
              </ul>
            </div>
          </div>
      </div>
    </div>
@endsection