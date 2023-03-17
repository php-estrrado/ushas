
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl alert css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />

        <!--INTERNAL Select2 css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/select2/select2.min.css')); ?>" rel="stylesheet" />

        <!-- INTERNAL File Uploads css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/fancyuploder/fancy_fileupload.css')); ?>" rel="stylesheet" />
        <!-- INTERNAL File Uploads css-->
        <link href="<?php echo e(URL::asset('admin/assets/plugins/fileupload/css/fileupload.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>

						<!--Page header-->
                        <div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo e(url('/admin/customer')); ?>"><i class="fe fe-grid mr-2 fs-14"></i>Customer</a></li>

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
<?php $currency = getCurrency()->name; ?>
						<!--/app header-->
						<div class="main-proifle">
							<div class="row">
								<div class="col-lg-7">
									<div class="box-widget widget-user">
										<div class="widget-user-image1 d-sm-flex">
										    <!--<?php if($info->profile_image!=''): ?>-->
										    <?php if(isset($info->profile_image)): ?>
										    <img alt="User Avatar" class="rounded-circle border p-0" style="width:128px;height:128px;" src="<?php echo e(config('app.storage_url').'/app/public/customer_profile/'.$info->profile_image); ?>">
										    <?php else: ?>
											<img alt="User Avatar" class="rounded-circle border p-0" src="<?php echo e(URL::asset('admin/assets/images/users/2.jpg')); ?>">
											<?php endif; ?>
											<div class="mt-1 ml-lg-5">
											    <?php 
                                                    $cust_id = date('y',strtotime($customer_mst->created_at)).date('m',strtotime($customer_mst->created_at)).str_pad($customer_mst->id, 6, "0", STR_PAD_LEFT); ?>
												<h4 class="pro-user-username mb-3 font-weight-bold"><?php echo e($info->first_name." ".$info->middle_name." ".$info->last_name); ?> <i class="fa fa-check-circle text-success"></i> <br><p class="pt-2" style="font-size:13px;">( <?php echo e("#".$cust_id); ?>  )</p></h4>

												<ul class="mb-0 pro-details">
                                                    <?php $__currentLoopData = $telecom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tele): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                    <?php if($tele->usr_telecom_typ_id==2): ?>
													<li><span class="profile-icon"><i class="fe fe-phone-call"></i></span><span class="h6 mt-3"><?php if($tele->country_code !="null"): ?><?php echo e($tele->country_code); ?> <?php endif; ?> <?php echo e($tele->usr_telecom_value); ?></span></li>
                                                    <?php endif; ?>
                                                    <?php if($tele->usr_telecom_typ_id==1): ?>
													<li><span class="profile-icon"><i class="fe fe-mail"></i></span><span class="h6 mt-3"><?php echo e($tele->usr_telecom_value); ?></span></li>
                                                    <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <li><span class="profile-icon"><i class="fe fe-calendar"></i></span><span class="h6 mt-3"><?php echo e(date('d M Y',strtotime($customer_mst->created_at))); ?></span></li>
                                                    <li><span class="profile-icon"><i class="fe fe-globe"></i></span><?php if($info->is_active==1): ?><span class="h6 mt-3 badge badge-primary"><?php echo e("Active"); ?></span><?php endif; ?> <?php if($info->is_active==0): ?><span class="h6 mt-3 badge badge-pill badge-danger" style="color:white"><?php echo e("Inactive"); ?></span><?php endif; ?></li>
                                                    <li><span class="profile-icon"><i class="fa fa-id-card" aria-hidden="true"></i></span><span class="h6 mt-3">PAN: <?php echo e(@$info->pan_number); ?> <?php if(isset($info->pan_file)): ?> <a href="<?php echo e(config('app.storage_url').'/app/public/customer_profile/pan/'.$info->pan_file); ?>" target="_blank" class="ml-5">View</a> <?php endif; ?></span></li>
                                                    <li><span class="profile-icon"><i class="fa fa-id-card" aria-hidden="true"></i></span><span class="h6 mt-3">GST: <?php echo e(@$info->gst_number); ?> <?php if(isset($info->gst_file)): ?> <a href="<?php echo e(config('app.storage_url').'/app/public/customer_profile/pan/'.$info->gst_file); ?>" target="_blank" class="ml-5">View</a> <?php endif; ?></span></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-5 col-md-auto">
									<div class="text-lg-right btn-list mt-4 mt-lg-0">
										<!--<a href="#" class="btn btn-light">Change Password</a>-->
										<!-- <button data-toggle="modal" data-target="#SignUp" class="btn btn-primary">Edit Profile</button> -->
									</div>
									<div class="mt-5">
										<div class="main-profile-contact-list row">
											<div class="media col-sm-4">
												<div class="media-icon bg-primary text-white mr-3 mt-1">
													<i class="fa fa-shopping-cart fs-18"></i>
												</div>
												<div class="media-body">
													<small class="text-muted">Orders</small>
													<div class="font-weight-bold number-font">
														<?php echo e(count($order)); ?>

													</div>
												</div>
											</div>
											<!--<div class="media col-sm-4">-->
											<!--	<div class="media-icon bg-secondary text-white mr-1 mt-1">-->
											<!--		<i class="las la-hand-holding-usd fs-18"></i>-->
											<!--	</div>-->
											<!--	<div class="media-body">-->
											<!--		<small class="text-muted">Wallet</small>-->
											<!--		<div class="font-weight-bold number-font">-->
											<!--			<?php if($wallet): ?>-->
											<!--			<?php if($wallet->wallet>0): ?>-->
											<!--			<?php echo e($currency); ?> <?php echo e($wallet->wallet); ?>-->
											<!--			<?php else: ?>-->
											<!--			<?php echo e($currency); ?> <?php echo e("0"); ?>-->
											<!--			<?php endif; ?>-->
											<!--			<?php else: ?>-->
											<!--			<?php echo e("0"); ?>-->
											<!--			<?php endif; ?>-->
											<!--		</div>-->
											<!--	</div>-->
											<!--</div>-->
											<div class="media col-sm-4">
												<div class="media-icon bg-info text-white mr-1 mt-1">
													<i class="fa fa-briefcase fs-18"></i>
												</div>
												<div class="media-body">
													<small class="text-muted">Purchased Amount</small>
													<div class="font-weight-bold number-font">
													<?php echo e($currency); ?> <?php echo e(round($sale_amt)); ?>

													
													</div>
												</div>
											</div>
											<div class="media col-sm-4">
												<div class="media-icon bg-info text-white mr-1 mt-1">
													<i class="fa fa-money fs-18"></i>
												</div>
												<div class="media-body">
													<small class="text-muted">Points</small>
													<div class="font-weight-bold number-font">
														<?php if(!empty($customer_points)): ?>
															<?php echo e($customer_points); ?>

														<?php else: ?>
															<?php echo e(0); ?>

														<?php endif; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="profile-cover">
								<div class="wideget-user-tab">
									<div class="tab-menu-heading p-0">
										<div class="tabs-menu1 px-3">
											<ul class="nav">
												<li><a href="#tab-7" class="active fs-14" data-toggle="tab">Address</a></li>
												<li><a href="#tab-8" data-toggle="tab" class="fs-14">Order Summary</a></li>
												<li><a href="#tab-9" data-toggle="tab" id="ord_hist" class="fs-14">Order History</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div><!-- /.profile-cover -->
						</div>
						<!-- Row -->
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="border-0">
									<div class="tab-content">
									    
										<div class="tab-pane active" id="tab-7">
											<div class="card">
												<div class="card-body border-top">
													<h5 class="font-weight-bold">Default Address</h5>
													
													<div class="main-profile-contact-list d-lg-flex">
														<?php if($customer_addr && count($customer_addr)>0): ?>
													
														<?php $__currentLoopData = $customer_addr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<?php if($address->is_default==1): ?>

																<div class="media-body">
																<h6 class="font-weight-bold mb-1"><?php echo e($address->name); ?></h6>
																 <?php if($address->address_1): ?><span><b>Street:</b> <?php echo e($address->address_1); ?></span><br><?php endif; ?>
																<?php if($address->address_2): ?><span><b>House/Apartment:</b> <?php echo e($address->address_2); ?></span><br><?php endif; ?>
																<?php if(getCities($address->city_id)): ?> <span><b>City:</b> <?php echo e(getCities($address->city_id)['city']); ?></span><br><?php endif; ?>
																<?php if(getCities($address->city_id)): ?> <span><b>State:</b> <?php if(isset(getCities($address->city_id)['state'])): ?><?php echo e(getCities($address->city_id)['state']); ?><?php endif; ?></span><br><?php endif; ?>
																<?php if(getCities($address->city_id)): ?> <span><b>Country:</b> <?php if(isset(getCities($address->city_id)['country'])): ?><?php echo e(getCities($address->city_id)['country']); ?><?php endif; ?></span><br><?php endif; ?>

															<?php if($address->pincode): ?><span><b>Pincode:</b> <?php echo e($address->pincode); ?></span><br><?php endif; ?>
																
																
																</div>

																
                                                        <?php endif; ?>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
														<?php else: ?>
													    <div class="media-body">	<p>No Address found.</p> </div>
														<?php endif; ?>
													</div>
												</div>


											</div>
										</div>
										<div class="tab-pane" id="tab-8">
											<div class="card p-5">
											    
												<!--Row-->
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="row mr-0 ml-0">
										<div class="col-xl-6 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
											<div class="card-body text-center">
												<p class="mb-1">Number of orders</p>
									            <h2 class="mb-1 font-weight-bold"><?php echo e($tot_order); ?></h2>
												<!--<span class="mb-0 text-muted"><span class="text-success"><i class="fa fa-caret-up  mr-1"></i> 2</span> Last month</span>-->
									        </div>
										</div>
										<div class="col-xl-6 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
											<div class="card-body text-center">
												<p class="mb-1">Purchase Amount</p>
									            <h2 class="mb-1 font-weight-bold"><?php echo e(round($sale_amt)); ?></h2>
									            <!--<span class="mb-0 text-muted"><span class="text-success"><i class="fa fa-caret-up  mr-1"></i> 250</span> Last month</span>-->
									        </div>
										</div>
										<!--<div class="col-xl-3 col-lg-6 col-sm-6 pr-0 pl-0 border-right">-->
										<!--	<div class="card-body text-center">-->
										<!--		<p class="mb-1">Cancelled Orders</p>-->
									 <!--           <h2 class="mb-1 font-weight-bold"><?php echo e($order_cancel); ?></h2>-->
									            <!--<span class="mb-0 text-muted"><span class="text-danger"><i class="fa fa-caret-down  mr-1"></i> 0</span> Last month</span>-->
									 <!--       </div>-->
										<!--</div>-->
										<!--<div class="col-xl-3 col-lg-6 col-sm-6 pr-0 pl-0 border-right">-->
										<!--	<div class="card-body text-center">-->
										<!--		<p class="mb-1">Amount Refunded</p>-->
									 <!--           <h2 class="mb-1 font-weight-bold"><?php echo e($order_refund); ?></h2>-->
									            <!--<span class="mb-0 text-muted"><span class="text-danger"><i class="fa fa-caret-up  mr-1"></i> 0</span> Last month</span>-->
									 <!--       </div>-->
										<!--</div>-->
										
									</div>
								</div>
							</div>
						</div>
						<!--End row-->
											</div>
										</div>
										<div class="tab-pane" id="tab-9">
                                            <div class="card">
                                            <div class="card-body">
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table id="order-table" class="order-table table table-striped table-bordered w-100 text-nowrap">
														    
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5"></th>
																	<th class="border-bottom-0 w-15">Order ID</th>
																	<th class="border-bottom-0 w-15">Qty</th>
                                                                    <th class="border-bottom-0 w-15">Amount</th>
																	<th class="border-bottom-0 w-15">Order Date</th>
                                                                    <th class="border-bottom-0 w-15">Tax Amount</th>
																	<th class="border-bottom-0 w-15">Shipping charge</th>
																	<th class="border-bottom-0 w-15">Seller</th>
																	<th class="border-bottom-0 w-15">Payment Method</th>
																	<th class="border-bottom-0 w-15">Payment Status</th>
																	<th class="border-bottom-0 w-15">Order Status</th>
																	<th class="border-bottom-0 w-30">Action</th>
																</tr>
															</thead>

															<tbody>
															    <?php if($order && count($order) > 0): ?>
                    											<?php $__currentLoopData = $order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    											<tr>
                                                                <td class="align-middle select-checkbox" id="">
                                                                    <label class="custom-control custom-checkbox">
                                                                    </label>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold">#<?php echo e($row->order_id); ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <?php
                                                                    $total=0;
                                                                    $items=$row->orderitem($row->id); 
                                                                    foreach($items as $item)
                                                                    {
                                                                    $total += $item->qty;
                                                                    }
                                                                    ?>
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold"><?php echo e($total); ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold"><?php echo e($row->g_total); ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold"><?php echo e(date('d-m-Y',strtotime($row->created_at))); ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold"><?php echo e($row->tax); ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold"><?php echo e($row->shiping_charge); ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold"><?php if($row->store($row->seller_id)): ?><?php echo e($row->store($row->seller_id)->store_name); ?> <?php endif; ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold">Stripe</h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold"> <?php echo e(ucfirst($row->payment_status)); ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                    <h6 class=" font-weight-bold"><?php echo e(ucfirst($row->order_status)); ?></h6>
                                                                    </div>
                                                                </td>
                                                                
                                                                <td class="align-middle">
                                                                    <?php if($row->order_status=="delivered"): ?>
                                                                    <div class="d-flex">
                                                                     
                                                                    <h6 class=" font-weight-bold"><a href="<?php echo e(url('admin/customer/invoice/')); ?>/<?php echo e($row->id); ?>" class="btn btn-sm btn-info">Invoice</a></h6>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </td>
                                                                </tr>
                                                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              													  <?php endif; ?>
															</tbody>
														</table>
													</div>
												</div>
                                            </div>
                                        </div><!--card-->
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div><!-- end app-content-->
			</div>

            <!-- Modal change password -->
			<div id="changepwd" class="modal fade" role="dialog">
                <div class="modal-dialog">

                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title text-center">Change Password</h3>
                            <button type="button" class="close" data-dismiss="modal">×</button>

                        </div>
                        <div class="modal-body" style="overflow: hidden;">
                            <div class="alert alert-danger" role="alert" style="display: none">
                            </div>
                            <div class="col-md-offset-1 col-md-12">
                                <form method="POST" id="changepwd" enctype="multipart/form-data">
                                   <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">New password<span class="text-red">*</span></label>
                                        <input type="text" name="password" id="password" class="form-control" placeholder="New Password">
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Confirm password<span class="text-red">*</span></label>
                                        <input type="text" name="c_password" id="c_password"  class="form-control" placeholder="Confirm Password">
                                    </div>
                                </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal regsiter -->
			<div id="SignUp" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title text-center">Edit Profile</h3>
                            <button type="button" class="close" data-dismiss="modal">×</button>

                        </div>
                        <div class="modal-body" style="overflow: hidden;">
                            <div class="alert alert-danger" role="alert" style="display: none">
                            </div>
                            

                            <div class="col-md-offset-1 col-md-12">
                                <form method="POST" id="editprofile" action="<?php echo e(url('admin/customer/update-profile/'.$customer_mst->id)); ?>" name="editprofile" enctype="multipart/form-data">
                                   <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">First Name<span class="text-red">*</span></label>
                                        <input type="text" name="first_name"  id="first_name" value="<?php echo e($info->first_name); ?>" class="form-control" placeholder="First name" >

                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Last Name<span class="text-red">*</span></label>
                                        <input type="text" name="last_name" id="last_name" value="<?php echo e($info->last_name); ?>" class="form-control" placeholder="Last name">

                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Status<span class="text-red">*</span></label>
                                        <select class="form-control select2" id="status" name="status">
                                            <option value="1" <?php if($info->is_active==1): ?><?php echo e("selected"); ?><?php endif; ?>>Active</option>
                                            <option value="0" <?php if($info->is_active==0): ?><?php echo e("selected"); ?><?php endif; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <?php $__currentLoopData = $telecom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tele): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($tele->usr_telecom_typ_id==1): ?>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Email<span class="text-red">*</span></label>
                                        <input type="email" name="email" id="email" value="<?php echo e($tele->usr_telecom_value); ?>" class="form-control" placeholder="Email" readonly>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($tele->usr_telecom_typ_id==2 && isset($tele->usr_telecom_value)): ?>
                                    <div class="form-group has-feedback">
                                        <label class= "form-label">Contact Number<span class="text-red">*</span></label>
                                        <div class="input-group">
                                        <div class="input-group-append" style="width:20%"><input type="text" value="<?php if($tele->country_code!='null'): ?><?php echo e($tele->country_code); ?><?php endif; ?>" class="form-control" placeholder=""  readonly ></div>
                                        <input type="number" name="number" id="number" min="0" value="<?php echo e($tele->usr_telecom_value); ?>" class="form-control"  placeholder="Contact number" readonly>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-group has-feedback">
                                        <label class="form-label">Profile Image <span class="text-red"></span></label>
                                        <input type="file" id="profile_img" class="form-control" accept=".jpg, .png, image/jpeg, image/png"  name="profile_img" />
                                        </div>
                                </div><!---col--->
                            </div><!---row-->

                                    <div class="row">
                                        <div class="col-xs-12 col-md-12 justify-content-end">
                                          <button type="submit" id="submitForm" class="btn btn-primary btn-prime white btn-flat fr">Save</button>
                                        </div>
                                    </div>
                        </form>
                        </div>
                    </div>

                </div>
            </div></div>
            <input type="hidden" id="ord_dt" value="0">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<!--INTERNAL Select2 js -->
<script src="<?php echo e(URL::asset('admin/assets/plugins/select2/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/select2.js')); ?>"></script>
<!-- INTERNAL Popover js -->
<script src="<?php echo e(URL::asset('admin/admin/assets/js/popover.js')); ?>"></script>

<!-- INTERNAL Sweet alert js -->
<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>

<!-- INTERNAl Data tables -->
		<!--<script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/ordertable-datatable.js')); ?>"></script>-->


<script type="text/javascript">

var myLink = document.getElementById('ord_hist');

    myLink.onclick = function(){
        var ord_dt = $('#ord_dt').val();
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "<?php echo e(URL::asset('admin/assets/js/datatable/tables/ordertable-datatable.js')); ?>"; 
        if (  ord_dt == 0 ) {
        $('#ord_dt').val(1);
        document.getElementsByTagName("head")[0].appendChild(script);
        }
        return false;
    }
    



	$(document).ready(function(){
jQuery.validator.addMethod("lettersonly", function(value, element) {
  return this.optional(element) || /^[a-z ]+$/i.test(value);
}, "Please enter valid name."); 

 $('body').on('click', '#submitForm', function(e){
     

$("#editprofile").validate({
    
rules: {

first_name : {
required: true,
lettersonly: true 
},

last_name: {
required: true,
lettersonly: true
},
number: {
required: true,
number: true,
minlength:7,
maxlength:15
},
email : {
required: true,
email: true,
},



},

messages : {
first_name: {
required: "First Name is required."
},
last_name: {
required: "Last Name is required."
},
number: {
required: "Contact Number is required.",
minlength: "Contact Number is invalid",
maxlength: "Contact Number is invalid"
},
email: {
required: "Email is required."
},
password: {
required: "Password is required.",
minlength: "Password must be greater than 8 digits.",
maxlength: "Password must be less than 20 digits."
},


},

});
});

});

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/customer/view_customer.blade.php ENDPATH**/ ?>