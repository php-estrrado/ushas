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
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.category')}}">Category List</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Category</a></li>
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

								<!--@if(Session::has('message'))-->

								<!--<div class="alert alert-{{session('message')['type']}}" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{session('message')['text']}}</div>-->
								<!--@endif-->
								<!--@if ($errors->any())-->
								<!--@foreach ($errors->all() as $error)-->

								<!--<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{$error}}</div>-->
								<!--@endforeach-->
								<!--@endif-->
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
											<div class="card">
                                                <div class="card-body">
                                                    <form action="{{url('admin/update-category/'.$category->category_id)}}" method="POST"  id="catForm" enctype="multipart/form-data">
													@csrf
                                                    
                                                           <input type="hidden" value="{{ $category->category_id }}" name="cat_id" id="cat_id">
                                                           <input type="hidden" value="{{ $category->cat_name_cid }}" name="cat_content_id" id="cat_content_id">
                                                           <input type="hidden" value="{{ $category->cat_desc_cid }}" name="desc_content_id" id="desc_content_id">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Select Language <span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2" name="language" id="language" required>
                                                                    @foreach ($language as $lang)
                                                                    <option value="{{ $lang->id }}" <?php if($default_language->id==$lang->id){ echo "selected";}?>>{{ $lang->glo_lang_name }}<?php if(1==$lang->is_default){ echo " (Default)";}?></option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                       
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Name in Local Language <span class="text-red"></span></label>
                                                                <input type="text" class="form-control @error('local_name') is-invalid @enderror" placeholder="Name in Local Language" name="local_name" value="{{ $category->local_name }}">
                                                            @error('local_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
														 </div>
														 <div class="row" id="lang_content">
														@include('admin.master.includes.content')											
														</div>
														 <div class="row">
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Status <span class="text-red">*</span></label>
                                                                <select class="form-control select2" name="status">
                                                                    <option value="1" <?php if($category->is_active==1){ echo "selected";}?>>Active</option>
                                                                    <option value="0" <?php if($category->is_active==0){ echo "selected";}?>>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <label class="form-label">Category Image <span class="text-red">*</span></label>
                                                            <div class="d-flex">
                                                                <img src="{{ url('storage/app/public/category/'.$category->image) }}" alt="{{ $category->image }}"  style="height: 150px; max-height:150px; width:auto;">
                                                                <input type="hidden" value="{{ $category->image }}" name="image_file">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <label class="form-label">Change Category Image <span class="text-red">*</span></label>
                                                            <p>(Image type .png,.jpeg)</p>
                                                            <input type="file" class="dropify" data-height="180"  accept="image/*" name="category_image"  data-allowed-file-extensions='["png", "jpg", "jpeg"]' />
                                                             <p style="color: red" id="errNm1"></p>
                                                        </div>
														
														<div class="col-sm-12 col-md-12">
                                                            <div class="form-group m-0">
													<div class="form-label">Rating and Review</div>
													<div class="custom-controls-stacked">
														<label class="custom-control custom-checkbox">
															<input type="checkbox" class="custom-control-input" name="is_rating" {{ ($category->is_rating=="1")? "checked" : "" }} value="1" >
															<span class="custom-control-label">Product in this category will have rating and reviews</span>
														</label>
														
														
                                                        </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="col d-flex justify-content-end">
                                                    <a href="{{ route('admin.category')}}" class="mr-2 mt-4 mb-0 btn btn-secondary" >Cancel</a>
                                                    <button type="submit"  id="frontval" class="btn btn-primary mt-4 mb-0" >Submit</button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                                    <!---ttt-->


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
			<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>
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
  $('#category_list').addClass("active");
  $('#a_cat').addClass("active");
  $('#master').addClass("is-expanded");
    });
    
    jQuery(document).ready(function(){


$("#frontval").click(function(){

$("#catForm").validate({
	ignore: [],
rules: {

category_name : {
required: true
},

category_description: {
required: true
},
// category_image: {

// required: true
// }

},

messages : {
category_name: {
required: "Category Name is required."
},
category_description: {
required: "Category Description is required."
},
category_image: {
required: "Category Image is required."
}
},


 errorPlacement: function(error, element) {
 	 // $("#errNm1").empty();$("#errNm2").empty();
 	 console.log($(error).text());
            if (element.attr("name") == "category_image" ) {
            	
                $("#errNm1").text($(error).text());
                
            }else if (element.attr("name") == "product_id" ) {
                $("#errNm2").text($(error).text());
                
            }else {
               error.insertAfter(element)
            }
        },

});
});

});
</script>
<script type="text/javascript">
    $(document).ready(function(){
            @if(Session::has('message'))
            @if(session('message')['type'] =="success")
            
            toastr.success("{{session('message')['text']}}"); 
            @else
            toastr.error("{{session('message')['text']}}"); 
            @endif
            @endif
            
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{$error}}"); 
            
            @endforeach
            @endif
   
	
	
	$('body').on('change','#language',function(){	
	var lang_id=$(this).val();
	var title_id=$("#cat_content_id").val();
	var desc_id=$("#desc_content_id").val();
	var cat_id=$("#cat_id").val();
	$.ajax({
                type: "POST",
                url: '{{ url("admin/category/content") }}',
                data: { lang_id:lang_id,title_id:title_id,cat_id:cat_id,desc_id:desc_id,'_token': '{{ csrf_token()}}'},
                success: function (data) {
                    $("#lang_content").empty().html(data);
					
					
				
			}
	});	
});
 });
    </script>
@endsection
