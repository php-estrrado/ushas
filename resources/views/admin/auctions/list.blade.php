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
		<link href="{{URL::asset('admin/assets/css/jquery-ui.css')}}" rel="stylesheet" />
			

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
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Auction Management</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#">{{ $title }}</a></li>
								</ol>
							</div>
											<div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">


								<label class="form-label" for="filterSel" style="margin-right: 8px;">Filter </label>
							    <select class="form-control" id="filterSel" style="margin-right: 30px;">
								<option value="">All Status</option>
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
								</select>
								<div class="btn btn-list">
						
									<!-- <a href="{{ url('admin/coupons/create/') }}"  class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a> -->
								</div>
							</div>



						</div>
						<div class="row" id="filterrow">
							<div class="plus-minus-toggle collapsed"><p>Additional Filters</p></div> 
				

						</div>
							 <div class="row" id="filtersec" style="display:none;">
								<div class="col-4">
								<div class="page-filters">
								<div  class="datepicker input-group date">
								<input class="form-control" name="valid_from"  id="valid_from" type="text" readonly   />
								<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								<input type="hidden" id="startdate" value="<?php echo date("Y-m-d"); ?>">
								<input type="hidden" id="enddate" value="<?php echo date("Y-m-d"); ?>">
								</div>

								</div>
								</div>
								<div class="col" style="width: 18.825%;flex: 0 0 18.825%;max-width: 18.825%;">

								<div class="page-filters">

								<div class="price_filter">
								<div id="mySlider"></div>
								<p>
								<label for="price" style="font-family:Verdana;">Price Range:</label>
								<span type="text" id="price" style="border:0; color:#fff; font-weight:bold;"></span>
								</p>
								<input type="hidden" id="startprice" value="<?php  echo $minprice; ?>">
								<input type="hidden" id="endprice" value="<?php echo $maxprice; ?>">
								</div>
								</div>

								</div>
								<div class="col">
								<div class="page-filters">

								<select class="form-control dropfill" id="filterSell" >
								<option value="">All Sellers</option>
								@if($sellers && count($sellers) > 0)
								@foreach($sellers as $kv)
								<option value="{{ $kv->seller_id }}">{{ $kv->store_name }}</option>
								@endforeach
								@endif

								</select>


								</div>								
								</div>
								<div class="col">
								<div class="page-filters" >

								<select class="form-control dropfill" id="filterPr">
								<option value="">All Products</option>
								@if($products && count($products) > 0)
								@foreach($products as $kp)
								<option value="{{ $kp->id }}">{{ $kp->name }}</option>
								@endforeach
								@endif

								</select>


								</div>								
								</div>

								<div class="col">
								<a  id="viewfilter"  class="mr-2 btn btn-info btn-sm pointer"><i class="fa fa-check-circle"></i> Apply</a>
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
														<table class="table table-bordered border-top text-nowrap auctionslist" id="auctionslist">
															<thead>
																<tr>

																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	<th class="border-bottom-0 w-20">Auction ID</th>
																	
																	<th class="border-bottom-0 w-30">Product Name</th>
																	<th class="border-bottom-0 w-30">Description</th>
																	<th class="border-bottom-0 w-30">Start Date</th>
																	<th class="border-bottom-0 w-30">End Date</th>
																	<th class="border-bottom-0 w-30">Seller</th>
																	<th class="border-bottom-0 w-30">Status</th>
																	<th class="border-bottom-0 w-30">Min Bid Price</th>
																	<th class="border-bottom-0 w-30">Shipping Costs</th>
																	<th class="border-bottom-0 w-30">No of Bids</th>
																	<th class="border-bottom-0 w-30">Bid allocated to</th>
																	
																	<!-- <th class="border-bottom-0 w-15">Created On</th> -->
																	<th class="border-bottom-0 w-30 notexport">Bid Logs</th>						
																	<!-- <th class="border-bottom-0 w-10 notexport">Actions</th> -->
																</tr>
															</thead>

															<tbody>

																@if($auctions && count($auctions) > 0)
                    											@foreach($auctions as $row)
																<tr>
																	<td class="align-middle select-checkbox" id="moduleid" data-value="{{$row['id']}}">
																		<label class="custom-control custom-checkbox">
																			
																			<!--{{ $loop->iteration }}-->
																		</label>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p>{{$row['auction_code']}}</p>
																	</div>
																	</td>

																	<td class="align-middle" >
																	    @php $prod_img= config('app.storage_url').$row['product_img'];
																	    @endphp
																	    <div class="d-flex">
																			@if($row['product_img'])
																	<span class="avatar brround avatar-md d-block" style="background-image: url(<?php echo $prod_img; ?>)"></span>
																			@else
																			<span class="avatar brround avatar-md d-block" ></span>
																			@endif
																			<div class="ml-3 mt-1">
																				<p>{{ $row['product_name'] }}</p>
																			</div>
																		</div>
																	
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p>{{ Str::limit($row['auct_desc'], 15) }}</p>
																	</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{date('Y-m-d H:i:s', strtotime($row['auct_start']))}}</p>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{date('Y-m-d H:i:s', strtotime($row['auct_end']))}}</p>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{$row['seller_name']}}</p>
																		</div>
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
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{$row['min_bid_price']}}</p>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{$row['shipping_cost_id']}}</p>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{count($row['bids'])}}</p>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p>{{$row['bid_allocated_to_user']}}</p>
																		</div>
																	</td>
																	
																	
																	
																	
																	<!-- <td class="text-nowrap align-middle"><span>{{date('d M Y',strtotime($row['created_at']))}}</span></td> -->
																	
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			
																			<a href="{{ url('admin/auctions/log/') }}/{{$row['id']}}"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
																			
																		</div>
																	</td>
																	<!-- <td class="align-middle">
																		<div class="btn-group align-top">
																			
																			<a href="{{ url('admin/coupons/edit/') }}/{{$row['id']}}"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
																			<button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn({{$row['id']}});" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
																		</div>
																	</td> -->
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

							<div style="display:none;">
								<table id="hiddentable" style="display: none;">
									<tbody>
										
									</tbody>
								</table>
								
							</div>
								

					</div>
				</div><!-- end app-content-->
            </div>

            <div id="loader" class="d-none"><div class="spinner1 content-spin"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>

<style type="text/css">
	table.dataTable tr.parent {
animation: none !important;
	}
	table.dataTable tr.selected p {
color: #fff;
	}

#viewfilter {
	display: block;
    margin: 5px;
    width: 90px;
    text-align: center;
}
</style>

@endsection
@section('js')
		<!-- INTERNAl Data tables -->

		<script src="{{URL::asset('admin/assets/js/datatable/tables/auctions-datatable.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/moment.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/daterangepicker.js')}}"></script>
	<!-- INTERNAL Popover js -->
		<script src="{{URL::asset('admin/assets/js/popover.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/comboTreePlugin.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/jquery-ui.js')}}"></script>

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
        url: '{{url("/admin/auctions/status")}}',
        data: { "_token": "{{csrf_token()}}", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
           
                toastr.success("Auction activated successfully.");
             
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
                toastr.success("Auction deactivated successfully."); 

            }
            var table = $.fn.dataTable.tables( { api: true } );
            table.rows().invalidate().draw();
        }else {
        toastr.error("Failed to update status."); 	
        }
        
        
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
			//   	alert("clicked");
			// $('#auctionslist tbody').append($('#loader').html()); 
			$('#auctionslist').addClass('blur'); 
			var startdate = $("#startdate").val();
			var enddate = $("#enddate").val();
			var startprice = $("#startprice").val();
			var endprice = $("#endprice").val();
			var filterSell = $("#filterSell").val();
			var filterPr = $("#filterPr").val();

			console.log(startdate+" "+enddate+" "+startprice+" "+endprice+" "+filterSell+" "+filterPr);


			$.ajax({
			type: "POST",
			url: '{{url("/admin/auctions/filter")}}',
			data: { "_token": "{{csrf_token()}}", startdate: startdate,enddate:enddate,startprice:startprice,endprice:endprice,filterSell:filterSell,filterPr:filterPr},
			success: function (data) {
			    console.log(data);
			var table = $.fn.dataTable.tables( { api: true } );
			if(data =="0") {
			// alert("no data");
			table.clear().draw();
			}else {
			$("#hiddentable tbody").html(data);
			// alert(data.length);
			html = data;
			i=0;
			var htmlFiltered = $(html).find("tr")
			
			table.clear().draw();
			$("#hiddentable tr").each(function(index, tr) { 
			console.log(index);
			console.log(tr);
			table.row.add($(tr)).columns.adjust().draw();
			});

			//   table.rows.add(data); // Add new data
			//   table.columns.adjust().draw();


			}
			$('#auctionslist').removeClass('blur');
			// $('#auctionslist tbody').remove($('#loader').html()); 


			}
			});

			});
          
         });  
    </script>
<script type="text/javascript">
$(function() {
$('.plus-minus-toggle').on('click', function() {
$(this).toggleClass('collapsed');
$('#filtersec').toggle('slow');
});
});
</script>
@endsection