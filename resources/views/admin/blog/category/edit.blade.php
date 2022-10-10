@extends('layouts.admin')
@section('css')
		<!-- INTERNAl alert css -->
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

        <!--INTERNAL Select2 css -->
		<link href="{{URL::asset('admin/assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

        <!-- INTERNAL File Uploads css -->
		<link href="{{URL::asset('admin/assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />
        <!-- INTERNAL File Uploads css-->
        <link href="{{URL::asset('admin/assets/plugins/fileupload/css/fileupload.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('page-header')
						<!--Page header-->
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Edit Category</h4>
								<ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Blog Management</a></li>
                                    <li class="breadcrumb-item" aria-current="page"><a href="{{url('admin/blog/categories')}}">Blog Category</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Category</a></li>
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
										<div class="card">
                                            <div class="card-body">
                                                <form action="{{url('admin/blog/updatecategory/'.$blog_category->bc_id)}}" method="POST" enctype="multipart/form-data">
													@csrf
                                                    <div class="row">
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Category Name <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control @error('bc_name') is-invalid @enderror" placeholder="Category Name" value="{{ $blog_category->bc_name }}"  name="bc_name">
                                                                @error('bc_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Category Description <span class="text-red">*</span></label>
                                                                <textarea class="form-control @error('bc_description') is-invalid @enderror" placeholder="Description" name="bc_description">{{ $blog_category->bc_description }}</textarea>
																@error('bc_description')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Category Icon </label>
                                                                <input type="text" class="form-control @error('bc_faicon') is-invalid @enderror" placeholder="Category fa icon code Ex:fa fa-home," value="{{ $blog_category->bc_faicon }}"  name="bc_faicon">
                                                                @error('bc_faicon')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Sort Order </label>
                                                                <input type="number" min="1" class="form-control @error('bc_sortorder') is-invalid @enderror" placeholder="Category Sort Order" value="{{ $blog_category->bc_sortorder }}"  name="bc_sortorder">
                                                                @error('bc_sortorder')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Status <span class="text-red">*</span></label>
                                                                <select class="form-control select2" name="status">
                                                                    <option <?php if($blog_category->is_active==1){ echo "selected";} ?> value="1">Published</option>
                                                                    <option <?php if($blog_category->is_active==0){ echo "selected";} ?> value="0">Draft</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Seo Tags </label>
                                                                <input type="text" class="form-control @error('bc_seo_tag') is-invalid @enderror" placeholder="Category Seo Tags" value="{{ $blog_category->bc_seo_tag }}"  name="bc_seo_tag">
                                                                @error('bc_seo_tag')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Seo Title </label>
                                                                <input type="text" class="form-control @error('bc_seo_title') is-invalid @enderror" placeholder="Category Seo Title" value="{{ $blog_category->bc_seo_title }}"  name="bc_seo_title">
                                                                @error('bc_seo_title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Seo Description</label>
                                                                <textarea class="form-control @error('bc_seo_description') is-invalid @enderror" placeholder="Seo Description" name="bc_seo_description">{{ $blog_category->bc_seo_description }}</textarea>
																@error('bc_seo_description')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        
                                                            
                                                    </div>
                                                    <div class="col d-flex justify-content-end">
                                                    <a href="{{ route('admin.blogcategories')}}" class="mr-2 mt-4 mb-0 btn btn-secondary" >Cancel</a>
                                                    <button type="submit" class="btn btn-primary mt-4 mb-0" >Save</button>
                                                    </div>
                                                </form>
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
@endsection
@section('js')
         <!--INTERNAL Select2 js -->
		<script src="{{URL::asset('admin/assets/plugins/select2/select2.full.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/select2.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>

        <!-- INTERNAL File-Uploads Js-->
		<script src="{{URL::asset('admin/assets/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
        <script src="{{URL::asset('admin/assets/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
        <script src="{{URL::asset('admin/assets/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
        <script src="{{URL::asset('admin/assets/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
        <script src="{{URL::asset('admin/assets/plugins/fancyuploder/fancy-uploader.js')}}"></script>

		<!-- INTERNAL File uploads js -->
        <script src="{{URL::asset('admin/assets/plugins/fileupload/js/dropify.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/filupload.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function () {
			$('#blog_acat').addClass("active");
            $('#blog_cat').addClass("active");
            $('#blog_menu').addClass("is-expanded");
    });
</script>

@endsection
