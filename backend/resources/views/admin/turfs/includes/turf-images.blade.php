<div class="turfImages">
    <div id="packageImages" role="tabpanel" style="margin:10px;">
        <div class="card-block">
            <div class="card-header p-4 mb-4">
                <div class="row" style="border-style: ridge;padding: 20px;">
                    <div class="col-md-6">
                        <label for="uploadPhoto" class="custom-file">
                            <input type="file" id="uploadPhoto" class="custom-file-input uploadPhoto">
                            <span class="custom-file-control"></span> </label>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center loader-block uploadProgress" style="display: none;">
                            <div class="preloader4">
                                <div class="double-bounce1"></div>
                                <div class="double-bounce2"></div>
                                <span>0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form class="" action="{{ route('turf.store.images') }}" id="frmTurfImages" method="POST">
                 @csrf
                <input type="hidden" name="id_turf" id="turfId" class="turfId" value="{{ $model?->id }}" />
                <div class="uploadedImages">
                    @if($model?->images)
                        @include('admin.turfs.includes.image', ['images' => $model?->images])
                    @endif
                </div>
                <div class="row mt-4">
                
                </div>
            </form>
            <div class="col-md-12 turf-btns">
                        <button type="button" onclick="document.getElementById('nav-sports-tab').click();"
                            class="btn btn-primary pull-right m-b-15 m-l-15"> Next </button>
                        <button type="submit" onclick="document.getElementById('nav-home-tab').click();"
                            class="btn btn-primary pull-right m-b-15 m-l-15">Previous </button>
                        {{-- <button type="submit" onclick="return package.saveImage()"
                            class="btn btn-primary pull-right">
                            {{ $model?->id ? 'Update' : 'Create' }} </button> --}}
                    </div>
        </div>
    </div>
</div>