@extends('Layout.user')

@section('content')
<div class="content-wrapper">           
  <div class="content-header"> 
      <div class=" justify-content-center align-items-center">
          <div class="card m-0">
            <div class="card-header" style="background-color: #337AB7;">
                <h3 class="mb-0 text-white">Where you're logged in</h3>
            </div>
            <div class="card-body">
              <Label><h5>Current Login</h5></Label><br>
              <h2>{{$user->current_login_at}}</h2>
              <Label><h5>Last Login</h5></Label><br>
              <h2>{{$user->last_login_at}}</h2>
              <Label><h5>User Device</h5></Label><br>
              <h5>{{$user->last_login_device}}</h5>
            </div>     
          </div>                                           
      </div><br>
      <h2 class="text-center" style="font-weight: 800"><span class="AnnouncementInstruction"></span></h2>
      <label><h4>Login Information:</h4></label>
      <div class="LoginInformation">
        <ul>
            <li>View your current and last login details in the "Account Security" or "Login History" section.</li>
            <li>Current Login" displays your most recent login information, including date, time, and device.</li>
            <li>Last Login" shows details of your previous login session for comparison.</li>
        </ul>
        </div>

        <div class="UserDevice">
        <label><h4>User Device:</h4></label>
        <ul>
            <li>Keep an eye on the "User Device" section to monitor devices accessing your account.</li>
            <li>Review and verify devices listed to ensure they are legitimate.</li>
            <li>If you notice any unfamiliar or suspicious devices, take immediate action</li>
        </ul>
        </div>

        <div class="DeleteAnnouncement">
        <label><h4>Remember:</h4></label>
        <ul>
            <li>Regularly update your password for enhanced security.</li>
            <li>Your account security is our priority..</li>
            <li>Contact support if you encounter issues with your login or have security concerns.</li>
        </ul>
      </div>
  </div><br><br>
@endsection
  