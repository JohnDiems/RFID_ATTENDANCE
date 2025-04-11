

@extends('Layout.Admin')

@section('content')

    <div class="content-wrapper">           
      <div class="content-header">
            <div class=" justify-content-center align-items-center" style="margin-top: 2%">
                <div class="card">
                  <form action="{{ route('UpdateAnnouncement', $id) }}" method="post">
                    @csrf
                    <div class="card-header" style="background-color: #337AB7;">
                        <h3 class="mb-0 text-white text-center">Announcement Update</h3>
                    </div>
                    <div class="card-body announcements">
                      <div class="form-group mb-4">
                        <input type="text" name="title" id="title" value="{{$announcement->title}}" class="form-control form-control-lg title" placeholder="TITTLE" required>
                      </div>

                      <div class="form-group mb-4">
                        <textarea type="text" name="Content" id="Content"  class="form-control form-control-lg content" rows="5" placeholder="CONTENT MESSAGE" required>{{$announcement->Content}}</textarea>
                      </div>

                      <div class="pt-1 mb-3">
                        <button type="submit" class="btn btn-bg text-white" style="background-color: #337AB7" >UPDATE</button>
                        <a type="button" href="{{route('Announcement')}}" class="btn btn-danger btn-bg text-white"  >BACK</a>
                      </div>                
                    </div>   
                  </form>  
                </div>     
                <h2 class="text-center" style="font-weight: 800"><span class="AnnouncementInstruction"></span></h2>
                  <label><h4>Add Announcement:</h4></label>
                  <div class="AddAnnouncement">
                    <ul>
                      <li>To add a new announcement, please navigate to the appropriate section and click on the "Add Announcement" button.</li>
                      <li>Ensure that the required fields such as title and content are filled in.</li>
                      <li>Only one announcement can add.</li>
                      <li>Once added, the announcement will be visible to all users.</li>
                    </ul>
                  </div>

                  <div class="UpdateAnnouncement">
                    <label><h4>Update Announcement:</h4></label>
                    <ul>
                      <li>To update an existing announcement, find the announcement you wish to modify and click on the "Edit" button.</li>
                      <li>Make the necessary changes to the title or content.</li>
                      <li>Save the changes to update the announcement.</li>
                    </ul>
                  </div>

                  <div class="DeleteAnnouncement">
                    <label><h4>Delete Announcement:</h4></label>
                    <ul>
                      <li>Deleting an announcement is a permanent action. Please exercise caution.</li>
                      <li>To delete an announcement, locate the announcement you want to remove and click on the "Delete" button.</li>
                      <li>Confirm the Alert deletion.</li>
                    </ul>
                  </div>

                  <div class="ImportantNote">
                    <label><h4>Important Note:</h4></label>
                    <ul>
                      <li>Only authorized Admin have the ability to add, delete, or update announcements.</li>
                      <li>Ensure that the information provided in announcements is accurate and relevant.</li>
                      <li>Contact the administrator if you encounter any issues or need further assistance.</li>
                    </ul>
                  </div>                                      
            </div>
        </div>
      </div>

@endsection