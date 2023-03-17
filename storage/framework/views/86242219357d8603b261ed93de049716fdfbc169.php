
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Customer Credits Log</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo e(url('/admin/customer/credits')); ?>"><i class="fe fe-grid mr-2 fs-14"></i>Credits</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Customer Credits Log</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
							</div>
						</div>
                        <!--End Page header-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
						<div class="card custom-card">
									<div class="card-body">
										<div class="main-profile-contact-list d-lg-flex">
											<div class="media mr-4">
													<div class="media-icon bg-primary text-white  mr-3 mt-1">
														<i class="fa fa-user"></i>
													</div>
													<div class="media-body">
														<small class="text-muted">Customer Name</small>
														<div class="font-weight-normal1">
															<?php echo e($customer->first_name); ?> <?php echo e($customer->middle_name); ?> <?php echo e($customer->last_name); ?>

														</div>
													</div>
												</div>
												<div class="media mr-4">
													<div class="media-icon bg-primary text-white  mr-3 mt-1">
														<i class="las la-hand-holding-usd fs-18"></i>
													</div>
													<div class="media-body">
														<small class="text-muted">Credits Balance</small>

														<div class="font-weight-normal1">
															<?php $cr_bal = $credits->credit_limit - $credits->outstanding; ?>
														<?php if($credits->credit_limit > $cr_bal): ?>	<?php echo e(($cr_bal)); ?>

														<?php else: ?>
														<!-- <?php echo e($credits->credit_limit); ?> -->
														<?php echo e($cr_bal); ?>

														<?php endif; ?>
														</div>
													</div>
												</div>
												<div class="media mr-4">
													<div class="media-icon bg-primary text-white  mr-3 mt-1">
														<i class="las la-hand-holding-usd fs-18"></i>
													</div>
													<div class="media-body">
														<small class="text-muted">Credits Limit</small>
														<div class="font-weight-normal1">
															<?php echo e($credits->credit_limit); ?>

														</div>
													</div>
												</div>
												<div class="media mr-4">
													<div class="media-icon bg-primary text-white  mr-3 mt-1">
														<i class="las la-hand-holding-usd fs-18"></i>
													</div>
													<div class="media-body">
														<small class="text-muted">Credits Days</small>
														<div class="font-weight-normal1">
															<?php echo e($credits->credit_days); ?>

														</div>
													</div>
												</div>
										</div>
									</div>
								</div>
				<!-- Row-->
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Log</div>
									</div>
									<div class="card-body">
										<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table id="wallet-table" class="wallet-table table table-striped table-bordered w-100 text-nowrap">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5"></th>
																	<th class="border-bottom-0 w-20">Date</th>
																	<th class="border-bottom-0 w-20">Order ID</th>
																	<th class="border-bottom-0 w-20">Credit</th>
																	<th class="border-bottom-0 w-20">Debit</th>
																	
																</tr>
															</thead>

															<tbody>

																<?php if($transaction && count($transaction) > 0): ?>
                    											<?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


																<tr>
																	<td class="align-middle select-checkbox" data-value="<?php echo e($row->user_id); ?>">
																	<label class="custom-control custom-checkbox">
																		</label>
																	</td>
                                                                    <td class="align-middle" >
																		<h6 class=" font-weight-bold">
																			<?php echo e(date('d M Y',strtotime($row->created_at))); ?>

																		</h6>
                                                                       
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<h6 class=" font-weight-bold">
																		 <?php if($row->sales): ?>	<?php echo e($row->sales->order_id); ?> <?php else: ?> <?php echo e("-"); ?> <?php endif; ?>
																		</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<h6 class=" font-weight-bold">
																			<?php if($row->credit>0): ?>
																			<?php echo e($row->credit); ?>

																			<?php else: ?>
																			<?php echo e("-"); ?>

																			<?php endif; ?>
																		</h6>
                                                                        </div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<h6 class=" font-weight-bold">
																			<?php if($row->debit>0): ?>
																			<?php echo e($row->debit); ?>

																			<?php else: ?>
																			<?php echo e("-"); ?>

																			<?php endif; ?>
																		</h6>
                                                                        </div>
																	</td>
																	
																</tr>
																     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              													  <?php endif; ?>

															</tbody>
														</table>
													</div>
												</div>
									</div>
									<div class="card-footer">
										<div class="row">
											<div class="col d-flex justify-content-end">
											<a href="<?php echo e(route('customer.credits')); ?>"  class="mr-2 btn btn-secondary" >Back</a>			
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End row-->
				</div><!-- end app-content-->
            </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<!-- INTERNAl Data tables -->
		<script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/wallet_log-datatable.js')); ?>"></script>
	<script type="text/javascript">
    $(document).ready(function(){
        $('#customer_wallet').addClass("active");
          $('#a_c_w').addClass("active");
          $('#wallet').addClass("is-expanded");
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



	function delete_wallet(w_id){
	    
	   $('body').removeClass('timer-alert');
        swal({
            title: "Delete Confirmation",
            text: "Are you sure you want to delete this record?",
            // type: "input",
            showCancelButton: true,
            closeOnConfirm: true,
            confirmButtonText: 'Yes'
        },function(inputValue){
    if (inputValue == true) {
        $.ajax({
            type: "POST",
            url: '<?php echo e(url("/admin/customer/wallet-delete/")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", w_id: w_id},
            success: function (data) {
                location.reload();

            }
        });
        }
    });
    }
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/customer/credits/log_view.blade.php ENDPATH**/ ?>