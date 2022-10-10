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
		<link href="{{URL::asset('admin/assets/css/daterangepicker.css')}}" rel="stylesheet" />
			<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/hot-sneaks/jquery-ui.css" />

@endsection
@section('page-header')
						<!--Page header-->

<style type="text/css">
	.filter-radio {
 display: table-cell;
    vertical-align: middle
	}
</style>
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">{{ $title }}</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Master Settings</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
											<div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">


								<label class="form-label" for="filterSel" style="margin-right: 8px;">Filter </label>
							    						<select class="form-control" id="filterSel" style="margin-right: 30px;">
<option value="">Status</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
									<a href="{{ url('admin/coupons/create/') }}"  class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
								</div>
							</div>



						</div>

						<div class="row">
							<div class="col-3">
<div class="page-filters">
<div  class="datepicker input-group date">
<input class="form-control" name="valid_from"  id="valid_from" type="text" readonly   />
<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
<input type="hidden" id="startdate" value="<?php echo date("Y-m-d"); ?>">
<input type="hidden" id="enddate" value="<?php echo date("Y-m-d"); ?>">
</div>

</div>
							</div>
							<div class="col-4">

								<div class="page-filters">

<div class="price_filter">
<div id="mySlider"></div>
<p>
<label for="price" style="font-family:Verdana;">Price Range:</label>
<span type="text" id="price" style="border:0; color:#fa4b2a; font-weight:bold;"></span>
</p>
<input type="hidden" id="startprice" value="<?php echo $minprice; ?>">
<input type="hidden" id="endprice" value="<?php echo $maxprice; ?>">
</div>
</div>
								
							</div>
							<div class="col-5">
							<div class="page-filters" style="display: inline-flex;">

<div class="price_filter" style="margin-right: 20px;">

<input checked="checked" type="radio"  class="filter-radio"  name="typesel" id="amount" value="1"/>
<label for="amount">Amount</label>
<input type="radio"  class="filter-radio" name="typesel"  id="percentage" value="2"/>
<label for="percentage">Percentage</label>
</div>

<a href="#" id="viewfilter"  class="mr-2 btn btn-info btn-sm"><i class="fe fe-edit mr-1"></i> View</a>

</div>								
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
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table class="table table-bordered border-top text-nowrap couponslist" id="couponslist">
															<thead>
																<tr>
																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	<th class="border-bottom-0 w-20">Title</th>
																	
																	<th class="border-bottom-0 w-30">Code</th>
																	<th class="border-bottom-0 w-30">Offer Value</th>
																	<th class="border-bottom-0 w-30">Offer Type</th>
																	<th class="border-bottom-0 w-30">Valid Till</th>
																	<th class="border-bottom-0 w-30">Status</th>
																	<th class="border-bottom-0 w-15">Created On</th>
																	<th class="border-bottom-0 w-30">Log</th>						
																	<th class="border-bottom-0 w-10 notexport">Actions</th>
																</tr>
															</thead>

															<tbody>

																@if($coupons && count($coupons) > 0)
                    											@foreach($coupons as $row)
																<tr>
																	<td class="align-middle select-checkbox" id="moduleid" data-value="{{$row['id']}}">
																		<label class="custom-control custom-checkbox">
																			
																			<!--{{ $loop->iteration }}-->
																		</label>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																	
																	<h6 class=" font-weight-bold">{{$row['cpn_title']}} </h6>
																				
																			
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p>{{$row['ofr_code']}}</p>
																	</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{$row['ofr_value']}}</p>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{ ucfirst($row['ofr_type']) }}</p>
																		</div>
																	</td>
																	<td class="text-nowrap align-middle">
														<?php 
													$valid_till = "";
													if($row['validity_type'] == "days") 
													{
														$days = $row['valid_days'];
												$valid_till = date('d-m-Y', strtotime($row['created_at'] ."+$days days"));
													}
													
													else {
														$valid_till = $row['valid_to'];
													}
													
													?>		
																		<p>{{ $valid_till }}</p>
																	</td>
																	
																	<td class="text-nowrap align-middle"  data-search="@if($row['is_active'] ==1){{ "Active" }}@else{{ "Inactive" }}@endif">
																		
																	<div class="switch">
																	<input class="switch-input status-btn ser_status" data-selid="{{$row['id']}}"  id="status-{{$row['id']}}"  type="checkbox"  @if($row['is_active'] ==1) {{ "checked" }} @endif >
																	<label class="switch-paddle" for="status-{{$row['id']}}">
																	<span class="switch-active" aria-hidden="true">Active</span>
																	<span class="switch-inactive" aria-hidden="true">Inactive</span>
																	</label>
																	</div>                    
                  
																	</td>
																	<td class="text-nowrap align-middle"><span>{{date('d M Y',strtotime($row['created_at']))}}</span></td>
																	
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			
																			<a href="{{ url('admin/coupons/log/') }}/{{$row['id']}}"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
																			
																		</div>
																	</td>
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			
																			<a href="{{ url('admin/coupons/edit/') }}/{{$row['id']}}"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
																			<button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn({{$row['id']}});" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
																		</div>
																	</td>
																</tr>
																     @endforeach
                @endif
																
																
																
																
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->


						<!-- Add Form Modal -->

							<div class="modal fade" role="dialog" tabindex="-1" id="add-form-modal">
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Create Coupon</h5>
												<button type="button" class="close" data-dismiss="modal">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												
											</div>
										</div>
									</div>
								</div>
								

					</div>
				</div><!-- end app-content-->
            </div>
<style type="text/css">
	table.dataTable tr.parent {
animation: none !important;
	}
	table.dataTable tr.selected p {
color: #fff;
	}
</style>

@endsection
@section('js')
		<!-- INTERNAl Data tables -->

		<script src="{{URL::asset('admin/assets/js/datatable/tables/coupons-datatable.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/moment.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/daterangepicker.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>
	
	<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/sweet-alert.js')}}"></script>
<script type="text/javascript">


function deletecpn(cpnid){
$('body').removeClass('timer-alert');
		swal({
			title: "Delete Confirmation",
			text: "Are you sure you want to delete this coupon?",
			// type: "input",
			showCancelButton: true,
			closeOnConfirm: true,
			confirmButtonText: 'Yes'
		},function(inputValue){



			if (inputValue == true) {
			 $.ajax({
            type: "POST",
             url: '{{url("/admin/coupons/delete")}}',
            data: { "_token": "{{csrf_token()}}", id: cpnid},
            success: function (data) {
            	// alert(data);
            	if(data ==1){
            		location.reload();
            	}
            
            }
        });

			}
		});
}

$(document).ready(function(){

  $('#valid_from').daterangepicker
        (
          {
            locale: {
                      format: 'DD/MM/YYYY'
                    },
            ranges:
            {
              'Today'       : [moment(), moment()],
              'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Tomorrow'    : [moment().add(1, 'days'), moment().add(1, 'days')],
              'Next 7 Days' : [moment(),moment().add(6, 'days')],
              'Next 30 Days': [moment(),moment().add(29, 'days')],
              'This Month'  : [moment().startOf('month'), moment().endOf('month')],
              'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
          },
          function(start, end, label)
          {
            startDate = start.format('YYYY-MM-DD');
            endDate = end.format('YYYY-MM-DD');
            console.log('A date range was chosen: ' + startDate + ' to ' + endDate);
            $("#startdate").val(startDate);
            $("#enddate").val(endDate);
      
        
          }
        );
});



	jQuery(document).ready(function(){

 
// Custom filtering function which will search data in column four between two values

 
$('body').on('change','.ser_status',function(){  

        
        
        var selid = jQuery(this).data("selid");
        
        var sestatus='0';
        if($(this).prop('checked') == true)
        {
        sestatus='1';
        }
        
        $.ajax({
        type: "POST",
        url: '{{url("/admin/coupons/status")}}',
        data: { "_token": "{{csrf_token()}}", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
           
              
             toastr.success("Tag activated successfully.");   
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
              toastr.success("Tag deactivated successfully."); 

            }
            var table = $.fn.dataTable.tables( { api: true } );
            table.rows().invalidate().draw();
        }else {
        toastr.error("Failed to update status."); 	
        }
        
        
        }
        });
        });
        
        $('#userrole').DataTable({
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_',
		}
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


        $(document).ready(function() {
        $( "#mySlider" ).slider({
          range: true,
          min: <?php echo $minprice; ?>,
          max: <?php echo $maxprice; ?>,
          values: [ <?php echo $minprice; ?>,<?php echo $maxprice; ?> ],
          slide: function( event, ui ) {
         $( "#price" ).text( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
         
         $("#startprice").val(ui.values[ 0 ]);
            $("#endprice").val(ui.values[ 1 ]);

         }
      });
          
      $( "#price" ).text( "$" + $( "#mySlider" ).slider( "values", 0 ) +" - $" + $( "#mySlider" ).slider( "values", 1 ) );


      $("#viewfilter").click(function(){
      	alert("clicked");

			var startdate = $("#startdate").val();
			var enddate = $("#enddate").val();
			var startprice = $("#startprice").val();
			var endprice = $("#endprice").val();
			var typesel = $('input[name="typesel"]:checked').val();



			$.ajax({
			type: "POST",
			url: '{{url("/admin/coupons/filter")}}',
			 data: { "_token": "{{csrf_token()}}", startdate: startdate,enddate:enddate,startprice:startprice,endprice:endprice,typesel:typesel},
			success: function (data) {
			console.log(data);
			var table = $.fn.dataTable.tables( { api: true } );
			if(data =="0") {
				alert("no data");
				table.clear().draw();
			}else {
				// $("#couponslist tbody").html(data);
			 alert("data");

 table.clear().draw();
 //   table.rows.add(data); // Add new data
 //   table.columns.adjust().draw();
   table.rows.add($(data)).columns.adjust().draw();

			}
			
			}
			});

      });
          
         });  
    </script>

@endsection