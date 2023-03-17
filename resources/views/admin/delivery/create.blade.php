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
		<link href="{{URL::asset('admin/assets/css/datepicker.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/css/chosen.min.css')}}" rel="stylesheet"/>
		<link rel="stylesheet" type="text/css" href="{{URL::asset('admin/assets/css/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('page-header')
	<!--Page header-->
        <div class="page-header">
            <div class="page-leftheader">
                <h4 class="page-title mb-0">{{ $title }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Settings</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/delivery')}}"><i class="fe fe-grid mr-2 fs-14"></i>Delivery</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
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
										{{ Form::open(array('url' => "/admin/delivery/save", 'id' => 'deliveryForm', 'name' => 'deliveryForm', 'class' => '')) }}
										    {{Form::hidden('id',0,['id'=>'deliveryid'])}}
                                            <div class="py-1">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label class="form-label">Delivery Type Name <span class="text-red">*</span></label>
                                                            {!! Form::text('delivery_type', null, ['class' => 'form-control','rows' => 3,'id'=>'delivery_type']) !!}
                                                        </div>
                                                        @error('delivery_type')
                                                            <p style="color: red" class="error">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label class="form-label">Description</label>		
                                                            {!! Form::textarea('delivery_description', null, ['class' => 'form-control','rows' => 4,'cols'=>54, 'id'=>'delivery_description']) !!}
                                                        </div>
                                                        @error('delivery_description')
                                                            <p style="color: red" class="error">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label class="form-label">Delivery Charges <span class="text-red">*</span></label>
                                                            {!! Form::text('delivery_charges', null, ['class' => 'form-control','rows' => 3,'id'=>'delivery_charges']) !!}
                                                        </div>
                                                        @error('delivery_charges')
                                                            <p style="color: red" class="error">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col d-flex justify-content-end">
                                                        <input class="btn btn-primary" type="submit" id="frontval" value="Save Changes">
                                                    </div>
                                                </div>
                                            </div>
										{{Form::close()}}
									</div>
								</div>
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

<style type="text/css">
	.radio-opts {
		margin-top: 10px;
	}

.chosen-container-multi .chosen-choices {
    padding: 4px 10px;
    border: 1px solid #e3e4e9;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    border-radius: 5px;
}

</style>

@endsection
@section('js')
		<!-- INTERNAl Data tables -->
<script src="{{URL::asset('admin/assets/js/jquery.validate.min.js')}}"></script>

<script src="{{URL::asset('admin/assets/js/chosen.jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('admin/assets/js/moment.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/bootstrap-datetimepicker.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/datatables.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/bootstrap-datepicker.js')}}"></script>
		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>
<script type="text/javascript">

function readURL(input)
{
    if (input.files && input.files[0])
    {
        $("#errNm1").empty();
        var reader = new FileReader(); 
        reader.onload = function (e) { $('#bannerForm img#'+input.id+'_img').attr('src', e.target.result); $('#bannerForm #'+input.id+'_img').show(); }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#frontval").click(function(){
    $("#deliveryForm").validate({
        ignore: [],
        rules: {
            delivery_type : {
                required: true
            },
            delivery_description : {
                required: true
            },
            delivery_charges : {
                required: true
            }
        },

        messages : {
            delivery_type: {
                required: "Delivery Type is required."
            },
            delivery_description: {
                required: "Delivery Description is required."
            },
            delivery_charges: {
                required: "Delivery Charges is required."
            }
        },

        errorPlacement: function(error, element) {
            $("#errNm1").empty();
            console.log($(error).text());
            if (element.attr("name") == "image[]" )
            {
                $("#errNm1").text($(error).text());                
            }
            else if (element.attr("name") == "sale_start" )
            {
                $("#errNm3").text($(error).text());    
            }
            else
            {
                error.insertAfter(element)
            }
        },

    });
});

$(".datepicker").datepicker({ 
    autoclose: true, 
    todayHighlight: true,
    startDate: new Date()
}).datepicker('update', new Date());


	// Prompt
	$(".deletemodule").on("click", function(e){

		var moduleid = jQuery(this).parents("tr").find("#moduleid").data("value");
		$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this module?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){



			if (inputValue == true) {
			 $.ajax({
            type: "POST",
            url: '{{url("/admin/modules/delete")}}',
            data: { "_token": "{{csrf_token()}}", id: moduleid},
            success: function (data) {
            	// alert(data);
            	if(data ==1){
            		location.reload();
            	}
            
            }
        });

			}
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


<script type="text/javascript">


$(document).ready(function(){


$(".chosen-select").chosen({
  no_results_text: "Oops, nothing found!"
});

$("#banner_type").change(function(){

var b_type =$(this).val();
if(b_type ==1){
	$("#add_more").show('1000');
	$("#alt_text_div").show('1000');
	$("#status_div").removeClass("col-md-offset-6");
	$("#media_type option[value='video']").removeAttr("disabled");
}else{
	//$("#add_more").hide('1000');
	$("#alt_text_div").hide('1000');
	$("#status_div").addClass("col-md-offset-6");
	$('#media_type option[value="video"]').attr("disabled",true);

$("#bannerForm #prd_imgs .img_row:not(#img_row_0)").remove();
}

});

$("#media_type").change(function(){

var media_type =$(this).val();

if(media_type == "video"){
	$("#image_type").hide('1000');
	$("#video_type").show('1000');
}else{
	$("#image_type").show('1000');
	$("#video_type").hide('1000');
}

});


});

 function date_check() 
    {
      var sdate=$("[name='valid_from']").val();
      var tdate=$("[name='valid_to']").val();
      
      $('#valid_from').datepicker('setStartDate',new Date(sdate));
      if(sdate && tdate)
      {
        var d1 = Date.parse(sdate);
        var d2 = Date.parse(tdate);
        if (d1 > d2) 
        {
          $("[name='valid_to']").val(sdate);
          $('#valid_to').datepicker('setStartDate',new Date(sdate));
        }
      }
      
    }

</script>
@endsection