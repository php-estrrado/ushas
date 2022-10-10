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
								<h4 class="page-title mb-0">Add Category</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.category')}}">Category List</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Add Category</a></li>
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
                                                    <form action="{{url('admin/insert-category')}}" method="POST" id="catForm" enctype="multipart/form-data">
													@csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Select Language <span class="text-red">*</span></label>
                                                               <select class="form-control custom-select select2" name="language" id="language" required disabled>
																@if($language)
																@foreach ($language as $lang)
																	<option value="{{ $lang->id }}" data-defaultlang="{{$lang->is_default}}" <?php if(1==$lang->is_default){ echo "selected";}?>>{{ $lang->glo_lang_name  }} <?php if(1==$lang->is_default){ echo "(Default)";}?> </option>
																@endforeach
																@endif
																</select>
																<input type="hidden" name="language" value="{{$default_language->id}}">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Name in Local Language <span class="text-red"></span></label>
                                                                <input type="text" class="form-control @error('local_name') is-invalid @enderror" placeholder="Name in Local Language" value="{{ old('local_name') }}"  name="local_name">
                                                                @error('local_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
														<div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Category Name <span class="text-red">*</span></label>
																	<input type="text" class="form-control @error('category_name') is-invalid @enderror" placeholder="Category" value="{{ old('category_name') }}"  name="category_name">
                                                                @error('category_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Category Description <span class="text-red">*</span></label>
                                                                <!--<input type="text" class="form-control @error('category_description') is-invalid @enderror" placeholder="Description" value="{{ old('category_description') }}" name="category_description">-->
                                                                <textarea class="form-control @error('category_description') is-invalid @enderror" placeholder="Description" value="{{ old('category_description') }}" name="category_description">{{ old('category_description') }}</textarea>
                                                                @error('category_description')
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
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Daily Product <span class="text-red">*</span></label>
                                                                    <div class="row">
                                                                    <div class="col-md-2">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" name="daily_pro" value="1" checked>
                                                                        <span class="custom-control-label">Yes</span>
                                                                    </label>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" name="daily_pro" value="0" checked>
                                                                        <span class="custom-control-label">No</span>
                                                                    </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <label class="form-label">Category Image <span class="text-red">*</span></label>
                                                            <p>(Image type .png,.jpeg)</p>
                                                            <input type="file" class="dropify @error('category_image') is-invalid @enderror" data-height="180"  accept="image/*" data-allowed-file-extensions='["png", "jpg", "jpeg"]' name="category_image" />
                                                            <p style="color: red" id="errNm1"></p>
                                                        </div>
                                                       
                                                             @error('category_image')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                    </div>
													<div class="col-sm-12 col-md-12">
                                                            <div class="form-group m-0">
													<div class="form-label">Rating and Review</div>
													<div class="custom-controls-stacked">
														<label class="custom-control custom-checkbox">
															<input type="checkbox" class="custom-control-input" name="is_rating" value="1" >
															<span class="custom-control-label">Product in this category will have rating and reviews</span>
														</label>
														
														
                                                        </div>
                                                        </div>
                                                        </div>
                                                    <div class="col d-flex justify-content-end">
                                                    <a href="{{ route('admin.category')}}" class="mr-2 mt-4 mb-0 btn btn-secondary" >Cancel</a>
                                                    <button type="submit" id="frontval" class="btn btn-primary mt-4 mb-0" >Submit</button>
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


						<!-- User Form Modal -->
								<div class="modal fade" role="dialog" tabindex="-1" id="user-form-modal">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Create Module</h5>
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												{{ Form::open(array('url' => "admin/modules/save", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true')) }}
												{{Form::hidden('id',0,['id'=>'moduleid'])}}
												{{Form::hidden('is_selected',0,['is_selected'=>'is_selected'])}}
												<div class="py-1">

														<div class="row">
															<div class="col">
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Module Name</label>

																			{!! Form::text('module_name', null, ['class' => 'form-control','required','id'=>'module_name']) !!}
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label>Class</label>

																			{!! Form::text('class', null, ['class' => 'form-control','required','id'=>'module_class']) !!}
																		</div>
																	</div>

																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Slug</label>

																			{!! Form::text('link', null, ['class' => 'form-control','required','id'=>'module_link']) !!}
																		</div>
																	</div>
																	<div class="col">
																		<div class="form-group">
																			<label>Sort Order</label>

																			{!! Form::text('sort', null, ['class' => 'form-control','required','id'=>'module_order']) !!}
																		</div>
																	</div>

																</div>
																<div class="row">
																	<div class="col">
																		<div class="form-group">
																			<label>Status</label>

																			{!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'module_status']); !!}
																		</div>
																	</div>
																	<div class="col">
																		<!-- <div class="form-group">
																			<label>Sort Order</label>

																			{!! Form::text('sort', null, ['class' => 'form-control']) !!}
																		</div> -->
																	</div>

																</div>


															</div>
														</div>

														<div class="row">
															<div class="col d-flex justify-content-end">
															<input class="btn btn-primary" type="submit" value="Save Changes">
															</div>
														</div>

												</div>
												{{Form::close()}}
											</div>
										</div>
									</div>
								</div>

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
category_image: {

required: true
}

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
    });
    </script>
@endsection
