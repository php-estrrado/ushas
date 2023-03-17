
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/css/combo-tree.css')); ?>" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/quill/quill.snow.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(URL::asset('admin/assets/plugins/quill/quill.bubble.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								<!-- <div class="btn btn-list">
									<a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>
									<a href="#"  data-target="#user-form-modal" data-toggle="modal" class="btn btn-danger addmodule"><i class="fe fe-shopping-cart mr-1"></i> Add New</a>
								</div> -->
							</div>
						</div>
                        <!--End Page header-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">
<?php if(isset($loyality)){

  $id=$loyality->id;
  $point=$loyality->point;
  $value=$loyality->order_amount;
  $is_active=$loyality->is_active;

}else{
  $id="";
  $point="";
  $value="";
  $is_active="0";
}	
?>
							<!-- 	<?php if(Session::has('message')): ?>

								<div class="alert alert-<?php echo e(session('message')['type']); ?>" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e(session('message')['text']); ?></div>
								<?php endif; ?>
								<?php if($errors->any()): ?>
								<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e($error); ?></div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?> -->
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsiv table-lg mt-3">
														
					<form action="" method="POST" id="loyalityForm" enctype="multipart/form-data">
                     <?php echo csrf_field(); ?>
					 
					 
                        <div class="row">
						<div class="col-md-4">
						<div class="form-group">
							<div class="form-group">
											<label class="form-label">Points Awarded <span class="text-red">*</span></label>
											<div class="input-group">
												<input type="text" name="points" class="form-control" id="points" value="<?php echo e($point); ?>">
												<span class="input-group-append">
													<button class="btn btn-primary" type="button">%</button>
												</span>
											</div>
										</div>
							
							<input type="hidden" name="loyality_id" class="form-control" id="" value="<?php echo e($id); ?>">
							<span class="error"></span>
						</div>
						</div>
						
						<div class="col-md-4">
						<div class="form-group">
							<?php echo e(Form::label('','Min Order Amount',['class'=>''])); ?> <span class="text-red">*</span>
							<input type="text" name="value" class="form-control" id="value" value="<?php echo e($value); ?>">
							<span class="error"></span>
						</div>
						</div>
						<div class="col-md-12">
						<div class="form-group col-md-6">
							<div class="form-group">
							 <?php echo e(Form::label('status','Status',['class'=>''])); ?> <span class="text-red">*</span>
							<div class="col-12">
							<label class="custom-control custom-radio custom-control-sm ">
								<input type="radio" name="status" class='custom-control-input cus_radio' <?php echo e(($is_active=="1")? "checked" : ""); ?> value="1" >
								<span class="custom-control-label custom-control-label-sm"> Yes </span>
							</label>
							<label class="custom-control custom-radio custom-control-sm ">
							<input type="radio" name="status" class='custom-control-input cus_radio' value="0" <?php echo e(($is_active=="0")? "checked" : ""); ?> > 
							<span class="custom-control-label custom-control-label-sm">No</span>
							</label>
							</div><div class="clr"></div>
							</div>
						<span class="error"></span>
					</div>
					<span class="error"></span>
						</div>
						
                        
                     </div>
                     <div class="col d-flex justify-content-end">
                        <button type="submit" id="saveBtn" class="btn btn-primary mt-4 mb-0" >Save</button>
                     </div>
                  </form>

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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/jquery.dataTables.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/dataTables.bootstrap4.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/dataTables.buttons.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.bootstrap4.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/jszip.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/pdfmake.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/vfs_fonts.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.html5.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.print.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/js/buttons.colVis.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/dataTables.responsive.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/datatables.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/comboTreePlugin.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>       
	   <script src="<?php echo e(URL::asset('admin/assets/plugins/quill/quill.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/form-editor2.js')); ?>"></script>

<script type="text/javascript">
var base_path = "<?php echo e(url('/')); ?>";
$(document).ready(function(){
	
 $("#loyalityForm").validate();

 $('body').on('submit','#loyalityForm',function(e){ 
	e.preventDefault(); 
	
    //var myEditor = document.querySelector('#label_content')
	//var html = myEditor.children[0].innerHTML
	//$("#labelcontent").val(html);
	//if(html=='<p><br></p>')
			//{
				//$('#loyalityForm #label_content').parents('.form-group').find('.error').html("Please enter label");
				//return false;
			//}	
	//if ($("#catForm").valid() === true){           
        var formData = new FormData(this);
        $('#loyalityForm #saveBtn').attr('disabled',true);
        $('#loyalityForm #saveBtn').text('Validating...'); 
        $request= $.ajax({
            type: "POST",
            url: '<?php echo e(url("admin/loyalty/validate")); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
            if(data == 'success'){ 
                $('#loyalityForm #saveBtn').text('Saving...');
                $('#loyalityForm #saveBtn').attr('disabled',true);
                submitLabelForm(formData,'loyalityForm','saveBtn'); return false;
                return false;
            }else {
                var errKey = ''; var n = 0;
                $.each(data, function(key,value) { if(n == 0){ errKey = key; n++; }
					if(key == "tp_image") { $(".image.error").html(value); }else{
                        $('#loyalityForm #'+key).parents('.form-group').find('.error').html(value);
                    }
				});
				$('#loyalityForm #'+errKey).focus();
                $('#loyalityForm #saveBtn').attr('disabled',false);
                $('#loyalityForm #saveBtn').text('Save'); return false;
                
                    return false;
            }
			}
        });
	//	}
		return false; 
    });
});
	
	function submitLabelForm(postValues,form,button){
		// alert();
        $.ajax({
            type: "POST",
            url: '<?php echo e(url("admin/loyalty/save")); ?>',
            data: postValues,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) { 
              $('#loyalityForm #saveBtn').attr('disabled',false);
              $('#loyalityForm #saveBtn').text('Save');
			 
				if(data == 1){
                var msg = 'Loyality Point updated successfully'; 
                toastr.success(msg);
				}
				else{
                var msg = 'Loyality point update failed!'; 
                toastr.error(msg);
				}
				window.location.reload()
            } 
        });
    }
	
	




</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/loyalty_points/create.blade.php ENDPATH**/ ?>