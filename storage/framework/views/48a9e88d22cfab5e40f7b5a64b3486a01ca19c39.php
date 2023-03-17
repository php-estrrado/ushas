
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">View Customer</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Customer</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="<?php echo e(route('admin.category')); ?>"><?php echo e($title); ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">View Customer</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
							</div>
						</div>
                    
						<!-- Row -->
				<div class="row flex-lg-nowrap">
					<div class="col-12">
						<div class="row flex-lg-nowrap">
							<div class="col-12 mb-3">
								<div class="e-panel card">
									<div class="card-body">
										<div class="e-table">
											<div class="table-responsiv table-lg mt-3">
														<div class="row">

															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">Name:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php echo e($customer->info->first_name); ?> <?php echo e($customer->info->middle_name); ?> <?php echo e($customer->info->last_name); ?></p>
																   </div>
																</div>
																<div class="form-group row">
																	<label class="form-label col-md-4">Email:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php echo e($customer->custEmail($customer->email)); ?></p>
																</div>
																</div>

																<div class="form-group row">
																	<label class="form-label col-md-4">Phone:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php echo e($customer->custPhone($customer->phone)); ?></p>
																</div>
																</div>
																		
															</div>
															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">Invited By:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php if($customer->invited_by>0): ?><?php echo e($customer->invite($customer->invited_by)); ?><?php endif; ?></p>
																</div>
																</div>
																<div class="form-group row">
																	<label class="form-label col-md-4">Status:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php if($customer->is_approved==0): ?><span class="badge badge-default">Pending</span><?php endif; ?>
                                            <?php if($customer->is_approved==2): ?>
                                            <span class="badge badge-danger">Rejected</span><?php endif; ?></p>
																</div>
																</div>
																<div class="form-group row">
																	<label class="form-label col-md-4">Created on:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php echo e(date('d M Y',strtotime($customer->created_at))); ?></p>
																</div>
																</div>
																		
															</div>

															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">PAN Number:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php if($customer->info): ?><?php echo e($customer->info->pan_number); ?><?php endif; ?></p>
																</div>
																</div>
	
															</div>

															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">GST Number:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php if($customer->info): ?><?php echo e($customer->info->gst_number); ?><?php endif; ?></p>
																</div>
																</div>
	
															</div>
															
															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">PAN:</label>
																	<div class="col-md-8">
																<?php if($customer->info->pan_file!=''): ?>
																<img alt="User Avatar" class="rounded-circle border p-0" style="width:128px;height:128px;" src="<?php echo e(config('app.storage_url').'/app/public/customer_profile/pan/'.$customer->info->pan_file); ?>">
																<?php endif; ?>
																</div>
																</div>
															</div>

															
															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">GST:</label>
																	<div class="col-md-8">
																<?php if($customer->info->gst_file!=''): ?>
																<img alt="User Avatar" class="rounded-circle border p-0" style="width:128px;height:128px;" src="<?php echo e(config('app.storage_url').'/app/public/customer_profile/gst/'.$customer->info->gst_file); ?>">
																<?php endif; ?>
																</div>
																</div>
															</div>
															
														</div>
														
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															    <button type="button" class="mr-2 btn btn-secondary backtoview" >Back</a>  
															
															</div>
														</div>

													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


			<?php /**PATH /home/qaushas/public_html/resources/views/admin/customer/request/view.blade.php ENDPATH**/ ?>