@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
				<link href="{{URL::asset('admin/assets/css/combo-tree.css')}}" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">View Category</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Masters</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.category')}}">Category List</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">View Category</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
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
											<div class="table-responsiv table-lg mt-3">
												<?php  $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
                                                           $category_name=DB::table('cms_content')->where('cnt_id', $category->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                           $category_desc=DB::table('cms_content')->where('cnt_id', $category->cat_desc_cid)->where('lang_id', $default_lang->id)->first(); ?>
														<div class="row">

															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">Language:</label>
																	<div class="col-md-8">
																	<p class="view_value">{{$default_lang->glo_lang_name}}</p>
																   </div>
																</div>
																<div class="form-group row">
																	<label class="form-label col-md-4">Category Name:</label>
																	<div class="col-md-8">
																	<p class="view_value">{{ $category_name->content }}</p>
																</div>
																</div>
																		
															</div>
															<div class="col-md-6 col-lg-6 col-xl-6 col-sm-12">
																<div class="form-group row">
																	<label class="form-label col-md-4">Category Description:</label>
																	<div class="col-md-8">
																	<p class="view_value">{{ $category_desc->content }}</p>
																</div>
																</div>
																<div class="form-group row">
																	<label class="form-label col-md-4">Status:</label>
																	<div class="col-md-8">
																	<p class="view_value"><?php if($category->is_active==1){echo"Active";}else{echo"Inactive";}?></p>
																</div>
																</div>
																		
															</div>
															<div class="col-md-12 col-lg-12 col-xl-12 col-sm-12">
																<label class="form-label">Category Image: <span class="text-red"></span></label>
                                                            <div class="d-flex">
                                                                <img src="{{ url('storage/app/public/category/'.$category->image) }}" alt="{{ $category->image }}"  style="height: 150px; max-height:150px; width:auto;">
                                                                <input type="hidden" value="{{ $category->image }}" name="image_file">
                                                            </div>
															</div>
														</div>
														
														<div class="row" style="margin-top: 30px;">
															<div class="col d-flex justify-content-end">
															    <a href="{{route('admin.category')}}"  class="mr-2 btn btn-secondary" >Back</a>  
															
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


						
							

					
				</div><!-- end app-content-->
            </div>
@endsection
@section('js')
<script type="text/javascript">
	$(document).ready(function () {
  $('#category_list').addClass("active");
  $('#a_cat').addClass("active");
  $('#master').addClass("is-expanded");
    });
</script>		
@endsection