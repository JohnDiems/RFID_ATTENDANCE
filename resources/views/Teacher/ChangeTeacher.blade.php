@extends('Layout.teacher')

@section('content')

<div class="content-wrapper">
    <div class="content-header"> 
        <form  action="{{ route('ChangeTeacher') }}" method="POST">
        @csrf   
            <div class=" justify-content-center align-items-center">
                <!----------------------HANDLE ERROR VALIDATION---------------------------->
                @if(session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif
                <!----------------------HANDLE ERROR VALIDATION---------------------------->

                <!-- form card change password -->
                <div class="card">
                    <div class="card-header" style="background-color: #337AB7;">
                        <h3 class="mb-0 text-white">Change Password</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inputPasswordOld">Current Password</label>
                            @error('current_password')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                            <input type="password" class="form-control" id="current_password" name="current_password" required="">
                        </div>
                        <div class="form-group">
                            <label for="inputPasswordNew">New Password</label>
                            @error('new_password')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                            <input type="password" class="form-control" id="new_password"  name="new_password" required="">
                            <span class="form-text small ">
                                The password must be 8-20 characters, and must <em>not</em> contain spaces.
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="Verify">Confirm Password</label>
                            @error('new_password_confirmation')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                            <input type="password" class="form-control" id="new_password_confirmation"  name="new_password_confirmation" required="">
                            <span class="form-text small ">
                                To confirm, type the new password again.
                                
                            </span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg text-white" style="background-color: #337AB7; float:right;" >Save</button>
                        </div>
                    </div>
                </div>                                           
            </div>
        </form><br>
        <h2 class="text-center" style="font-weight: 800"><span class="AnnouncementInstruction"></span></h2>
        <label><h4>Privacy and Security:</h4></label>
        <div class="AddAnnouncement">
            <ul>
                <li>Keep your username and password confidential. Do not share them with anyone.</li>
                <li>Use a unique and strong password for your account to enhance security.</li>
                <li>Regularly update your password to protect your account from unauthorized access.</li>
                <li>Avoid using easily guessable information, such as birthdays or names, in your password.</li>
                <li>If you suspect any unauthorized activity or compromise of your account, change your password immediately and notify the administrator.</li>
            </ul>
            </div>

            <div class="UpdateAnnouncement">
            <label><h4>Username and Privacy:</h4></label>
            <ul>
                <li>Your username is a personal identifier; avoid using sensitive information.</li>
                <li>Be mindful of the information you share on your profile to protect your privacy.</li>
                <li>Review and adjust privacy settings to control who can view your profile and activities.</li>
                <li>If you have concerns about your account's privacy, contact the administrator for assistance.</li>
            </ul>
            </div>

            <div class="DeleteAnnouncement">
            <label><h4>Remember:</h4></label>
            <ul>
                <li>Regularly update your password for enhanced security.</li>
                <li>Report any suspicious activity or security concerns promptly.</li>

            </ul>
        </div> 
    </div>
</div>

@endsection