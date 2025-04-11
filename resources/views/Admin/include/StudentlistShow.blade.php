<div class="row d-flex justify-content-center">
  <div class="row z-depth-3">
      <!------------------------------------SHOW INFORMATION BACKGROUND-------------------------------------------------->
      <div class="col-sm-4 rounded-left" style="background-color: #24A9E1;" >
          <!--------------------------PHOTO---------------------------->
          <div class="card-block text-center text-white mt-5">
            <img src="{{ asset($profiles->photo) }}" class="img-fluid rounded-circle" style="width: 200px; height: 200px;" alt="Profile Image">
            <h2 class="font-weight-bold mt-4">{{$profiles->FullName}}</h2>
            <h5>Student Profile</h5>
            <a href="ProfileEdit/{{$profiles->id}}" style="color: red;"><i class="far fa-edit fa-2x mb-4"></i></a>
          </div>
      </div>
      <div class="col-sm-8 ">
          <h2 class="mt-5 text-center" style="color: #24A9E1; font-weight: 700">INFORMATION</h2>
          <hr class="bg-primary mt-0 w-24">
          <div class="row">
              <!--------------------------RFID CODE---------------------------->
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">STUDENT RFID</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->StudentRFID}}" disabled/>
              </div>

              <!--------------------------COMPLETE ADDRESS---------------------------->
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">EMAIL ADDRESS</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->EmailAddress}}" disabled/>
              </div>

              <!--------------------------YEAR LEVEL---------------------------->
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">GRADE LEVEL</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->YearLevel}}" disabled/>
              </div>

              <!--------------------------COURSE---------------------------->
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">SECTION</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->Course}}" disabled/>
              </div>


              <!--------------------------GENDER---------------------------->
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">GENDER</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->Gender}}" disabled/>
              </div>

              <!--------------------------EMAIL ADDRESS---------------------------->
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">COMPLETE ADDRESS</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->CompleteAddress}}" disabled/>
              </div>


              <!--------------------------COMPLETE ADDRESS---------------------------->
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">CONTACT NUMBER</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->ContactNumber}}" disabled/>
              </div>

              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">PARENT NAME</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->Parent}}" disabled/>
              </div>
          </div>
              <!--------------------------Emergency Contact---------------------------->
              <h2 class="mt-5 text-center" style="color: #24A9E1; font-weight: 700">EMERGENCY CONTACT</h2>
              <hr>
          <div class="row ">
              <!--------------------------PARENT/GUARDIAN NAME---------------------------->
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">PARENT NAME</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->Parent}}" disabled/>
              </div>
              <!----------------------------EMERGENCY ADDRESS------------------------------>
              <div class="form-group col-sm-6">
                  <label class="font-weight-bold ">EMERGENCY ADDRESS</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->EmergencyAddress}}" disabled/>
              </div>

              <!----------------------------EMERGENCY NUMBER------------------------------>

              <div class="form-group col-sm-6">
                  <label class="font-weight-bold">EMERGENCY NUMBER</label>
                  <input type="text" class="form-control form-control-lg text-center" value="{{$profiles->EmergencyNumber}}" disabled/>
              </div>
          </div>
      </div>
  </div>
</div>
