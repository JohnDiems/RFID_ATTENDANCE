@extends('Layout.user')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <label for="dateFilter">Filter by Date:</label>
                    <input type="date" id="dateFilter" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label for="statusFilter">Filter by Status:</label>
                    <select id="statusFilter" class="form-control">
                        <option value="">All</option>
                        <option value="completed">Completed</option>
                        <option value="ongoing">Ongoing</option>
                    </select>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Lunch Records</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="lunchTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">LUNCH START</th>
                                    <th class="text-center">LUNCH DONE</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">DURATION</th>
                                    <th class="text-center">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lunchs as $lunch)
                                    <tr>
                                        <td class="text-center">{{ $lunch->time_in ? \Carbon\Carbon::parse($lunch->time_in)->format('g:i A') : '-' }}</td>
                                        <td class="text-center">{{ $lunch->time_out ? \Carbon\Carbon::parse($lunch->time_out)->format('g:i A') : '-' }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($lunch->date)->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            @if($lunch->time_in && $lunch->time_out)
                                                {{ \Carbon\Carbon::parse($lunch->time_in)->diffInMinutes($lunch->time_out) }} mins
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($lunch->time_out)
                                                <span class="badge badge-success">Completed</span>
                                            @else
                                                <span class="badge badge-warning">Ongoing</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $lunchs->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#lunchTable').DataTable({
                "order": [[2, "desc"]],
                "pageLength": 10,
                "responsive": true
            });

            // Date filter
            $('#dateFilter').on('change', function() {
                var date = $(this).val();
                $('#lunchTable').DataTable().column(2)
                    .search(date)
                    .draw();
            });

            // Status filter
            $('#statusFilter').on('change', function() {
                var status = $(this).val();
                $('#lunchTable').DataTable().column(4)
                    .search(status)
                    .draw();
            });
        });
    </script>
    @endpush
@endsection
