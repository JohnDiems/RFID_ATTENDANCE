@extends('Layout.Admin')

@section('content')

  <style>
    .scheule{
      font-size: 20px;
    }
  </style>

    <!--MASTER NEEDED-->
    <div class="content-wrapper">
      <div class="content-header">
        <div class="card">
            <form action="{{route('WhiteCardSchedule', $id )}}" method="post">
              @csrf
                <div class="card-header" style="background-color: #337AB7;">
                  <h3 class="mb-0 text-white text-center">White Card Update</h3>
                </div>
                <div class="card-body">

                <label class="Whitecard">Student Name:</label>
                <div class="form-group mb-4">
                  <input type="text" name="FullName" id="FullName" class="form-control form-control-lg" placeholder="Event Content" value="{{ $cards->profiles->FullName }}">
                </div>

                <label class="Whitecard">Start Lunch:</label>
                <div class="form-group mb-4">
                  <input type="time" name="Lunch_in" id="Lunch_in" class="form-control form-control-lg" value="{{$cards->lunch_in}}" >
                </div>

                <label class="Whitecard">End Lunch</label>
                <div class="form-group mb-4">
                  <input type="time" name="Lunch_out" id="Lunch_out" class="form-control form-control-lg" value="{{$cards->lunch_out}}" >
                </div>


                <div class="pt-1 mb-3">
                  <button type="submit" class="btn btn-bg text-white" style="background-color: #337AB7" >UPDATE</button>
                  <a type="button" href="{{route('WhiteCard')}}" class="btn btn-danger btn-bg text-white">BACK</a>
                </div>
              </div>
            </form>
          </div>
          <br>
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
@endsection
