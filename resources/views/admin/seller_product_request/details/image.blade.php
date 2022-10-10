@php $n_img = 0 @endphp
    <div class="card-header mb-4"><div class="card-title">Product Images</div></div>
    @if($product)
    <div class="col-12 mb-4">
        @foreach($product->prdImage as $img)
        <div class="col-md-3 col-sm-6 mb-3 fl"><img src="{{config('app.storage_url').$img->thumb}}" alt="Product Image" height="120px" /></div>
        @endforeach
    </div>
    @endif
    <div id="prd_imgs">
        <div id="img_row_0" class="col-12 fl img_row">
            <div class="col-lg-6 fl">
                <div class="form-group">
                    {{Form::hidden('imgId[]',0,['id'=>'img_id_'.$n_img])}}
                    {{Form::file('image[]',['id'=>'image_'.$n_img,'class'=>'form-control img','placeholder'=>'Choose Image','accept'=>'image/*'])}}
                </div>
            </div>
        <div class="col-lg-5 col-8 fl">
                <div class="form-group">
                    <img src="" alt="Image" id="image_{{$n_img}}_img" class="no-disp" height="90" />
                </div>
            </div> @php $n_img++; @endphp
            <div class="clr"></div>
        </div>
            <div class="clr"></div>

    </div>
    <div class="clr"></div>
    <div class="col-12 text-right">
        <button id="add_more" class="mt-4 mb-4 btn btn-info btn-sm" type="button"><i class="fa fa-plus mr-1"></i>Add More</button>
    </div>


<div id="add_more_img" class="d-none">
    <div id="img_row_id" class="col-12 fl img_row">
        <div class="col-lg-6 fl">
            <div class="form-group">
                {{Form::file('image[]',['id'=>'image_file_id','class'=>'form-control img','placeholder'=>'Choose Image','accept'=>'image/*'])}}
            </div>
        </div>
        <div class="col-lg-5 col-8 fl">
            <div class="form-group">
                <img src="" alt="Image" id="image_disp_id" class="no-disp" />
            </div>
        </div>
        <div class="col-lg-1 col-2 pl-0 mb-2 fl">
            <div class="form-group">
                <label>&nbsp; &nbsp;</label><div class="clr"></div>
                <a id="del_img_id" class="del_img del"><i class="fa fa-trash"></I></a>
            </div>
        </div>@php $n_img++; @endphp
        <div class="clr"></div>
    </div>
</div>
<div class="card-header mb-4"><div class="card-title">Product Video</div></div>
<p>(Video Types: mp4, mpeg, mov, avi, flv. Max: Size: 30MB) </p>
<div class="row">
    
    <?php if(isset($videos)){ $video_link = config('app.storage_url').$videos->video;  }else { $video_link = ""; } ?>
    <div class="col-md-6 col-md-offset-6">
       <input type="file" class="dropify" name="video" data-height="180" data-default-file="{{ $video_link }}" data-allowed-file-extensions='["mpeg", "ogg", "mp4", "webm", "3gp", "mov", "flv", "avi", "wmv"]' data-max-file-size="30M" />
    </div>
</div>
<!-- INTERNAL File uploads js -->
        <script src="{{URL::asset('admin/assets/plugins/fileupload/js/dropify.js')}}"></script>
        <script src="{{URL::asset('admin/assets/js/filupload.js')}}"></script>