<div class="sportList">
    <table class="table table-bordered" id="turfListing">
        <thead>
            <tr>
                <th style="width: 10px">#</th>
                <th>Sport Name</th>
                <th>Dimension</th>
                <th>Capacity</th>
                <th>Rate Par Hours</th>
                <th>Status</th>
                <th style="width: 40px">Action</th>
            </tr>
        </thead>
        <tbody>

            @if($sports?->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">No sports found</td>
                </tr>
            @endif
            @foreach($sports as $sport)
            <tr class="align-middle">
                <td>{{ $sport->id }}</td>
                <td>{{ $sport->sportType->name }}</td>
                <td>{{ $sport->dimensions }}</td>
                <td>{{ $sport->capacity }}</td>
                <td>{{ $sport->rate_per_hour }}</td>
                <td>{{ $sport->status == 1 ? 'Available' : 'Not Available' }}</td>
                <td>
                    <a class="btb btn-sm btn-primary" href="javascript:void(0)" onclick="turf.editSport({{ $sport->id }}, ` {{ route('turf.edit.sport', ['sport' => $sport->id])}}`)">
                        Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>