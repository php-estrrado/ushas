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
								<h4 class="page-title mb-0">Edit Subcategory</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.subcategory')}}">Subcategory List</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Subcategory</a></li>
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
                                                    <form action="{{url('admin/update-subcategory/'.$subcategory->subcategory_id)}}" method="POST"  id="subcatForm" enctype="multipart/form-data">
													@csrf
                                                    <?php  $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
                                                           $category_data =DB::table('category')->where('category_id', $subcategory->category_id)->first();
                                                           $category_name=DB::table('cms_content')->where('cnt_id', $category_data->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                           
                                                           if($subcategory->parent!=0)
                                                           {
                                                               $parent_data=DB::table('subcategory')->where('subcategory_id', $subcategory->parent)->first();
                                                               $parent_name=DB::table('cms_content')->where('cnt_id', $parent_data->sub_name_cid)->where('lang_id', $default_lang->id)->first();
                                                               $parent_content= $parent_name->content;
                                                           }
                                                           else
                                                           {
                                                            $parent_content='';
                                                           }
                                                           ?>
                                                           
                                                           <input type="hidden" value="{{ $subcategory->subcategory_id }}" name="subcatid" id="subcatid">
                                                           <input type="hidden" value="{{ $subcategory->sub_name_cid }}" name="sub_name_cid" id="sub_name_cid">
                                                           <input type="hidden" value="{{ $subcategory->desc_cid }}" name="desc_cid" id="desc_cid">
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
                                                                <label class="form-label">Category<span class="text-red">*</span></label>
                                                                <select class="form-control custom-select select2 @error('category') is-invalid @enderror" name="category" id="category_id" onchange="loadsubcat()" required>
                                                                    <option value="">--Select--</option>
                                                                    @foreach ($category as $cat)
                                                                    <?php
                                                                    $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
                                                                    $category_name=DB::table('cms_content')->where('cnt_id', $cat->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                    ?>
                                                                    <option value="{{ $cat->category_id }}" @if($cat->category_id==$subcategory->category_id){{ "selected" }} @endif>{{ $category_name->content }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('category')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                <input type="hidden" value="{{ $subcategory->category_id }}" id="oldcat">
                                                            </div>
                                                        </div>
                                                       </div>
													   <div class="row" id="lang_content">
													   @include('admin.master.includes.subcat_content')
													   </div>
													   @if($subcategory->image)
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <label class="form-label">Subcategory Image <span class="text-red">*</span></label>
                                                            <div class="d-flex">
                                                                <img src="{{ url('storage/app/public/subcategory/'.$subcategory->image) }}" alt="{{ $subcategory->image }}"  style="height: 150px; max-height:150px; width:auto;">
                                                                <input type="hidden" value="{{ $subcategory->image }}" name="image_file">
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                                           <label class="form-label">Choose another Subcategory Image <span class="text-red"></span></label>
                                                           <p>(Image type .png,.jpeg)</p>
                                                           <input type="file" class="dropify @error('subcategory_image') is-invalid @enderror" data-height="180"  accept="image/*" name="subcategory_image" data-allowed-file-extensions='["png", "jpg", "jpeg"]' />
                                                           <p style="color: red" id="errNm1"></p>
                                                        </div>
														 <div class="col-lg-4 col-md-4 col-sm-12">
                                                            <div class="form-group">
                                                            <img src="" alt="Image" id="image_disp_id" class="no-disp" width="120px" />
                                                            </div>
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

        <!----combotree----->
        <script src="{{URL::asset('admin/assets/plugins/combotree/comboTreePlugin.js')}}"></script>
        <!--<script src="https://estrradoweb.com/vrise/template/seller/assets/scripts/dropdown-tree/comboTreePlugin.js" ></script>-->
        
        
                    <!--INTERNAL Sumoselect js-->
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


},

messages : {
sub_category_name: {
required: "Subcategory Name is required."
},
category: {
required: "Category Name is required."
},


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
    loadsubcat('1');
    var selectionIdList = new Array($("#sub-category-id").val());
    instance.setSelection(selectionIdList);
    

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
    

    
  

</script>
<script type="text/javascript">
    $(document).ready(function(){
            $('body').on('change','#language',function(){	
				var lang_id=$(this).val();
				var title_id=$("#sub_name_cid").val();
				var desc_id=$("#desc_cid").val();
				var subcat_id=$("#subcatid").val();
				$.ajax({
							type: "POST",
							url: '{{ url("admin/subcategory/content") }}',
							data: { lang_id:lang_id,title_id:title_id,subcat_id:subcat_id,desc_id:desc_id,'_token': '{{ csrf_token()}}'},
							success: function (data) {
								$("#lang_content").empty().html(data);
								
								
							
						}
				});	
			});
    });
    </script>
	
	<script type="text/javascript">
    $(document).ready(function(){
            $('body').on('change','#sub_category_name',function(){	
				var subname=$("#sub_category_name option:selected").text();
				$("#lang_sub_category_name").val(subname);
					
			});
    });
    </script>
@endsection
