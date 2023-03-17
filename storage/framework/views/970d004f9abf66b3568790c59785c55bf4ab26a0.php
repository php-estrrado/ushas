
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
		
		<style>
		    .input-group-addon {
		        margin-top: -1px !important;
		        border-right:1px solid #e3e4e9 !important;
		        
		    }
		</style>
		
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Ecom Benefits</a></li>
									<li class="breadcrumb-item"><a href="<?php echo e(url('admin/coupons')); ?>"><i class="fe fe-grid mr-2 fs-14"></i>Coupons</a></li>
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
													<?php echo e(Form::open(array('url' => "admin/coupons/save", 'id' => 'couponForm', 'name' => 'userForm', 'class' => '','files'=>'true'))); ?>

												<?php echo e(Form::hidden('id',0,['id'=>'couponid'])); ?>

												<div class="py-1">
													
														<div class="row">
															<div class="col">
																<div class="row">

																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="glo_lang_cid" required>
                                                                    
                                                                    <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($lang->id); ?>"><?php echo e($lang->glo_lang_name); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
																		</div>
																		
																	</div>

																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Coupon Title <span class="text-red">*</span></label>
																			
																			<?php echo Form::text('coupon_title', null, ['class' => 'form-control','required','id'=>'coupon_title']); ?>

																		</div>
																		<?php $__errorArgs = ['coupon_title'];
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
																			<label class="form-label">Description <span class="text-red">*</span></label>
																			
																			<?php echo Form::textarea('coupon_desc', null, ['class' => 'form-control','rows' => 3,'id'=>'coupon_desc']); ?>

																		</div>
																		
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Category </label>
																		
																			<select class="form-control" name="category_id" id="category_id" required onchange="loadsubcat()">
																			<option value="0">Select Category</option>


																			<?php if($categories && count($categories) > 0): ?>
																			<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

																			
																			<option value="<?php echo e($row['category_id']); ?>"><?php echo e($row['cat_name']); ?></option>
																			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																			<?php endif; ?>
																			</select>
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																	
																			<label class="form-label">Subcategory </label>
																			<input type="text" id="sub-category-id" placeholder="Type to filter" name="subcat_id" autocomplete="off" hidden />
																			 <input type="text" id="sub-category-drop" class="form-control " placeholder="Select Subcategory" readonly style="background-color: #fff !important;">
																		</div>
																	</div>
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																		<label class="form-label">Image </label>
																		<input type="file" name="image" class="form-control" id="image" accept="image/png, image/jpeg">
																		<?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																		
																			<!-- <select class="form-control" name="seller_id" id="seller_id" required >
																			<option value="0">Select Shop</option>


																			<?php if($sellers && count($sellers) > 0): ?>
																			<?php $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

																			
																			<option value="<?php echo e($row['seller_id']); ?>"><?php echo e($row['store_name']); ?></option>
																			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																			<?php endif; ?>
																			</select> -->
																			
																			

																		
																		</div>
																	</div>
																		<div class="col-sm-4 col-md-6 mb-3">
                    <img id="image_img" src="<?php echo e(url('storage/app/public/default.png')); ?>" alt="coupon_img" style="height: 100px;" />
               
																	</div>
																	
																	
																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Purchase Type </label> <div class="radio-opts">
																		
                          <input class="purchase_type" id="purchase_type" type="radio"  name="purchase_type" value="number" checked /> Purchase Number &nbsp;&nbsp;
                          <input class="purchase_type"  type="radio" id="purchase_type" name="purchase_type" value="amount"  /> Purchase Amount  &nbsp;&nbsp;
                        </div>
																			
																		</div>
																		
																	</div>

																	<div class="col">
																		<div class="form-group">
														<div  class="purchase_type_options purchase-number" style="display:block;">
														<label class="form-label" for="purchase_number" >Purchase Number <span class="text-red">*</span></label>
														
														<input min="1" step="1" type="number" max="9999" name="purchase_number" id="purchase_number" class="form-control" />

														</div>

														<div  class="purchase_type_options purchase-amount" style="display:none;">
														<label class="form-label" for="purchase_amount">Purchase Amount <span class="text-red">*</span></label>
													
														<input type="number" min="0" max="9999" name="purchase_amount" id="purchase_amount" class="form-control"  />

														</div>
																			

																		</div>
																		<?php $__errorArgs = ['purchase_number','purchase_amount'];
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
																			<label class="form-label">Min. Order Amount <span class="text-red">*</span></label>
																	
																			<?php echo Form::number('ofr_min_amount', null, ['class' => 'form-control','required','id'=>'ofr_min_amount','min'=>0,'max'=>999999]); ?>

																		</div>
																		<?php $__errorArgs = ['ofr_min_amount'];
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
															<label class="form-label">Offer Value <span class="text-red">*</span></label>

															<?php echo Form::number('ofr_value', null, ['class' => 'form-control','required','id'=>'ofr_value','min'=>0,'max'=>999999]); ?>

															</div>
															<?php $__errorArgs = ['ofr_value'];
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
															<label class="form-label">Offer Value Type </label>

															<select name="ofr_value_type" id="ofr_value_type" class="form-control">
																<option value="percentage">Percentage</option>
																<!--<option value="amount">Amount</option>-->
																
															</select>
															</div>
															</div>
															<div class="col">
															<div class="form-group">
															<label class="form-label">Offer Type </label>

															<select name="ofr_type" id="ofr_type" class="form-control">
																<option value="cashback">Cashback</option>
																<option value="discount">Discount</option>
																
															</select>
															</div>
															</div>

															</div>

															<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Code (6 characters) <span class="text-red">*</span></label>
														<div class="input-group">
												<input type="text" class="form-control" maxlength="6" minlength='6' required name="ofr_code" id="ofr_code" placeholder="Click to generate...">
												<span class="input-group-append">
													<button class="btn btn-primary" onClick="couponCode(6);" type="button">Generate</button>
												</span>
											</div>
																			

																		</div>
																		<p style="color: red" id="errNm1"></p>
																		<?php $__errorArgs = ['ofr_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
																	<p style="color: red" class="error" id="errNm1"><?php echo e($message); ?></p>
																	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
																	</div>
																	
																	
																</div>

																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label class="form-label">Validity Type </label> <div class="radio-opts">
																		
                          <input class="validity_type" id="validity_type" type="radio"  name="validity_type" value="range" checked /> Date Range   &nbsp;&nbsp;
                          <input class="validity_type" id="validity_type" type="radio" name="validity_type" value="days"  />  Number of Days 
                        </div>
																		</div>
																	</div>

																	<div class="col">
																		<div class="form-group">
														<div  class="validity_type_options valid-range" style="display:block;">
															<label class="form-label" for="valid_from" >Validity Range <span class="text-red">*</span></label>
															<div class="row">
															<div class="col">
																	<div id="valid_from"  class="datepicker input-group date"
															data-date-format="yyyy-mm-dd">
															<input class="form-control" name="valid_from" type="text" readonly  onchange="date_check()" />
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															</div>
															</div>
															<div class="col">
																	<div id="valid_to" class="datepicker input-group date"
															data-date-format="yyyy-mm-dd">
															<input class="form-control"  name="valid_to" type="text" readonly  onchange="date_check()" />
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
															</div>
															</div>
														</div>
														
													

														</div>

														<div  class="validity_type_options valid-days" style="display:none;">
														<label class="form-label" for="valid_days">Validity Days <span class="text-red">*</span></label>
													
														<input type="number" min="0"  max="9999" name="valid_days" id="valid_days" class="form-control"  />

														</div>
																			

																		</div>
																		<p style="color: red" id="errNm2"></p>
																		<?php $__errorArgs = ['valid_days','valid_from','valid_to'];
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
																			<label class="form-label">Status <span class="text-red">*</span></label>
																	
																			<?php echo Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'coupon_status']); ?>

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
</style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->
<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>

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
	jQuery(document).ready(function(){


$("#frontval").click(function(){

$("#couponForm").validate({
rules: {

coupon_desc : {
required: true
},
image : {
required: true
},

purchase_number: {
required: '#purchase_type[value="number"]:checked',
number: true,
min: 1
},
purchase_amount: {
required: '#purchase_type[value="amount"]:checked',
number: true,
min: 1
},
ofr_min_amount : {
required: true,
min: 1
},
ofr_value : {
required: true,
min: 1
},

valid_from: {
required: '#validity_type[value="range"]:checked',
},
valid_to: {
required: '#validity_type[value="range"]:checked',
},
valid_days: {
required: '#validity_type[value="days"]:checked',
min: 1
},

},

messages : {
coupon_title: {
required: "Coupon Title is required."
},
coupon_desc: {
required: "Coupon Description is required."
},
purchase_number: {
required: "Purchase Number is required.",
min: "Purchase Number must be greater than 0"
},
purchase_amount: {
required: "Purchase Amount is required.",
min: "Purchase Amount must be greater than 0"
},
ofr_min_amount: {
required: "Minimum Order Amount is required.",
min: "Minimum Order Amount must be greater than 0"
},
ofr_value: {
required: "Offer Value is required.",
min: "Offer Value must be greater than 0"
},
ofr_code: {
required: "Offer Code is required.",
maxlength:"Offer Code must be 6 characters",
minlength:"Offer Code must be 6 characters",
},
valid_from: {
required: "Validity From Date is required."
},
valid_to: {
required: "Validity To Date is required."
},

valid_days: {
required: "Validity Days is required.",
min: "Validity Days must be greater than 0"
},
image: {
required: "Image is required."
},

},
 errorPlacement: function(error, element) {
 	 $("#errNm1").empty();
            if (element.attr("name") == "ofr_code" ) {
                $("#errNm1").text($(error).text());
                
            }else if (element.attr("name") == "valid_from" || element.attr("name") == "valid_to" ) {
                $("#errNm2").text($(error).text());
                
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


var instance = $('#sub-category-drop').comboTree({
collapse:true,
cascadeSelect:true,
isMultiple: false
});

 function loadsubcat(clear='')
    {
        var category_id=$("#category_id").val();
        
        if(clear!='1')
        {
            $("#sub-category-id").val('');
        }
        
         $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/tags/subcategory")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", category_id: category_id},
            success: function (data) {
            	var obj = JSON.parse(data);
            
            	console.log(obj);
            	 var obj = JSON.parse(data);
            if(obj.subdata.length >=1)
            {
               $('#sub-category-drop').attr("placeholder", "Select subcategory"); 
            }
            else
            {
                $('#sub-category-drop').attr("placeholder", "No subcategory to display"); 
            }
            instance.setSource(obj.subdata);
            if($("#sub-category-id").val())
            {
                var selectionIdList = new Array($("#sub-category-id").val());
                instance.setSelection(selectionIdList);

            }
            
            }
        });
        
        
        
    }
    $('#sub-category-drop').on('change',function()
        {
            if(instance.getSelectedIds())
            {
                $("#sub-category-id").val(instance.getSelectedIds()[0]);
            }
        });

function couponCode(length) {
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
    var cpn = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        cpn += chars.charAt(i);
    }
    $("#ofr_code").val(cpn);
}

$(document).ready(function(){

$("body").on('change','#couponForm #image',function(){
 readURL(this);
        $('#image_img').val(this);
     });



$(".purchase_type").click(function(){
$(".purchase_type_options").hide('1000');    
$(".purchase-"+$(this).val()).show('1000');
});

$(".validity_type").click(function(){
$(".validity_type_options").hide('1000');    
$(".valid-"+$(this).val()).show('1000');
});



function readURL(input) { 
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) { $('body #'+input.id+'_img').attr('src', e.target.result); $('body #'+input.id+'_img').show(); }
            reader.readAsDataURL(input.files[0]);
        }
    }


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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/benefits/coupons/create.blade.php ENDPATH**/ ?>