
@extends('layouts.admin.master', ['title' => "Dashboard"])

@section('content')

    <div class="container-fluid">
        <div class="row">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="basic-info nav-link {{ $step == 'basic' ? 'active border-b-2 border-blue-500' : '' }}" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Home</button>
                    <button class="images-info nav-link {{ $step == 'images' ? 'active border-b-2 border-blue-500' : ($step == 'images' ? '' : 'pointer-events-none text-gray-400') }}" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Images / Links</button>
                    <button class="sports-info nav-link {{ $step == 'sports' ? 'active border-b-2 border-blue-500' : ($step == 'sports' ? '' : 'pointer-events-none text-gray-400') }}" id="nav-sports-tab" data-bs-toggle="tab" data-bs-target="#nav-sports" type="button" role="tab" aria-controls="nav-sports" aria-selected="false">Sports</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{ $step == 'basic' ? 'active show' : '' }}" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    @include('admin.turfs.includes.details')
                </div>
                <div class="tab-pane fade {{ $step == 'sports' ? 'active show' : '' }}" id="nav-sports" role="tabpanel" aria-labelledby="nav-sports-tab">
                    @include('admin.turfs.includes.sports')
                </div>
                <div class="tab-pane fade {{ $step == 'images' ? 'active show' : '' }}" id="nav-contact" role="tabpanel" aria-labelledby="nav-images-tab">
                    @include('admin.turfs.includes.turf-images')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jslibs')
<script type="text/javascript" src="{{asset('admin/js/page/turf.js')}}"></script>
@endsection
@section('scripts')

@php
    $className = 'basic-info';
    if($step == 'sports'){
        $className = 'sports-info';
    }else if($step == 'images'){
        $className = 'images-info';
    }
@endphp
    <script type="text/javascript">
        var fileCreateURL = '{{route('file.create', ['type' => 'turf'])}}';
        $(window).on('load', function () {
            $(".{{$className}}").trigger('click')
        });
    </script>
@endsection

