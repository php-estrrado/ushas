
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">View Customer</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Customer</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.category')}}">{{$title}}</a></li>
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
																	<p class="view_value">{{$customer->info->first_name}} {{$customer->info->middle_name}} {{$customer->info->last_name}}</p>
																   </div>
																</div>
																<div class="form-group row">
																	<label class="form-label col-md-4">Email:</label>
																	<div class="col-md-8">
																	<p class="view_value">{{$customer->custEmail($customer->email)}}</p>
																</div>
																</div>

																<div class="form-group row">
																	<label class="form-label col-md-4">Phone:</label>
																	<div class="col-md-8">
																	<p class="view_value">{{$customer->custPhone($customer->phone)}}</p>
																</div>
																</div>
																		
															</div>
															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">Invited By:</label>
																	<div class="col-md-8">
																	<p class="view_value">@if($customer->invited_by>0){{ $customer->invite($customer->invited_by) }}@endif</p>
																</div>
																</div>
																<div class="form-group row">
																	<label class="form-label col-md-4">Status:</label>
																	<div class="col-md-8">
																	<p class="view_value">@if($customer->is_approved==0)<span class="badge badge-default">Pending</span>@endif
                                            @if($customer->is_approved==2)
                                            <span class="badge badge-danger">Rejected</span>@endif</p>
																</div>
																</div>
																<div class="form-group row">
																	<label class="form-label col-md-4">Created on:</label>
																	<div class="col-md-8">
																	<p class="view_value">{{date('d M Y',strtotime($customer->created_at))}}</p>
																</div>
																</div>
																		
															</div>

															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">PAN Number:</label>
																	<div class="col-md-8">
																	<p class="view_value">@if($customer->info){{ $customer->info->pan_number }}@endif</p>
																</div>
																</div>
	
															</div>

															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">GST Number:</label>
																	<div class="col-md-8">
																	<p class="view_value">@if($customer->info){{ $customer->info->gst_number }}@endif</p>
																</div>
																</div>
	
															</div>
															
															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">PAN:</label>
																	<div class="col-md-8">
																@if($customer->info->pan_file!='')
																<img alt="User Avatar" class="rounded-circle border p-0" style="width:128px;height:128px;" src="{{ config('app.storage_url').'/app/public/customer_profile/pan/'.$customer->info->pan_file }}">
																@endif
																</div>
																</div>
															</div>

															
															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">GST:</label>
																	<div class="col-md-8">
																@if($customer->info->gst_file!='')
																<img alt="User Avatar" class="rounded-circle border p-0" style="width:128px;height:128px;" src="{{ config('app.storage_url').'/app/public/customer_profile/gst/'.$customer->info->gst_file }}">
																@endif
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


			