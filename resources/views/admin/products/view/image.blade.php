@php $n_img = 0 @endphp
<div class="tab-pane" id="tab3">
    <div class="card-header mb-4"><div class="card-title">Product Images</div></div>
    <div id="prd_imgs">
        <div id="img_row_0" class="col-12 fl img_row">
           <ul class="prod_imgs">
            @if(count($images) >0)
            @foreach ($images as $img)
             <li class="prod_img">
           <img src="{{ url('storage/'.$img->image) }}" alt="Product Image" width="150px" >
        </li> 

            @endforeach
            @endif
            
           </ul>
        </div>
            <div class="clr"></div>

    </div>
<div class="card-header mb-4"><div class="card-title">Product Video</div></div>
<div class="row">
    
    <?php if(isset($videos)){ $video_link = config('app.storage_url').$videos->video;  }else { $video_link = ""; } ?>
    <div class="col-md-6 col-md-offset-6">
       <input type="file" class="dropify" name="video" data-height="180" data-default-file="{{ $video_link }}" data-allowed-file-extensions='["mpeg", "ogg", "mp4", "webm", "3gp", "mov", "flv", "avi", "wmv"]' data-max-file-size="30M" />
    </div>
</div>
<!-- INTERNAL File uploads js -->
        <script src="{{URL::asset('admin/assets/plugins/fileupload/js/dropify.js')}}"></script>
        <script src="{{URL::asset('admin/assets/js/filupload.js')}}"></script>
    
</div>