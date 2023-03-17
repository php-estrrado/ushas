
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/css/combo-tree.css')); ?>" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
		<link href="<?php echo e(URL::asset('admin/assets/css/datepicker.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/css/chosen.min.css')); ?>" rel="stylesheet"/>
		<link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('admin/assets/css/bootstrap-datetimepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Settings</a></li>
									<li class="breadcrumb-item"><a href="<?php echo e(url('admin/banners')); ?>"><i class="fe fe-grid mr-2 fs-14"></i>Banners</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
								</ol>
							</div>
							<div class="page-rightheader">
						
							</div>
						</div>
                        <!--End Page header-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

						
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsiv table-lg mt-3">
													<?php echo e(Form::open(array('url' => "/admin/banners/save", 'id' => 'bannerForm', 'name' => 'bannerForm', 'class' => '','files'=>'true'))); ?>

												<?php echo e(Form::hidden('id',$banner['id'],['id'=>'saleid'])); ?>

												<?php echo e(Form::hidden('title_cnt_id',$banner['title_cnt_id'],['id'=>'title_cnt_id'])); ?>

												<?php echo e(Form::hidden('alt_text_cid',$banner['alt_text_cid'],['id'=>'alt_text_cid'])); ?>

												<?php echo e(Form::hidden('btn_label_cid',$banner['btn_label_cid'],['id'=>'btn_label_cid'])); ?>

												<div class="py-1">
													
														<div class="row">
															<div class="col">
																<div class="row">

																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="glo_lang_cid" required>

																			<?php
																			$def_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
																			$content_table=DB::table('cms_content')->where('cnt_id', $banner['title_cnt_id'])->where('lang_id', $def_lang->id)->first();
																			if($content_table){ 
																			$lang_id = $content_table->lang_id;
																			}
																			?>                                                                    

																			<?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																			<option value="<?php echo e($lang->id); ?>" <?php if($lang_id==$lang->id){ echo "selected";} ?> ><?php echo e($lang->glo_lang_name); ?></option>
																			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																			</select>
																		</div>
																		
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Caption <span class="text-red">*</span></label>
																			
																			<?php echo Form::text('caption', $banner['title'], ['class' => 'form-control','rows' => 3,'id'=>'caption']); ?>

																		</div>
																		<?php $__errorArgs = ['caption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																		
																	</div>
																</div>
															

																<div class="row">
														
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Banner Type <span class="text-red">*</span></label>
																		<select class="form-control dropfill" id="banner_type"  name="banner_type" >

																		<?php if($banner_types && count($banner_types) > 0): ?>
																		<?php $__currentLoopData = $banner_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																		<option value="<?php echo e($kv['id']); ?>"  <?php if($banner['banner_id'] ==$kv['id']) { echo 'selected'; }else { echo 'disabled="true"'; } ?>
																		><?php echo e($kv['title']); ?></option>
																		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																		<?php endif; ?>

																		</select>
																		</div>
																		<p style="color: red" id="errNm3"></p>
																		<?php $__errorArgs = ['banner_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																		
																	</div>

																	<div class="col" >
																		<div class="form-group">
																			<label class="form-label">Media Type <span class="text-red">*</span></label>
																		<?php if($banner['banner_id'] ==1){ $mtype = array('image' => 'Image', 'video' => 'Video'); }else {
																			$mtype =array('image' => 'Image');
																		}  ?>
																			<?php echo Form::select('media_type', $mtype, $banner['media_type'],['class' => 'form-control','required','id'=>'media_type']); ?>

																		</div>
																		<?php $__errorArgs = ['media_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																		
																	</div>
																</div>
																<div class="row">
																	<?php if($banner['media_type'] =="image") { $img_div = "style='display:block;'";$vdo_div = "style='display:none;'"; }else { $img_div = "style='display:none;'";$vdo_div = "style='display:block;'"; } ?>

															<div class="col">
															<div class="form-group" id="image_type" <?php echo $img_div; ?> >
															<!-- <label class="form-label">Image<span class="text-red">*</span></label> -->

															<?php $n_img = 0; $k=0; ?>
															    <div class="card-header mb-4"><div class="card-title">Images <span class="text-red">*</span></div></div>

																<?php if($banner['media_type'] =="image" && $banner['media'] !=""): ?>
																<input type="hidden" id="img_hid" value="1">
																<?php $img_arr = explode(",",$banner['media']); ?>
																<div id="img_rows" class="col-12 mb-4 img_rows">
																<?php $__currentLoopData = $img_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<?php $k++; ?>
																<div class="col-md-3 col-sm-6 mb-3 fl imgdivs imgdiv<?php echo e($k); ?>" >
																    
																    <img src="<?php echo e(config('app.storage_url').'/app/public/banner/'.$img); ?>" alt="Banner Image" height="150px" />
																	<a id="delete_existing" data-eid="<?php echo e($k); ?>" class="delete_existing"><i class="fa fa-trash"></I></a>
																</div>
																
																 <?php echo e(Form::hidden('existing[]',$img,['id'=>'img_eid'.$k,'class'=>'img_eid'])); ?>

																
																<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																</div>
																<?php else: ?>
																<input type="hidden" id="img_hid" value="0">
																<?php endif; ?>
																<?php if($banner['banner_id'] !=2){ $hide_more =""; }else{ $hide_more =""; } ?>
															    <div id="prd_imgs" class="ifremoved" <?php echo $hide_more; ?>>
															        <div id="img_row_0" class="col-12 fl img_row">
															            <div class="col-lg-6 fl">
															                <div class="form-group">
															                    <?php echo e(Form::hidden('imgId[]',0,['id'=>'img_id_'.$n_img])); ?>

															                    <?php echo e(Form::file('image[]',['id'=>'image_'.$n_img,'class'=>'form-control img','placeholder'=>'Choose Image','accept'=>'image/*'])); ?>

															                </div>
															            </div>
															        <div class="col-lg-5 col-8 fl">
															                <div class="form-group">
															                    <img src="" alt="Image" id="image_<?php echo e($n_img); ?>_img" class="no-disp" height="90" />
															                </div>
															            </div> <?php $n_img++; ?>
															            <div class="clr"></div>
															        </div>
															            <div class="clr"></div>
															            <p style="color: red" id="errNm1"></p>
															    </div>
															    <div class="clr"></div>
															    <div class="col-12 text-right" <?php echo $hide_more; ?>>
															        <button id="add_more" class="mt-4 mb-4 btn btn-info btn-sm" type="button"><i class="fa fa-plus mr-1"></i>Add More</button>
															    </div>


															<div id="add_more_img" class="d-none">
															    <div id="img_row_id" class="col-12 fl img_row">
															        <div class="col-lg-6 fl">
															            <div class="form-group">
															                <?php echo e(Form::file('image[]',['id'=>'image_file_id','class'=>'form-control img','placeholder'=>'Choose Image','accept'=>'image/*'])); ?>

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
															        </div><?php $n_img++; ?>
															        <div class="clr"></div>
															    </div>
															</div>
															</div>
															<div class="form-group " id="video_type" <?php echo $vdo_div; ?> >
															<label class="form-label">Video Link <span class="text-red">*</span></label>

															<?php echo Form::text('media_link', $banner['media'], ['class' => 'form-control','rows' => 3,'id'=>'media_link','placeholder'=>'Eg: youtube.com/embed/ScMzIvxBSi4']); ?>

															</div>
															</div>
															
															

															</div>
																<div class="row">
																	
																	 <div class="col">
																		<div class="form-group">
																			<label class="form-label">Button Text </label>
																			
																			<?php echo Form::text('btn_text', $banner['btn_label'], ['class' => 'form-control','rows' => 3,'id'=>'btn_text']); ?>

																		</div>
																		<?php $__errorArgs = ['btn_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																	</div> 
																	 <div class="col">
																		<div class="form-group">
																			<label class="form-label">Button Link </label>
																			
																			<?php echo Form::text('btn_link', $banner['btn_link'], ['class' => 'form-control','rows' => 3,'id'=>'btn_link']); ?>

																		</div>
																		<?php $__errorArgs = ['btn_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																	</div> 

																</div>	


																<div class="row">
																	
																	<div class="col-md-6" id="alt_text_div">
																		<div class="form-group">
																			<label class="form-label">Alt Text </label>
																			
																			<?php echo Form::text('alt_text', $banner['alt_text'], ['class' => 'form-control','rows' => 3,'id'=>'alt_text']); ?>

																		</div>
																		<?php $__errorArgs = ['alt_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																	</div> 
																	<div class="col-md-6" id="status_div">
																		<div class="form-group">
																			<label class="form-label">Status <span class="text-red">*</span></label>
																	
																			<?php echo Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), $banner['is_active'],['class' => 'form-control','required','id'=>'coupon_status']); ?>

																		</div>
																		<?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																	</div>
																	
																	
																</div>
																
																

																
															</div>
														</div>
														
														<div class="row">
															<div class="col d-flex justify-content-end">
																<a href="<?php echo e(url('/admin/banners')); ?>"  class="mr-2 btn btn-secondary" >Cancel</a>
															<input class="btn btn-primary" type="submit" id="frontval" value="Save Changes">
															</div>
														</div>
													
												</div>
												<?php echo e(Form::close()); ?>


													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


								

					</div>
				</div><!-- end app-content-->
            </div>
<style type="text/css">
	.radio-opts {
		margin-top: 10px;
	}

.chosen-container-multi .chosen-choices {
    padding: 4px 10px;
    border: 1px solid #e3e4e9;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    border-radius: 5px;
}
.delete_existing {
	position: absolute;
    right: 30px;
    border: 1px solid white;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    background: white;
    top: 5px;
    cursor: pointer;
}
.delete_existing i {
	display: block;
	text-align: center;
	margin: auto;
	padding-top: 5px;
	}
	.img_rows img  {
		text-align: center;
    margin: auto;
    display: block;
	}

</style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->
<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>

<script src="<?php echo e(URL::asset('admin/assets/js/chosen.jquery.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(URL::asset('admin/assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/bootstrap-datetimepicker.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/datatables.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/bootstrap-datepicker.js')); ?>"></script>
		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/comboTreePlugin.js')); ?>"></script>
<script type="text/javascript">

  function readURL(input) { 
        if (input.files && input.files[0]) { 
        	$("#errNm1").empty();
            var reader = new FileReader(); 
            reader.onload = function (e) { $('#bannerForm img#'+input.id+'_img').attr('src', e.target.result); $('#bannerForm #'+input.id+'_img').show(); }
            reader.readAsDataURL(input.files[0]);
        }
    }

	jQuery(document).ready(function(){


 var row      =   parseInt('<?php echo e($n_img); ?>'); 
        $('body').on('click','#add_more',function(){ 
            var htmlContent             =   $('#add_more_img').html();
            htmlContent                 =   htmlContent.replace('img_row_id','img_row_'+row);
            htmlContent                 =   htmlContent.replace('img_id_id','img_id_'+row);
            htmlContent                 =   htmlContent.replace('image_file_id','image_'+row);
            htmlContent                 =   htmlContent.replace('image_disp_id','image_'+row+'_img');
            htmlContent                 =   htmlContent.replace('del_img_id','del_img_'+row);
            $('#bannerForm #prd_imgs').append(htmlContent); row++;
        });

         $('body').on('click','#bannerForm .del_img.del',function(){
            var id      =   this.id.replace('del_img_',''); 
            $('#bannerForm #img_row_'+id).remove();
        });
        
        $("body").on('change','#bannerForm input.img',function(){ readURL(this); });

$("#frontval").click(function(){

$("#bannerForm").validate({
	ignore: [],
rules: {

caption : {
required: true
},
"image[]": {
	required: function(){
return (''+$("#media_type option:selected").val() =="image" && $("#img_hid").val() ==0) ;
},

},
"media_link": {
required: '#media_type option[value="video"]:selected',

},
"btn_text": {
required: function(){
return (''+$("#btn_link").val() !="") ;
}
},



},

messages : {
caption: {
required: "Caption is required."
},


"image[]": {
required: "Image is required."
},
media_link: {
required: "Link is required."

},
offer_value: {
required: "Offer Value is required."

},

btn_text: {
required: "Button Text is required."
}

},


 errorPlacement: function(error, element) {
 	 $("#errNm1").empty();
 	 console.log($(error).text());
            if (element.attr("name") == "image[]" ) {
                $("#errNm1").text($(error).text());
                
            }else if (element.attr("name") == "sale_start" ) {
                $("#errNm3").text($(error).text());
                
            }else {
               error.insertAfter(element)
            }
        },

});
});



	 $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
       startDate: new Date()
  }).datepicker('update', new Date());




	// Prompt
	$(".deletemodule").on("click", function(e){

		var moduleid = jQuery(this).parents("tr").find("#moduleid").data("value");
		$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this module?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){



			if (inputValue == true) {
			 $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/modules/delete")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", id: moduleid},
            success: function (data) {
            	// alert(data);
            	if(data ==1){
            		location.reload();
            	}
            
            }
        });

			}
		});
	});

	});
</script>

<script type="text/javascript">
    $(document).ready(function(){
            <?php if(Session::has('message')): ?>
            <?php if(session('message')['type'] =="success"): ?>
            
            toastr.success("<?php echo e(session('message')['text']); ?>"); 
            <?php else: ?>
            toastr.error("<?php echo e(session('message')['text']); ?>"); 
            <?php endif; ?>
            <?php endif; ?>
            
            <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            toastr.error("<?php echo e($error); ?>"); 
            
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
    });
    </script>


<script type="text/javascript">


$(document).ready(function(){
$('body').on('click','.delete_existing',function(){  // alert('sss');
            var id          =   $(this).data("eid"); 
  
            swal({
                title: "Delete Confirmation",
                text: "Are you sure you want to delete this Image?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) { 
                   $("#img_eid"+id).remove();
                   $(".imgdiv"+id).hide(500);
                   if($(".img_eid").length ==0){
                   	$("#img_hid").val(0);
                   	<?php if($banner['banner_id'] !=1){ ?> $(".ifremoved").show(); <?php  } ?>
                   }
                }
            });
        });



$(".chosen-select").chosen({
  no_results_text: "Oops, nothing found!"
});

$("#banner_type").change(function(){

var b_type =$(this).val();
if(b_type ==1){
	$("#add_more").show('1000');
	$("#alt_text_div").show('1000');
	$("#status_div").removeClass("col-md-offset-6");
	$("#media_type option[value='video']").removeAttr("disabled");
}else{
	//$("#add_more").hide('1000');
	$("#alt_text_div").hide('1000');
	$("#status_div").addClass("col-md-offset-6");
	$('#media_type option[value="video"]').attr("disabled",true);

$("#bannerForm #prd_imgs .img_row:not(#img_row_0)").remove();
}

});

$("#media_type").change(function(){

var media_type =$(this).val();

if(media_type == "video"){
	$("#image_type").hide('1000');
	$("#video_type").show('1000');
	<?php 
if($banner['media_type'] == "image"){ ?>
$("#media_link").val("");

<?php }	?>
}else{
	$("#image_type").show('1000');
	$("#video_type").hide('1000');
}

});


});

 function date_check() 
    {
      var sdate=$("[name='valid_from']").val();
      var tdate=$("[name='valid_to']").val();
      
      $('#valid_from').datepicker('setStartDate',new Date(sdate));
      if(sdate && tdate)
      {
        var d1 = Date.parse(sdate);
        var d2 = Date.parse(tdate);
        if (d1 > d2) 
        {
          $("[name='valid_to']").val(sdate);
          $('#valid_to').datepicker('setStartDate',new Date(sdate));
        }
      }
      
    }

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/banners/edit.blade.php ENDPATH**/ ?>