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
								<h4 class="page-title mb-0">Edit Tag</h4>
								<ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Blog Management</a></li>
                                    <li class="breadcrumb-item" aria-current="page"><a href="{{url('admin/blog/tags')}}">Blog Tags</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Tag</a></li>
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
                                                <form action="{{url('admin/blog/updatetag/'.$blog_tag->bt_id)}}" method="POST" enctype="multipart/form-data">
													@csrf
                                                    <div class="row">
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Tag Name <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control @error('bt_name') is-invalid @enderror" placeholder="Tag Name" value="{{ $blog_tag->bt_name }}"  name="bt_name">
                                                                @error('bt_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Tag Description <span class="text-red">*</span></label>
                                                                <textarea class="form-control @error('bt_description') is-invalid @enderror" placeholder="Description" name="bt_description">{{ $blog_tag->bt_description }}</textarea>
																@error('bt_description')
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
                                                                    <option <?php if($blog_tag->is_active==1){ echo "selected";} ?> value="1">Published</option>
                                                                    <option <?php if($blog_tag->is_active==0){ echo "selected";} ?> value="0">Draft</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col d-flex justify-content-end">
                                                    <a href="{{ route('admin.blogtags')}}" class="mr-2 mt-4 mb-0 btn btn-secondary" >Cancel</a>
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
