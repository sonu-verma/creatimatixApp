<div class="sportRegister">
    <div class="sportListDiv">
        @if(@$model?->sports)
            @include('admin.turfs.includes.sportList')
        @endif
    </div>
    <div class="sportFormDiv">
        <form method="POST" action="{{ $route }}" id="frmSportDetails">
            @csrf
            <input type="hidden" name="sportId" id="sportId" class="form-control">
            <input type="hidden" name="turfId" id="turfId" value="{{ $model?->id }}" class="form-control packageId">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_sport" class="form-control-label">Sports:</label>
                        <select name="id_sport" class="form-control" id="id_sport">
                            <option value="">Select Sport Type</option>
                            @foreach($sportTypes as $key => $sport)
                                <option value="{{ $sport->id }}" >
                                    {{ $sport->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="capacity" class="form-control-label">Capacity</label>
                        <input type="text" name="capacity" class="form-control" id="capacity" value="{{ old('capacity') }}" aria-describedby="capacity">
                    </div>
                </div>
            </div>
            <div class="row  mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dimension" class="form-label">Dimension</label>
                        <div class="input-group">
                            <input type="text" name="dimension" class="form-control" id="dimension" value="{{ old('dimension') }}" aria-describedby="dimension">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="rate_per_hour" class="form-control-label">Rate Par Hours</label>
                        <input type="text" name="rate_per_hour" id="rate_per_hour" value="{{ old('rate_per_hour') }}" class="form-control">
                    </div>
                </div>
            </div>
        
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="rules" class="form-control-label">Rules</label>
                        <textarea class="form-control sportRules" name="rules" rows="4" id="editor11">{{ old('rules') }}</textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-control-label">Is Available?</label>
                        <div class="form-radio radio-flex">
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="status" value="1" checked>
                                    <i class="helper"></i>Active
                                </label>
                            </div>
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="status" value="0">
                                    <i class="helper"></i>Inactive
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row mb-3">
                <div class="col-md-12 turf-btns">
                    <button type="button" onclick="return turf.saveSportDetails()"
                        class="btn btn-primary pull-right">
                        Save
                    </button>
                    <button type="button" onclick="return turf.cancelSportEdit(this)"
                        class="btn btn-primary pull-right cancelSportEdit">
                        Cancel Edit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>