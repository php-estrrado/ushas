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
        <link href="{{URL::asset('admin/assets/css/combo-tree.css')}}" rel="stylesheet" />
        
        <link rel="stylesheet" href="{{URL::asset('admin/assets/plugins/sumoselect/sumoselect.css')}}">
        <link rel="stylesheet" href="{{URL::asset('admin/assets/plugins/jQuerytransfer/jquery.transfer.css')}}">
        <link rel="stylesheet" href="{{URL::asset('admin/assets/plugins/jQuerytransfer/icon_font/icon_font.css')}}">
        <link rel="stylesheet" href="{{URL::asset('admin/assets/plugins/multi/multi.min.css')}}">
        
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Add Subcategory</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.subcategory')}}">Subcategory List</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Add Subcategory</a></li>
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
                                                    <form action="{{url('admin/insert-subcategory')}}" method="POST" id="subcatForm" enctype="multipart/form-data">
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
                                                                <label class="form-label">Category<span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2 @error('category') is-invalid @enderror" name="category" id="category_id" onchange="loadsubcat()">
                                                                    <option value="">--Select--</option>
                                                                    @foreach ($category as $cat)
                                                                    <?php
                                                                    $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
                                                                    $category_name=DB::table('cms_content')->where('cnt_id', $cat->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                    ?>
                                                                    <option value="{{ $cat->category_id }}">{{ $category_name->content }}</option>
                                                                    @endforeach
                                                                </select>
                                                                 @error('category')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Subcategory Name <span class="text-red">*</span></label>
                                                                <!--<input type="text" class="form-control @error('sub_category_name') is-invalid @enderror" placeholder="Sub Category" value="{{ old('sub_category_name') }}" name="sub_category_name">-->
                                                                <input type="hidden" name="id" id="curent_subid" value="0" />
                                                                
                                                                 {{Form::select('sub_category_name',$subcategories,'',['id'=>'sub_category_name','class'=>'form-control','placeholder'=>'Select Subcategory'])}}
                                                                 
                                                                @error('sub_category_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <a href="#"  data-target="#subitem-modal" data-toggle="modal" class="btn btn-danger fr addmodule"> Add New</a>
                                                        </div>
                                                        <!--<div class="col-md-12">-->
                                                        <!--    <div class="form-group">-->
                                                        <!--        <label class="form-label">Name in Local Language </label>-->
                                                        <!--        <input type="text" class="form-control @error('local_name') is-invalid @enderror" placeholder="Name in Local Language" value="{{ old('local_name') }}" name="local_name">-->
                                                        <!--        <input type="hidden" name="id" id="" value="0" />-->
                                                        <!--        @error('local_name')-->
                                                        <!--            <span class="invalid-feedback" role="alert">-->
                                                        <!--                <strong>{{ $message }}</strong>-->
                                                        <!--            </span>-->
                                                        <!--        @enderror-->
                                                        <!--    </div>-->
                                                        <!--</div>-->
                                                        
                <!--                                        <div class="col-md-12">-->
                <!--                                            <div class="form-group">-->
                <!--                                                <label class="form-label">Parent Category<span class="text-red"></span></label>-->
                <!--                                                <input type="text" id="sub-category-id" placeholder="Type to filter" name="subcat_id" autocomplete="off"  value="" hidden />-->
																<!--<input type="text" id="sub-category-drop" class="form-control" name="parent" placeholder="Select Subcategory" readonly style="background-color: #fff !important;">-->
                                                                <!--<input type="text" id="sub-category-drop" placeholder="Select" name="parent" class="form-control" readonly>-->
                                                                <!--<input type="text" id="sub-category-id" name="" value="" hidden />-->
                <!--                                            </div>-->
                <!--                                        </div>-->
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Subcategory Description <span class="text-red"></span></label>
                                                                <input type="text" class="form-control" placeholder="Description" name="subcategory_description">
                                                            </div>
                                                        </div>
                                                        
                                                        <!--<div class="col-sm-12 col-md-12">-->
                                                        <!--    <div class="form-group">-->
                                                        <!--        <label class="form-label">Status <span class="text-red">*</span></label>-->
                                                        <!--        <select class="form-control select2" name="status">-->
                                                        <!--            <option value="1">Active</option>-->
                                                        <!--            <option value="0">Inactive</option>-->
                                                        <!--        </select>-->
                                                        <!--    </div>-->
                                                        <!--</div>-->
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
                                            
                                                        {{-- <div class="dropdown dropdown-tree" id="firstDropDownTree"></div> --}}
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                           <label class="form-label">Subcategory Image <span class="text-red">*</span></label>
                                                           <p>(Image type .png,.jpeg)</p>
                                                           <input type="file" class="dropify @error('subcategory_image') is-invalid @enderror" data-height="180"  accept="image/*" name="subcategory_image" data-allowed-file-extensions='["png", "jpg", "jpeg"]' />
                                                           <p style="color: red" id="errNm1"></p>
                                                        </div>
                                                      @error('subcategory_image')
                                                                   <span class="invalid-feedback" role="alert">
                                                                       <strong>{{ $message }}</strong>
                                                                  </span>
                                                              @enderror
                                                             <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <div class="form-group">
                                                            <img src="" alt="Image" id="image_disp_id" class="no-disp" width="120px" />
                                                            </div>
                                                        </div>    
                                                                
																{{--<div class="col-sm-12 col-md-12">
                                                        <div class="from-group">
                                                        <label class="form-label">Extra Fields </label>
                                                        <select multiple="multiple" name="extra_fields[]" id="extra_fields">

                                                        @if($extra_fields)
                                                        <optgroup label="Select Fields">
                                                        @foreach($extra_fields as $e_field)
                                                        <option value="{{$e_field->id}}">{{$e_field->name}}</option>
                                                        @endforeach
                                                        </optgroup>
                                                        @endif

                                                        </select>
                                                        </div>
                                                        </div>--}}
                                                        
                                                    </div>
                                                    <div class="col d-flex justify-content-end">
                                                    <a href="{{ route('admin.subcategory')}}" class="mr-2 mt-4 mb-0 btn btn-secondary" >Cancel</a>
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

					</div>
				</div><!-- end app-content-->
            </div>
            
            <div class="modal fade" role="dialog" tabindex="-1" id="subitem-modal">
                                    <div class="modal-dialog modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Create Subcategory Item</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{ Form::open(array('url' => "admin/subcategory-item", 'id' => 'subitemForm', 'name' => 'subitemForm', 'class' => '','files'=>'true')) }}
                                        
                                                <div class="py-1">
                                                    
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label>Subcategory Item Name <span class="text-red">*</span></label>
                                                                            
                                                                            {!! Form::text('item_name', null, ['class' => 'form-control','required','id'=>'item_name']) !!}
                                                                        </div>
                                                                    </div>
                                                                   
                                                                    
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <label>Status <span class="text-red">*</span></label>
                                                                    
                                                                            {!! Form::select('is_active', array('1' => 'Active', '0' => 'Inactive'), '1',['class' => 'form-control','required','id'=>'module_status']); !!}
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col d-flex justify-content-end">
                                                            <input class="btn btn-primary" type="submit" id="frontval" value="Save">
                                                            </div>
                                                        </div>
                                                    
                                                </div>
                                                {{Form::close()}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
@endsection
@section('js')
        
        <!----combotree----->
        <script src="{{URL::asset('admin/assets/plugins/combotree/comboTreePlugin.js')}}"></script>
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
		
		<script src="{{URL::asset('admin/assets/plugins/sumoselect/jquery.sumoselect.js')}}"></script>

        <!--INTERNAL jquery transfer js-->
        <script src="{{URL::asset('admin/assets/plugins/jQuerytransfer/jquery.transfer.js')}}"></script>

        <!--INTERNAL multi js-->
        <script src="{{URL::asset('admin/assets/plugins/multi/multi.min.js')}}"></script>

        <!--INTERNAL Form Advanced Element -->
        <script src="{{URL::asset('admin/assets/js/formelementadvnced.js')}}"></script>
        
<script type="text/javascript">

    jQuery(document).ready(function(){


$("#frontval").click(function(){
    
   

$("#subcatForm").validate({
	ignore: [],
rules: {

sub_category_name : {
required: true
},

category : {
required: true
},


// subcategory_image: {

// required: true
// }

},

messages : {
sub_category_name: {
required: "Subcategory Name is required."
},
category: {
required: "Category Name is required."
},

// subcategory_image: {
// required: "Subcategory Image is required."
// }
},


 errorPlacement: function(error, element) {
 	 // $("#errNm1").empty();$("#errNm2").empty();
 	 console.log($(error).text());
            if (element.attr("name") == "subcategory_image" ) {
            	
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
$(document).ready(function () {
  $('#subcategory_list').addClass("active");
  $('#a_sub').addClass("active");
  $('#master').addClass("is-expanded");
});
	

   
    
    var instance = $('#sub-category-drop').comboTree({
    collapse:true,
    cascadeSelect:true,
    isMultiple: false
    });

 function loadsubcat(clear='')
    {
        var category_id=$("#category_id").val();
        
        if(clear!='1')
        {
            $("#sub-category-id").val('');
        }
        
         $.ajax({
            type: "POST",
            url: '{{url("/admin/tags/subcategory")}}',
            data: { "_token": "{{csrf_token()}}", category_id: category_id},
            success: function (data) {
            	var obj = JSON.parse(data);
            
            	console.log(obj);
            	 var obj = JSON.parse(data);
            if(obj.subdata.length >=1)
            {
               $('#sub-category-drop').attr("placeholder", "Select subcategory"); 
            }
            else
            {
                $('#sub-category-drop').attr("placeholder", "No subcategory to display"); 
            }
            instance.setSource(obj.subdata);
            if($("#sub-category-id").val())
            {
                var selectionIdList = new Array($("#sub-category-id").val());
                instance.setSelection(selectionIdList);

            }
            
            }
        });
        
        
        
    }
    $('#sub-category-drop').on('change',function()
        {
            if(instance.getSelectedIds())
            {
                $("#sub-category-id").val(instance.getSelectedIds()[0]);
            }
        });

    // function loadsubcat(clear='')
    // {
    //     var category_id=$("#category_id").val();
    //     $("#sub-category-drop").val('');
    //     $('#sub-category-drop').empty();
    //   //  alert(category_id);
    //     if(clear!='1')
    //     {
    //         $("#sub-category-id").val('');
    //         $("#sub-category-drop").val('');
            
    //     }
    //     var url="{{ url('admin/subcategory/list/') }}";
    //     $.get(url+"/"+category_id+"/"+$("#curent_subid").val(), function(data, status)
    //     {
    //         var obj = JSON.parse(data);
    //         // console.log(obj.subdata);
            
    //         if(obj.subdata.length >1)
    //         {
                
    //             comboTree3 =$('#sub-category-drop').comboTree({
    //              source : obj.subdata,
    //              cascadeSelect: true,
    //              collapse:true
    //             });
                
    //         }
    //         else
    //         {
    //             $("#sub-category-id").val('');
    //             $("#sub-category-drop").val('');
    //             $('#sub-category-drop').attr("placeholder", "No subcategory to display");
    //             $('#sub-category-drop').empty();
    //             comboTree3.destroy();

    //         }
    //     });
    // }
    

  

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
