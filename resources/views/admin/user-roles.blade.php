@extends('layouts.admin')

@section('page-header')
						<!--Page header-->
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">User Roles</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Apps</a></li>
									<li class="breadcrumb-item"><a href="#">User List</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">User Roles</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
									<a href="#"  data-target="#user-form-modal" data-toggle="modal" class="btn btn-danger"><i class="fe fe-shopping-cart mr-1"></i> Add New</a>
								</div>
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table class="table table-bordered border-top text-nowrap" id="example1">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5"></th>
																	<th class="border-bottom-0 w-20">User Role</th>
																	<th class="border-bottom-0 w-15">Created On</th>
																	<th class="border-bottom-0 w-30">Description</th>
																	<th class="border-bottom-0 w-10">Actions</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class="align-middle">
																		<label class="custom-control custom-checkbox">
																			
																			1
																		</label>
																	</td>
																	<td class="align-middle">
																		<div class="d-flex">
																			<span class="avatar brround avatar-md d-block" style="background-image: url({{URL::asset('assets/images/users/2.jpg')}})"></span>
																			<div class="ml-3 mt-1">
																				<h6 class="mb-0 font-weight-bold">Staff Role 1</h6>
																				<small class="">Active</small>
																			</div>
																		</div>
																	</td>
																	<td class="text-nowrap align-middle"><span>09 Dec 2017</span></td>
																	<td class="text-nowrap align-middle">
																		<p>Lorem ipsum dolor sit amet</p>
																	</td>
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			<button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>
																			<button class="btn btn-sm btn-success" type="button"><i class="fe fe-trash-2"></i></button>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td class="align-middle">
																		<label class="custom-control custom-checkbox">
																			
																			2
																		</label>
																	</td>
																	<td class="align-middle">
																		<div class="d-flex">
																			<span class="avatar brround avatar-md d-block" style="background-image: url({{URL::asset('assets/images/users/1.jpg')}})"></span>
																			<div class="ml-3 mt-1">
																				<h6 class="mb-0 font-weight-bold">Staff Role 2</h6>
																				<small class="">Inactive</small>
																			</div>
																		</div>
																	</td>
																	<td class="text-nowrap align-middle"><span>27 Jan 2018</span></td>
																	<td class="text-nowrap align-middle">
																		<p>Lorem ipsum dolor sit amet</p>
																	</td>
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			<button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>
																			<button class="btn btn-sm btn-success" type="button"><i class="fe fe-trash-2"></i></button>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td class="align-middle">
																		<label class="custom-control custom-checkbox">
																			
																			3
																		</label>
																	</td>
																	<td class="align-middle">
																		<div class="d-flex">
																			<span class="avatar brround avatar-md d-block" style="background-image: url({{URL::asset('assets/images/users/3.jpg')}})"></span>
																			<div class="ml-3 mt-1">
																				<h6 class="mb-0 font-weight-bold">Staff Role 3</h6>
																				<small class="">Active</small>
																			</div>
																		</div>
																	</td>
																	<td class="text-nowrap align-middle"><span>09 Dec 2017</span></td>
																	<td class="text-nowrap align-middle">
																		<p>Lorem ipsum dolor sit amet</p>
																	</td>
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			<button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>
																			<button class="btn btn-sm btn-success" type="button"><i class="fe fe-trash-2"></i></button>
																		</div>
																	</td>
																</tr>
																<tr>
																	<td class="align-middle">
																		<label class="custom-control custom-checkbox">
																			
																			4
																		</label>
																	</td>
																	<td class="align-middle">
																		<div class="d-flex">
																			<span class="avatar brround avatar-md d-block" style="background-image: url({{URL::asset('assets/images/users/4.jpg')}})"></span>
																			<div class="ml-3 mt-1">
																				<h6 class="mb-0 font-weight-bold">Staff Role 4</h6>
																				<small class="">Inactive</small>
																			</div>
																		</div>
																	</td>
																	<td class="text-nowrap align-middle"><span>20 Jan 2018</span></td>
																	<td class="text-nowrap align-middle">
																		<p>Lorem ipsum dolor sit amet</p>
																	</td>
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			<button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#user-form-modal">Edit</button>
																			<button class="btn btn-sm btn-success" type="button"><i class="fe fe-trash-2"></i></button>
																		</div>
																	</td>
																</tr>
																
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


						<!-- User Form Modal -->
								<div class="modal fade" role="dialog" tabindex="-1" id="user-form-modal">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Create Role</h5>
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												<div class="py-1">
													<form class="form" novalidate="">
														<div class="row">
															<div class="col">
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Role Name</label>
																			<input class="form-control" type="text" name="name" placeholder="John Smith" value="John Smith">
																		</div>
																	</div>
																	
																</div>
																
																<div class="row">
																	<div class="col mb-3">
																		<div class="form-group">
																			<label>About</label>
																			<textarea class="form-control" rows="5" placeholder="Description"></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															
															<div class="col">
																<div class="mb-2"><b>Permissions</b></div>
															<div class="row">
																	<div class="col">
																	<label>Pages</label>
																		<div class="custom-controls-stacked px-2">
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-blog" checked="">
																				<label class="custom-control-label" for="notifications-blog">View</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-news" checked="">
																				<label class="custom-control-label" for="notifications-news">Edit</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-offers" checked="">
																				<label class="custom-control-label" for="notifications-offers">Delete</label>
																			</div>
																		</div>
																	</div>
																	<div class="col">
																	<label>Blogs</label>
																		<div class="custom-controls-stacked px-2">
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-blog" checked="">
																				<label class="custom-control-label" for="notifications-blog">View</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-news" checked="">
																				<label class="custom-control-label" for="notifications-news">Edit</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-offers" checked="">
																				<label class="custom-control-label" for="notifications-offers">Delete</label>
																			</div>
																		</div>
																	</div>
																	<div class="col">
																	<label>Products</label>
																		<div class="custom-controls-stacked px-2">
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-blog" checked="">
																				<label class="custom-control-label" for="notifications-blog">View</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-news" checked="">
																				<label class="custom-control-label" for="notifications-news">Edit</label>
																			</div>
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" id="notifications-offers" checked="">
																				<label class="custom-control-label" for="notifications-offers">Delete</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col d-flex justify-content-end">
															<button class="btn btn-primary" type="submit">Save Changes</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>

					</div>
				</div><!-- end app-content-->
            </div>
@endsection
@section('js')
	
@endsection