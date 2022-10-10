@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		<style>
        #sortable-row { list-style: none; color: black; }
        #sortable-row li { margin-bottom:4px; padding:10px; background-color:#BBF4A8;cursor:move;}
        #sortable-row li.ui-state-highlight { height: 1.0em; background-color:#F0F0F0;border:#ccc 2px dotted;}
        .modal-open 
        {
        overflow: scroll;
        }
    </style>
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Category List</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Masters</a></li>

									<li class="breadcrumb-item active" aria-current="page"><a href="#">Category List</a></li>
								</ol>
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
										<div class="e-panel card">
											<div class="card-body">
											<div class="col-5">
											{{Form::label('Business Category','Business Categories',['class'=>''])}}
											<select class="form-control mr-2" id="busniess_cat" onChange="test()">
                                                       <option value="">Select</option>
													   @if($business_category)
														   <?php $i=1; ?>
															@foreach($business_category as $cat)
														<option value="{{ $cat->id }}" <?php if($i==1){ echo "selected"; } ?>>{{ $cat->name }}</option>
															<?php $i++; ?>
															@endforeach;
															@endif;
                                                        </select>
											</div>
											<div class="col-6">
												<form  action="{{route('businessCategory.sort-order')}}" method="POST" >
                                  @csrf
                                    <div class="modal-body popup_area">
                                     @include('admin.master.includes.businessCategoryList')   
                                    </div>
									<input type="hidden"  name="business_category" id="bcat_id" value="">
                                    <div class="modal-footer">
                                        <button type="submit" id="" onClick="saveOrder();" class="btn btn-success"> Save </button>
                                    </div>
                                  </form>
											</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


						<!-- User Form Modal -->


					</div>
				</div><!-- end app-content-->
            </div>

            <!-- Modal -->
			<!-- sort order modal -->              
                 
                        
@endsection
@section('js')
		<!-- INTERNAl Data tables -->
		<script src="{{URL::asset('admin/assets/js/datatable/tables/category-datatable.js')}}"></script>
		
		
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		
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
<script type="text/javascript">

$(document).ready(function(){
    $('#busniess_cat').trigger("change"); // onload it will call the function




});
function test() {
		var bcat_id= $('#busniess_cat').val();
		$('#bcat_id').val(bcat_id);
		var category_id=[];
		$('ul#sortable-row li').each(function() 
		{
          category_id.push($(this).attr("id"));
		});
		
		$.ajax({
            type: "POST",
            url: '{{url("/admin/business-category/check-exist")}}',
            data: { "_token": "{{csrf_token()}}", bcat_id: bcat_id,category_id:category_id},
            success: function (data) {
                 $(".popup_area").empty().html(data);
            $( "#sortable-row" ).sortable(
      {
        placeholder: "ui-state-highlight"
      }); 
            }
        });
		
}

	
    
    function saveOrder() 
    {
      var selectedLanguage = new Array();
      $('ul#sortable-row li').each(function() 
      {
          selectedLanguage.push($(this).attr("id"));
      });
      document.getElementById("row_order").value = selectedLanguage;
    }
    
    $(function() {
		
		
  
        
        $( "#sortable-row" ).sortable(
      {
        placeholder: "ui-state-highlight"
      });  
      

	
	
	
	
  })
</script>

@endsection
