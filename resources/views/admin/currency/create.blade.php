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
						<!--Page header-->

<?php if($currency)
{
 $id = $currency->id;
}
else
{
$id = 0;
}
?>
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
                                                    <form action="{{url('admin/insert-currency/'.$id)}}" method="POST" enctype="multipart/form-data">
													@csrf
                                                    <div class="row">
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Select Country <span class="text-red">*</span></label>
                                                                <select class="form-control select2-show-search @error('country') is-invalid @enderror" id="country" name="country">
                                                                	<option value="">Select country</option>
                                                                    @foreach ($country as $coun)
                                                                    <option value="{{ $coun->id }}" @if($currency)@if($coun->id==$currency->country_id) selected @endif;@endif;>{{ $coun->country_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('country')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Currency Name <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control @error('currency_name') is-invalid @enderror" @if($currency)value="{{ $currency->currency_name }}"  @else value="{{ old('currency_name') }}" @endif;
                                                                  placeholder="Currency Name" name="currency_name" >
                                                                @error('currency_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Currency Code <span class="text-red">*</span></label>
                                                                <input type="text" style="text-transform:uppercase;" class="form-control @error('currency_code') is-invalid @enderror" @if($currency)value="{{ $currency->currency_code }}"  @else value="{{ old('currency_code') }}" @endif;  placeholder="Currency Code" name="currency_code" >
                                                                @error('currency_code')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Set as default <span class="text-red">*</span></label>
                                                                <div class="col-12">
                                                                	<label class="custom-control custom-radio custom-control-md col-md-6 fl">
                                                                		<input id="option1" class="custom-control-input cus_radio" 
                                                                	@if($currency)
                                                                	@if($currency->is_default==1)
                                                                	checked="checked" 
                                                                	 @endif;
                                                                	 @endif; 
                                                                	 name="is_default" type="radio" value="1">
                                                                		<span class="custom-control-label custom-control-label-md">Yes</span>
                                                                	</label>
                                                                	<label class="custom-control custom-radio custom-control-md col-md-6 fl">
                                                                		<input id="option2" class="custom-control-input cus_radio" name="is_default" @if($currency)
                                                                	@if($currency->is_default==0)
                                                                	checked="checked" 
                                                                	 @endif;
                                                                	 @else
                                                                	 checked="checked" 
                                                                	 @endif;
                                                                	   name="prd_option" type="radio" value="0">
                                                                		<span class="custom-control-label custom-control-label-md">No</span>
                                                                	</label>
                                                                </div>
                                                                @error('is_default')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                <div class="clr"></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Status <span class="text-red">*</span></label>
                                                                <select class="form-control select2" name="status">
                                                                    <option value="1" @if($currency)
                                                                	@if($currency->is_active==1){{"selected"}}
                                                                	@endif;@endif;>Active</option>
                                                                    <option value="0" @if($currency)
                                                                	@if($currency->is_active==0){{"selected"}}
                                                                	@endif;@endif;>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                             <div class="form-group">
                                                            <label class="form-label">Flag Image <span class="text-red">*</span></label>
                                                            <input type="file" name="flag" class="dropify @error('flag') form-control  is-invalid  @enderror" accept=".jpg, .png, image/jpeg, image/png"/>
                                                             @error('flag')
                                                                    <span style="color: red">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                        </div>
                                                         </div>
                                                        @if($currency)
                                                        @if($currency->image)
                                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                                            <label class="form-label">Flag Image <span class="text-red">*</span></label>
                                                            <div class="d-flex">
                                                                <img src="{{ config('app.storage_url').$currency->image }}" alt="{{ $currency->image }}"  style="height: 150px; max-height:150px; width:auto;">
                                                                <input type="hidden" value="{{ $currency->image }}" name="image_file">
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @endif
                                                    </div>
                                                    <div class="row">
                                                        <div class="col d-flex justify-content-end">
                                                        <a class="btn btn-secondary mt-4 mb-0 mr-2" href="{{url('admin/currency')}}">Cancel</a>
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
