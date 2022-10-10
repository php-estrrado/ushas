<style>.warning { color: red; }</style>
@php $n_img = 0 @endphp
 <div id='lbError' class='warning'></div>
    <div class="card-header mb-4"><div class="card-title">Product Images</div></div>
    @if($product)
    <div class="col-12 mb-4">
        @foreach($product->prdImage as $img)
        <div id="prdImg-{{$img->id}}" class="col-md-3 col-sm-6 mb-3 fl imgDiv">
            <img class="prdImg" src="{{config('app.storage_url').$img->thumb}}" alt="Product Image" height="120px" />
            <i id="del-img-{{$img->id}}" class="fa fa-trash del-img" aria-hidden="true"></i>
        </div>
        @endforeach
    </div>
    @else
   
    <div class="col-12 mb-4" id="admin_prod_img" style="display:none;">
        
        <div id="adprdImg" class="col-md-3 col-sm-6 mb-3 fl imgDiv">
            <img class="adminprdImg" src="" alt="Product Image" height="120px" />
            <i id="ad-del-img-1" class="fa fa-trash ad-del-img" aria-hidden="true"></i>
             
        </div>
        {{Form::hidden('imgArr[]',0,['id'=>'ad_img_arr_id'])}}
        {{Form::hidden('adminimgArr',0,['id'=>'img_arr_id'])}}
    </div>

    @endif
		
    <div id="prd_imgs">
        <div id="img_row_0" class="col-12 fl img_row">
            <div class="col-lg-6 fl">
                <div class="form-group">
                    {{Form::hidden('imgId[]',0,['id'=>'img_id_'.$n_img])}}
                    {{Form::file('',['id'=>'image_'.$n_img,'class'=>'form-control img image','placeholder'=>'Choose Image','accept'=>'image/*'])}}
                    <input type="hidden" name="image[]" id="image_file_id" class="error " value="">
				   <span class="error"></span>
                </div>
            </div>
        <div class="col-lg-5 col-8 fl">
                <div class="form-group">
                    
                <div id="image_{{$n_img}}_img" style="background:#9d9d9d;width:238px;padding:34px 15px;height:170px;"></div>
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
                {{Form::file('',['id'=>'image_file_id','class'=>'form-control img image','placeholder'=>'Choose Image','accept'=>'image/*'])}}
			 <input type="hidden" name="image[]" id="img_name_id" class="error" value="">
			</div>
        </div>
        <div class="col-lg-5 col-8 fl">
            <div class="form-group">
			                <div id="image_disp_id" class="no-disp" style="background:#9d9d9d;width:238px;padding:34px 15px;height:170px;"></div>

               
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

<style>
.modal.modal-fullscreen .modal-dialog {
  width: 85vw;
  height: 90vh;
  margin: 0 auto;
  padding: 0;
  max-width: none; 
}

.modal.modal-fullscreen .modal-content {
  height: auto;
  height: 95vh;
  border-radius: 0;
  border: none; 
}

.modal.modal-fullscreen .modal-body {
  overflow-y: auto; 
}
</style>
<div class="modal modal-fullscreen" id="modaldemo1">
			<div class="modal-dialog " role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title">Image Preview</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-4 text-center">
							<div id="upload-demo"></div>
							<input type="hidden" id="browse_image" value="" >
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<a class="btn btn-success btn-block upload-image" style="margin-top:2%">Cropping Image</a>
					</div>
				</div>
			</div>
		</div>
		
		
		
		<script type="text/javascript">



 

var resize = $('#upload-demo').croppie({
    enableExif: true,
    enableOrientation: true,    
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 1024,
        height: 500,
        type: 'square' //square
    },
    boundary: {
        width: 1024,
        height: 500
    }
});
$('body').on('change','.image', function(){
    console.log('This file size is: ' + this.files[0].size / 1024 / 1024 + "MiB");
    var iSize = (this.files[0].size  / 1024); 
    var filesize = (Math.round(iSize * 100) / 100);
    warningel   = document.getElementById( 'lbError' );
    var maxfilesize=500;
if ( filesize > maxfilesize )
  {
    warningel.innerHTML = "File too large: " + filesize + ". Maximum size: " + maxfilesize +"kb";
    this.value="";
    return false;
  }

 $("#browse_image").val($(this).attr('id'));
$('#modaldemo1').modal('show');
  var reader = new FileReader();
    reader.onload = function (e) {
      resize.croppie('bind',{
        url: e.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
});
$('body').on('click','.upload-image', function(ev){
  resize.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function (img) {
    $.ajax({
      url: "{{route('croppie.upload-image')}}",
      type: "POST",
      data: {"image":img},
      success: function (data) {
		var browse_image= $("#browse_image").val();
		//$("#"+browse_image).next('input').find('.error').val(data['image']);
		$("#"+browse_image).nextAll(".error").val(data['image']);
		var substr = browse_image.split('_');
	//	alert(substr);
        html = '<img src="' + img + '" />';
        $("#image_"+substr[1]+"_img").html(html);
        $("#preview-crop-image").html(html);
		$('#modaldemo1').modal('hide');
      }
    });
  });
});


</script>