@extends('Layout.Admin')

@section('content')
  <style>
    .dataTables_wrapper, .dataTables_filter input {
      margin-bottom: 0;
    }
  </style>

  <div class="content-wrapper">
    <div class="content-header">
      <div class=" justify-content-center align-items-center" >

        <!-- Button trigger modal -->
        <div class="card-header py-3">
          <button type="button" class="btn btn-bg text-white" style="background-color: #337AB7"  data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="bi bi-person-plus-fill fa-lg"></i>  Add Announcement
          </button>
        </div>

        <!----------------------HANDLE ERROR VALIDATION---------------------------->
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif
        <!----------------------HANDLE ERROR VALIDATION---------------------------->
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Announcement</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{route('AnnouncementForm') }}" method="post">
                @csrf
                <div class="modal-body">
                  <div class="form-group mb-4">
                    <input type="text" name="title" id="title" class="form-control form-control-lg title" placeholder="TITLE" required>
                  </div>

                  <div class="form-group mb-4">
                    <textarea type="text" name="Content" id="Content" class="form-control form-control-lg content" rows="5"  placeholder="CONTENT MESSAGE" required></textarea>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger btn-bg text-white" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-bg text-white" style="background-color: #337AB7">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col mt-3 ">
              <table id="filter" class="table table-hover table-sm" >
                  <thead class="text-black">
                  <tr>
                    <th>ID</th>
                    <th>TITLE</th>
                    <th>CONTENT</th>
                    <th>ACTION</th>
                  </tr>
                </thead>
                @foreach($announcements as $Announcement)
                <tbody>
                  <tr>
                    <td>{{$Announcement->id}}</td>
                    <td>{{$Announcement->title}}</td>
                    <td>{{$Announcement->Content}}</td>
                    <td>
                      <div class="btn-group">
                          <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bi bi-three-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-dark">
                              <li>
                                  <li>
                                      <a class="dropdown-item text-center" href="announcements/{{ $Announcement->id }}" onclick="return confirm('Are you sure to UPDATE')">
                                      <i class="bi bi-pencil icon-lg text-warning"></i><span class="dots-vertical"> Update</span></a>
                                  </li>
                              </li>
                              <li>
                                  <a class="dropdown-item text-center" href="deleteAnnouncement/{{ $Announcement->id }}" onclick="return confirm('Are you sure to DELETE?')">
                                  <i class="bi bi-trash icon-lg text-danger"></i><span class="dots-vertical"> delete</span></a>
                              </li>
                          </ul>
                      </div>
                  </td>
                  </tr>
                </tbody>
                @endforeach
              </table>
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
  </div>
@endsection
