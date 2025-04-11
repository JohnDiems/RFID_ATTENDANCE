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
            <form action="{{route('ScheduleUpdates', $id )}}" method="post">
              @csrf
                <div class="card-header" style="background-color: #337AB7;">
                  <h3 class="mb-0 text-white text-center">Schedule Update</h3>
                </div>
                <div class="card-body">

                <label class="scheule">Event Content</label>
                <div class="form-group mb-4">
                  <input type="text" name="EventSchedule" id="EventSchedule" class="form-control form-control-lg" placeholder="Event Content" value="{{$schedule->EventSchedule}}">
                </div>

                <label class="scheule">Time in Start</label>
                <div class="form-group mb-4">
                  <input type="time" name="EventTimein" id="EventTimein" class="form-control form-control-lg" value="{{$schedule->EventTimein}}" >
                </div>

                <label class="scheule">Time out Start</label>
                <div class="form-group mb-4">
                  <input type="time" name="EventTimeout" id="EventTimeout" class="form-control form-control-lg" value="{{$schedule->EventTimeout}}" >
                </div>

                <label class="scheule">Event Date</label>
                <div class="form-group mb-4">
                  <input type="date" name="EventDate" id="EventDate" class="form-control form-control-lg" value="{{$schedule->EventDate}}">
                </div>
      
                <div class="pt-1 mb-3">
                  <button type="submit" class="btn btn-bg text-white" style="background-color: #337AB7" >UPDATE</button>
                  <a type="button" href="{{route('Schedule')}}" class="btn btn-danger btn-bg text-white">BACK</a>
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