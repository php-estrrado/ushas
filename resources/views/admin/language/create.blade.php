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


		<!-- INTERNAl WYSIWYG Editor css -->
		<link href="{{URL::asset('admin/assets/plugins/wysiwyag/richtext.css')}}" rel="stylesheet" />
        <style>.imageThumb {
            max-height: 75px;
            border: 2px solid;
            padding: 1px;
            cursor: pointer;
          }
          .pip {
            display: inline-block;
            margin: 10px 10px 0 0;
          }
          .remove {
            display: block;
            background: #444;
            border: 1px solid black;
            color: white;
            text-align: center;
            cursor: pointer;
          }
          .remove:hover {
            background: white;
            color: black;
          }</style>
@endsection
@section('page-header')
<?php if($lang)
{
 $id = $lang->id;
}
else
{
$id = 0;
}
?>
						<!--Page header-->

						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>{{ $menutype }}</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
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
@endsection
@section('content')
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

								@if(Session::has('message'))

								<div class="alert alert-{{session('message')['type']}}" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{session('message')['text']}}</div>
								@endif
								<!-- @if ($errors->any())
								@foreach ($errors->all() as $error)

								<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{$error}}</div>
								@endforeach
								@endif -->
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
											<div class="card">
                                                <div class="card-body">
                                                    <form action="{{url('admin/insert-language/'.$id)}}" method="POST" enctype="multipart/form-data">
													@csrf
                                                    <div class="row">
                                                        
                                                       
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Language Name <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control @error('language_name') is-invalid @enderror" @if($lang)value="{{ $lang->glo_lang_name }}"  @else value="{{ old('language_name') }}" @endif;
                                                                  placeholder="Language Name" name="language_name" >
                                                                @error('language_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Language Code <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control @error('language_code') is-invalid @enderror" @if($lang)value="{{ $lang->glo_lang_code }}"  @else value="{{ old('language_code') }}" @endif;  placeholder="Language Code" name="language_code" >
                                                                @error('language_code')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Language Description <span class="text-red"></span></label>
                                                                <input type="text" class="form-control @error('language_desc') is-invalid @enderror" @if($lang)value="{{ $lang->glo_lang_desc }}"  @else value="{{ old('language_code') }}" @endif;  placeholder="Language Description" name="language_desc" >
                                                                @error('language_desc')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Status <span class="text-red">*</span></label>
                                                                <select class="form-control select2" name="status">
                                                                    <option value="1" @if($lang)
                                                                	@if($lang->is_active==1){{"selected"}}
                                                                	@endif;@endif;>Active</option>
                                                                    <option value="0" @if($lang)
                                                                	@if($lang->is_active==0){{"selected"}}
                                                                	@endif;@endif;>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col d-flex justify-content-end">
                                                        <a class="btn btn-secondary mt-4 mb-0 mr-2" href="{{url('admin/language')}}">Cancel</a>
                                                        <button class="btn btn-primary mt-4 mb-0 " type="submit">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                                    <!---hhj-->


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

        <!-- INTERNAL WYSIWYG Editor js -->
		<script src="{{URL::asset('admin/assets/plugins/wysiwyag/jquery.richtext.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/form-editor.js')}}"></script>



<script type="text/javascript">

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
// 	$('#categoryList').on('change', function () {
//     $("#subcategoryList").attr('disabled', false); //enable subcategory select
//     $("#subcategoryList").val("");
//     $(".subcategory").attr('disabled', true); //disable all category option
//     $(".subcategory").hide(); //hide all subcategory option
//     $(".parent-" + $(this).val()).attr('disabled', false); //enable subcategory of selected category/parent
//     $(".parent-" + $(this).val()).show();
// });
</script>

@endsection
