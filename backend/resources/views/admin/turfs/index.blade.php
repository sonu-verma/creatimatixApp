@extends('layouts.admin.master', ['title' => "Turfs"])

@section('content')
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">
              <div class="card-header-flex">
                  <h3 class="card-title">Turf Lists</h3>
                  <a class="add-turf" href="{{ route('turf.create') }}">Add Turf</a>
              </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered" id="turfListing">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Turf Name</th>
                  <th>Location</th>
                  <th>Timing</th>
                  <th>Address</th>
                  <th>Is Available</th>
                  <th style="width: 40px">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($turfs as $turf)
                <tr class="align-middle">
                  <td>{{ $turf->id }}</td>
                  <td>{{ $turf->name }}</td>
                  <td>{{ $turf->location }}</td>
                  <td>{{ $turf->timing }}</td>
                  <td>{{ $turf->address }}</td>
                  <td>{{ $turf->status == 1 ? 'Available' : 'Not Available' }}</td>
                  <td><a class="btb btn-sm btn-primary" href="{{ route("turf.edit", ['id' => $turf->id])}}">Edit</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <div class="d-flex justify-end">
              {!! $turfs->links('pagination::bootstrap-4') !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end::Row-->
  </div>
  <!--end::Container-->
@endsection
