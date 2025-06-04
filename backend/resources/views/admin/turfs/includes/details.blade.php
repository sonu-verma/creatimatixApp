<div class="turfBooking">
    <form method="POST" action="{{ route('turf.store.basic') }}" id="frmDetails">
        @csrf
        <input type="hidden" name="id" id="packageId" value="{{ old('id', $model?->id) }}"
            class="form-control packageId">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="turfName" class="form-control-label">Name</label>
                    <input type="text" name="name" id="turfName"  value="{{ old('name', $model?->name) }}"
                        class="form-control">
                </div>
                @error('name')
                    <div><span class="error">{{ $message }}</span></div>
                @enderror
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="timing" class="form-control-label">Timing</label>
                    <input type="text" name="timing" id="txtName" value="{{ old('timing', $model?->timing) }}" class="form-control" >
                </div>
                @error('timing')
                    <div><span class="error">{{ $message }}</span></div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="slug" class="form-label">Turf URL</label>
                    <div class="input-group">
                        <span class="input-group-text">/</span>
                        <input type="text" name="slug" class="form-control turfSlug" id="slug" aria-describedby="slug"  value="{{ old('slug', $model?->slug) }}">
                    </div>
                </div>
                @error('slug')
                    <div><span class="error">{{ $message }}</span></div>
                @enderror
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label for="location" class="form-label">Location</label>
                <div class="input-group">
                    <input type="text" name="location" class="form-control" id="location" aria-describedby="location"  value="{{ old('location', $model?->location) }}">
                </div>
            </div>
            @error('location')
                <div><span class="error">{{ $message }}</span></div>
            @enderror
        </div>
        </div>
        <div class="row  mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="features" class="form-control-label">Short Description</label>
                    <textarea class="form-control" name="features" rows="5"
                        id="editor1">{{ $model?->features}}</textarea>
                    <small class="form-text text-muted">
                        You can use HTML tags here.
                    </small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="benefits" class="form-control-label">Benefits</label>
                    <textarea class="form-control" name="benefits" rows="5"
                        id="editor11">{{ $model?->benefits}}</textarea>
                    <small class="form-text text-muted">
                        You can use HTML tags here.
                    </small>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="description" class="form-control-label">Description</label>
                    <textarea class="form-control" name="description" rows="5">{{ $model?->description}}</textarea>
                    <small id="txtDescription" class="form-text text-muted">
                        You can use HTML tags here.
                    </small>
                </div>
            </div>
        </div>
        
        <div class="row  mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="latitude" class="form-control-label">Latitude</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $model?->latitude) }}" class="form-control">
                </div>
                @error('latitude')
                    <div><span class="error">{{ $message }}</span></div>
                @enderror
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="longitude" class="form-control-label">Longitude</label>
                    <input type="text" name="longitude" id="longitude"
                        value="{{ old('longitude', $model?->longitude) }}" class="form-control">
                </div>
                @error('longitude')
                    <div><span class="error">{{ $message }}</span></div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="txtAddress" class="form-control-label">Address</label>
                    <input type="text" name="address" id="txtAddress" value="{{ old('address', $model?->address) }}"
                        class="form-control">
                </div>
                @error('address')
                    <div><span class="error">{{ $message }}</span></div>
                @enderror
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status" class="form-control-label">Is Available?</label>
                    <div class="form-radio radio-flex">
                        <div class="radio radio-inline">
                            <label>
                                <input type="radio" name="status" value="1" {{ $model?->status == 1 || !$model?->status ? 'checked' : '' }}>
                                <i class="helper"></i>Active
                            </label>
                        </div>
                        <div class="radio radio-inline">
                            <label>
                                <input type="radio" name="status" value="0" {{ $model?->status == 0 && $model?->status == 0 ? 'checked' : '' }}>
                                <i class="helper"></i>Inactive
                            </label>
                        </div>
                    </div>
                </div>
                @error('status')
                    <div><span class="error">{{ $message }}</span></div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rules" class="form-control-label">Rules</label>
                    <textarea class="form-control" name="rules" rows="4" id="rules">{{ $model?->rules}}</textarea>
                </div>
                @error('rules')
                    <div><span class="error">{{ $message }}</span></div>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12 turf-btns">
                {{-- <button type="submit" onclick="CKupdate();return package.saveDetails('packageCombination')"
                        class="btn btn-primary pull-right m-b-15 m-l-15">Next
                </button> --}}
                <button type="submit" class="btn btn-primary pull-right">
                    Save & Next
                </button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function displayBase64ImgBox(n) {
        if (n == 1) {
            $('#base64img').show();
        } else {
            $('#base64img').hide();
        }
    }

    function checkWebDispQty() {
        var curQty = $('#stock').val();
        var minQty = $('#min_web_qty').val();
        if (parseInt(curQty) <= 0) {
            $('#stock').val(minQty);
        }
    }

    function selectAllStates(n) {
        if (n == 1) {
            $('#state').val(0);
            $('#state').trigger('change');
        }
    }
</script>