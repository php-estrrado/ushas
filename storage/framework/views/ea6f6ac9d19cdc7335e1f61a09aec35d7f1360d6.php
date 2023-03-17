
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
				<link href="<?php echo e(URL::asset('admin/assets/css/combo-tree.css')); ?>" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Ecom Benefits</a></li>
									
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
														
<?php echo e(Form::open(array('url' => "admin/settings/save", 'id' => 'settingsForm', 'name' => 'settingsForm', 'class' => '','files'=>'true'))); ?>


<?php if(isset($settings['id'])): ?> 
<input type="hidden" name="id" value="<?php echo e($settings['id']); ?>">	
<?php else: ?>
<input type="hidden" name="id" value="0">	
<?php endif; ?>
								
<div class="row mt-4">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Settings</h3>
		</div>
		
	</div>
	<div class="col-lg-12">
		<div class="expanel expanel-default">
			<div class="expanel-heading">
				<h3 class="expanel-title">Refund Deduction Options</h3>
			</div>
				
			<div class="row">
				<div class="col-lg-6">
					<div class="expanel-body">
						<div  class="referral-points" >
							<label class="form-label" for="refund_deduction" >Refund Deduction (%) <span class="text-red">*</span></label>
							<input min="1" step="1" type="number" name="refund_deduction" id="refund_deduction" <?php if(isset($settings['refund_deduction'])): ?> value="<?php echo e($settings['refund_deduction']); ?>" <?php endif; ?> placeholder="Refund Deduction(%)" class="form-control"  />
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="expanel-body">
						<div  class="referral-points" >
							<label class="form-label" for="return_period" >Return Time Period (in hours) <span class="text-red">*</span></label>
							<input min="1" step="1" type="number" name="return_period" id="return_period" <?php if(isset($settings['return_period'])): ?> value="<?php echo e($settings['return_period']); ?>" <?php endif; ?> placeholder="Return Time Period(After Delivery)" class="form-control"  />
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6">
					<div class="expanel-body">
						<div  class="referral-points" >
							<label class="form-label" for="point_equivalent" >Points Equivalent (1 point equals to rupees ₹) <span class="text-red">*</span></label>
							<input min="1" step="1" type="number" name="point_equivalent" id="point_equivalent" <?php if(isset($settings['point_equivalent'])): ?> value="<?php echo e($settings['point_equivalent']); ?>" <?php endif; ?> placeholder="Points Equivalent(₹)" class="form-control"  />
						</div>
					</div>
				</div>
			</div>
		</div>


														<div class="row">
															<div class="col d-flex justify-content-end">
															
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->
<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/bootstrap-datepicker.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/comboTreePlugin.js')); ?>"></script>


<script type="text/javascript">
	jQuery(document).ready(function(){


$("#frontval").click(function(){

$("#settingsForm").validate({
rules: {


refund_deduction : {
required: true,
min: 0
},
return_period : {
required: true,
min: 0
},




},

messages : {

refund_deduction: {
required: "Refund Deduction is required.",
min: "Refund Deduction must be greater than 0"
},
return_period: {
required: "Return Time Period is required.",
min: "Return Time Period must be greater than 1hr"
},

 errorPlacement: function(error, element) {
 	 $("#errNm1").empty();
            if (element.attr("name") == "ofr_code" ) {
                $("#errNm1").text($(error).text());
                
            }else {
               error.insertAfter(element)
            }
        },

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

loadOptions();



});

function loadOptions(){

var cu_val = $("[name='rwd_type']:checked").val();
$(".reward_type_options").hide('1000');  
if(cu_val == 3) {
$(".reward_type_options").show('1000'); 
}else {
$(".points_"+cu_val).show('1000');	
} 

}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/settings/view.blade.php ENDPATH**/ ?>