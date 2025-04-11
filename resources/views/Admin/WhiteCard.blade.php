@extends('Layout.Admin')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">

            <!----------------------HANDLE ERROR VALIDATION---------------------------->
            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif
            <!----------------------HANDLE ERROR VALIDATION---------------------------->

            <!-- Button trigger modal -->
            <div class="card-header py-3">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-bg text-white" style="background-color: #337AB7" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop">
                    <i class="bi bi-person-plus-fill fa-lg"></i> Register White Card
                </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('CardStore') }}" method="post">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Register To White Card</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label>Student Name:</label>
                                <div class="form-group mb-4">
                                    <select name="profile_id" id="profile_id" class="form-control searchable-dropdown" style="text-transform: lowercase;" required>
                                        <option value="">Select Name</option>
                                        @foreach ($profiles as $profile)
                                            <option value="{{ $profile->id }}">{{ $profile->FullName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label>Start Lunch:</label>
                                <div class="form-group mb-4">
                                    <input type="time" name="Lunch_in" id="Lunch_in" class="form-control" value="12:00"
                                        required>
                                </div>

                                <label>End Lunch:</label>
                                <div class="form-group mb-4">
                                    <input type="time" name="Lunch_out" id="Lunch_out" class="form-control"
                                        value="13:30" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Understood</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>




        <!-- Button trigger modal -->
        <div class="card-header ">
            <div class="card-body mt-4">
                <table id="filter" class="table table-hover  table-sm">
                    <thead class="text-black">
                        <tr class="text-center">
                            <th>NAME</th>
                            <th>START LUNCH</th>
                            <th>END LUNCH</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    </tbody>
                    @foreach ($cards as $card)
                        <tr class="Sectionlist vertical" style="font-size: 18px;">
                            <td>
                                @if ($card->profiles)
                                    {{ $card->profiles->FullName }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $card->lunch_in }}</td>
                            <td>{{ $card->lunch_out }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <li>
                                        <li>
                                            <a class="dropdown-item text-center"
                                                href="WhiteCardEdit/{{$card->id}}"
                                                onclick="return confirm('Are you sure to UPDATE')">
                                                <i class="bi bi-pencil icon-lg text-warning"></i><span
                                                    class="dots-vertical"> Update</span></a>
                                        </li>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-center" href="WhiteCardDelete/{{$card->id}}"
                                                onclick="return confirm('Are you sure to DELETE?')">
                                                <i class="bi bi-trash icon-lg text-danger"></i><span
                                                    class="dots-vertical"> delete</span></a>
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
