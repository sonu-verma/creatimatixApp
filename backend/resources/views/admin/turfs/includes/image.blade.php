@foreach($images as $image)
<div class="row p-t-10 p-b-10 turf_img package_image_{{$image->id}}">
    <div class="col-lg-3 col-md-6 col-sm-12"> <a href=""> 
        <img src="{{$image->image_url}}" class="img-fluid width-100 img-thumbnail" alt="img-edit"> </a> </div>
    <div class="col-lg-9 col-md-6 col-sm-12 ">
        <div class="row p-t-10 p-b-10">
            <div class="col-md-12 col-sm-12">
                <div class="input-group"> <span class="input-group-addon">Alt Text</span>
                    <input type="text" class="form-control" name="images[{{$image->id}}][alt]" placeholder="Alt Text"
                        value="{{$image->image_name}}">
                </div>
            </div>
        </div>
        <div class="row p-t-10 p-b-10 turf_img_div_2">
            <div class="col-md-5 col-sm-12">
                <div class="input-group"> <span class="input-group-addon">Sort Order</span>
                    <input type="text" class="form-control" name="images[{{$image->id}}][sort]" placeholder="Sort order"
                        value="{{$image->sort_order}}">
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group row">
                    <div class="col-md-10">
                        <div class="form-radio p-t-5">
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="default_image" value="{{$image->id}}" @if($image->
                                    is_default)checked="checked"@endif><i class="helper"></i>Set as default </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 edit-right text-right">
                <button type="button" class="btn btn-danger btn-mini waves-effect waves-light"
                    onclick="turf.removeImage(this, '{{route('turf.image.remove', ['id'=>$image->id])}}')"> Remove
                    <i class="icofont icofont-close-circled f-16 m-l-5"></i> </button>
            </div>
        </div>
    </div>
</div>
@endforeach