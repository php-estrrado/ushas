@extends('layouts.admin')
@section('css')
    <link href="{{URL::asset('admin/assets/traffic/web-traffic.css')}}" rel="stylesheet" type="text/css">
    		<link href="{{URL::asset('admin/assets/css/daterangepicker.css')}}" rel="stylesheet" />
<style>
.card-options {
	margin-left: 50%;
}
</style>
@endsection

   @section('page-header')
						<!--Page header-->
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Hi! Welcome Back</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="{{url('/' . $page='#')}}"><i class="fe fe-home mr-2 fs-14"></i>Home</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="{{url('/' . $page='#')}}">Sales Dashboard</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								<!-- <div class="btn btn-list">
									<a href="{{url('/' . $page='#')}}" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="{{url('/' . $page='#')}}" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>
									<a href="{{url('/' . $page='#')}}" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a>
								</div> -->
							</div>
						</div>
						<!--End Page header-->
						@endsection
						@section('content')						
						<!-- Row-1 -->
						<div class="row">
						    <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
								<div class="card overflow-hidden dash1-card border-0">
									<div class="card-body">
										<p class=" mb-1 ">Total Sales</p>
										<h2 class="mb-1 number-font">{{getCurrency()->name}} {{ round($tot_sales) }}</h2>
										<small class="fs-12 text-muted">Total Sales till now</small>
										<span class="ratio bg-warning"><a href="{{ url('admin/sales/orders') }}"><i class="fa fa-arrow-circle-right"></i> View Details</a></span>
									</div>
									
								</div>
							</div>
							<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
								<div class="card overflow-hidden dash1-card border-0">
									<div class="card-body">
										<p class=" mb-1 ">Total Orders</p>
										<h2 class="mb-1 number-font">{{ $orders_c }}</h2>
										<small class="fs-12 text-muted">Total orders till now</small>
										<span class="ratio bg-info"><a href="{{ url('admin/sales/orders') }}"><i class="fa fa-arrow-circle-right"></i> View Details</a></span>
									</div>
									
								</div>
							</div>
							<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
								<div class="card overflow-hidden dash1-card border-0">
									<div class="card-body">
										<p class=" mb-1 ">Total Products</p>
										<h2 class="mb-1 number-font">{{ $products_c }}</h2>
										<small class="fs-12 text-muted">Total current active products</small>
										<span class="ratio bg-danger"><a href="{{ url('admin/products') }}"><i class="fa fa-arrow-circle-right"></i> View Details</a></span>
									</div>
									
								</div>
							</div>
							
							
						</div>


						<div class="row">
						    
						    <div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
								<div class="card overflow-hidden dash1-card border-0">
									<div class="card-body">
										<p class=" mb-1 ">Total Customers</p>
										<h2 class="mb-1 number-font">{{ $customer_c }}</h2>
										<small class="fs-12 text-muted">Total current active customers</small>
										<span class="ratio bg-warning"><a href="{{ url('admin/customer') }}"><i class="fa fa-arrow-circle-right"></i> View Details</a></span>
									</div>
									
								</div>
							</div>
							
							<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
								<div class="card overflow-hidden dash1-card border-0">
									<div class="card-body">
										<p class=" mb-1 ">Today’s Customers</p>
										<h2 class="mb-1 number-font">{{ $customer_today_c }}</h2>
										<small class="fs-12 text-muted">Registrations</small>
										<span class="ratio bg-info"><a href="{{ url('admin/customers/today') }}"><i class="fa fa-arrow-circle-right"></i> View Details</a></span>
									</div>
									
								</div>
							</div>
							<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">
								<div class="card overflow-hidden dash1-card border-0">
									<div class="card-body">
										<p class=" mb-1 ">Pending Orders</p>
										<h2 class="mb-1 number-font">{{ $ord_pending_c }}</h2>
										<small class="fs-12 text-muted">Total Pending Orders</small>
										<span class="ratio bg-danger"><a href="{{ url('admin/sales/orders') }}"><i class="fa fa-arrow-circle-right"></i> View Details</a></span>
									</div>
									
								</div>
							</div>
							<!--<div class="col-xl-4 col-lg-6 col-md-6 col-xm-12">-->
							<!--	<div class="card overflow-hidden dash1-card border-0">-->
							<!--		<div class="card-body">-->
							<!--			<p class=" mb-1 ">Total Product Requests</p>-->
							<!--			<h2 class="mb-1 number-font">{{ $product_req_today_c }}</h2>-->
							<!--			<small class="fs-12 text-muted">Requests</small>-->
							<!--			<span class="ratio bg-danger"><a href="{{ url('admin/product/requests') }}"><i class="fa fa-arrow-circle-right"></i> View Details</a></span>-->
										
							<!--		</div>-->
									
							<!--	</div>-->
							<!--</div>-->
							
						</div>
						<!-- End Row-1 -->

						<!-- Row-3 -->
						<div class="row">
							
							<div class="col-xl-4  col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="d-flex align-items-end justify-content-between">
											<div>
												<p class=" mb-1 fs-14">Gross Revenue</p>
												<h2 class="mb-0"><span class="number-font1">{{getCurrency()->name}} {{ $revenue }}</span><span class="text-muted fs-11">
													<br><span class="text-success"> this month</span></h2>

											</div>
											<span class="text-secondary fs-35 dash1-iocns bg-secondary-transparent border-secondary"><i class="las la-hand-holding-usd"></i></span>
										</div>
										
									</div>
								</div>
							</div>
							<div class="col-xl-4  col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="d-flex align-items-end justify-content-between">
											<div>
												<p class=" mb-1 fs-14">Orders Processing</p>
												<h2 class="mb-0"><span class="number-font1">{{ $ord_processing_c }}</span><br><span class="text-muted fs-11"><span class="text-success"> this month</span></h2>

											</div>
											<span class="text-primary fs-35 dash1-iocns bg-primary-transparent border-primary"><i class="fe fe-refresh-cw"></i></span>
										</div>
										
									</div>
								</div>
							</div>
							<div class="col-xl-4  col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="d-flex align-items-end justify-content-between">
											<div>
												<p class=" mb-1 fs-14">Orders Completed</p>
												<h2 class="mb-0"><span class="number-font1"> {{ $ord_completed_c }} </span><br><span class="text-muted fs-11"><span class="text-success"> this month</span></h2>
											</div>
											<span class="text-secondary fs-35 dash1-iocns bg-secondary-transparent border-secondary"><i class="fe fe-check-circle"></i></span>
										</div>
										
									</div>
								</div>
							</div>
							<!--<div class="col-xl-3  col-md-12">-->
							<!--	<div class="card">-->
							<!--		<div class="card-body">-->
							<!--			<div class="d-flex align-items-end justify-content-between">-->
							<!--				<div>-->
							<!--					<p class=" mb-1 fs-14">Out of stock</p>-->
							<!--					<h2 class="mb-0"><span class="number-font1">{{ $prd_out_stock_soon_c }}</span><br><span class="text-muted fs-11"><span class="text-danger"> soon</span></h2>-->
							<!--				</div>-->
							<!--				<span class="text-info fs-35 bg-info-transparent border-info dash1-iocns"><i class="fa fa-exclamation-triangle text-warning"></i></span>-->
							<!--			</div>-->
										
							<!--		</div>-->
							<!--	</div>-->
							<!--</div>-->
						</div>
						<!-- End Row-3 -->

						<!--Row-->
						<div class="row" >
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Products - Out of stock soon</h3>
										<div class="card-options">
											
										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-vcenter text-nowrap mb-0 table-striped table-bordered border-top dashboard-table" id="dashboard-table">
												<thead class="">
													<tr>
														<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
														<th>Product</th>
														<th>Category</th>
														<th>Stock</th>
													
													</tr>
												</thead>
												<tbody>
													@if($prd_out_stock_soon && count($prd_out_stock_soon) > 0)
                    											@foreach($prd_out_stock_soon as $row)
                    									@if(($row->product_type ==2 && $row->visible ==0)|| ($row->product_type ==1) )		 
													<tr>
														
														<td class="align-middle select-checkbox" id="moduleid" data-value="{{$row->id}}">
																		<label class="custom-control custom-checkbox">
																			
																			<!--{{ $loop->iteration }}-->
																		</label>
																	</td>
														<td class="align-middle" >

														<div class="d-flex">
														@if($row->prdImage->first())

														 @php $prod_img=config('app.storage_url').''.$row->prdImage->first()->image;
																	    @endphp
														<span class="avatar brround avatar-md d-block" style="background-image: url(<?php echo $prod_img; ?>)"></span>
														@else
														<span class="avatar brround avatar-md d-block" ></span>
														@endif
														<div class="ml-3 mt-1">
														<h6 class="mb-0 font-weight-bold">{{ $row->name }}</h6>
														</div>
														</div>
														</td>
													
														<td>@if($row->category) {{ $row->category->cat_name }} @endif</td>
														<td>{{ $row->prdStock($row->id) }}</td>
														
													</tr>
													@endif
													@endforeach
													@endif
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--End row-->

								<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Overall Sales statistics</h3>
										<div class="card-options">
										<div class="row">
										<div class="col-md-4">
										<select id="year" name="year" class="form-control ">
											<option value="0">Year</option>
											{{ $last= date('Y')-20 }}
											{{ $now = date('Y') }}

											@for ($i = $now; $i >= $last; $i--)
												<option value="{{ $i }}">{{ $i }}</option>
											@endfor
										</select>
										</div>
										<div class="col-md-8">
											<div class="btn-group p-0">
											
													<div  class="datepicker input-group date">
								
								<input class="form-control" name="valid_from_sale"  id="valid_from_sale" type="text" readonly   />
								<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
								
								</div>
											</div>
											
										</div>
										</div>
										</div>
									</div>
									<div class="card-body">
										
										 <div class="demo-container" id="placeholder_loader_sale">
      <div id="placeholder_sale" class="demo-placeholder"></div>
    </div>

    <!--<div class="demo-container" style="height:150px;">-->
    <!--  <div id="overview_sale" class="demo-placeholder"></div>-->
    <!--</div>-->
									</div>
								</div>
							</div>
							
						</div>
						
						<!-- Web TrafficRow-2 -->
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Web Traffic Analysis</h3>
										<div class="card-options">
											<div class="btn-group p-0">
													<div  class="datepicker input-group date">
								<input class="form-control" name="valid_from"  id="valid_from" type="text" readonly   />
								<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
				
								</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										
										 <div class="demo-container"  id="placeholder_loader">
      <div id="placeholder" class="demo-placeholder"></div>
    </div>

    <!--<div class="demo-container" style="height:150px;">-->
    <!--  <div id="overview" class="demo-placeholder"></div>-->
    <!--</div>-->
									</div>
								</div>
							</div>
							
						</div>
						<!-- Web TrafficEnd Row-2 -->

					</div>
				</div>
				<!-- End app-content-->
			</div>

        <div id="loader" class="d-none">
        <div class="spinner1"  style="position: absolute;top: 100px;left: 50%;">
        <div class="double-bounce1"></div><div class="double-bounce2"></div>
        </div>
        </div>			
			
@endsection
@section('js')

<!--INTERNAL Peitychart js-->
<script src="{{URL::asset('assets/plugins/peitychart/jquery.peity.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/peitychart/peitychart.init.js')}}"></script>
<script src="{{URL::asset('admin/assets/js/datatable/tables/dashboard-table.js')}}"></script>
<!--INTERNAL Apexchart js-->

<script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/globalize.js')}}"></script>

  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.canvaswrapper.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.colorhelpers.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.saturated.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.browser.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.drawSeries.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.uiConstants.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.time.js')}}"></script>
  

  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.navigate.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.touchNavigate.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.hover.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.touch.js')}}"></script>
  <script language="javascript" type="text/javascript" src="{{URL::asset('admin/assets/traffic/jquery.flot.selection.js')}}"></script>
<script type='text/javascript' src="{{URL::asset('admin/assets/traffic/jquery.flot.axislabels.js')}}"></script>

	<script src="{{URL::asset('admin/assets/js/moment.min.js')}}"></script>
		<script src="{{URL::asset('admin/assets/js/daterangepicker.js')}}"></script>


  <script type="text/javascript">

     
$(document).ready(function(){

 
});


  $(function() {
	  
	  $('body').on('change', '#year', function() {
	var year= $(this).val();
	var startDate = moment(year+"/01/01").format('YYYY/MM/DD  HH:mm:ss');  
	
	var endDate = moment(year+"/12/31").format('YYYY/MM/DD  HH:mm:ss');  
	
    //var year= $(this).val();
	$.ajax({
                type: "POST",
                url: '{{url("/admin/salelog")}}',
                data: { "_token": "{{csrf_token()}}",startDate: startDate,endDate:endDate},
                success: function (data) {
                var obj = JSON.parse(data);
                
                console.log(obj);
                
                $.plot("#placeholder_sale", [obj], {
                xaxis: {
                mode: "time",
                min: (new Date(startDate)).getTime(),
                max: (new Date(endDate)).getTime(),
                timeBase: "milliseconds",
                autoScale: "none"
                }
                });
                init_graph_sale(obj,startDate,endDate,1);
                $('#placeholder_loader_sale .spinner1').remove();
                $('#placeholder_sale').removeClass('blur'); 
                }
                });

}); 

    // var d = <?php echo json_encode($traffic_arr); ?>;
    var d = <?php echo json_encode($traffic_arr); ?>;
    var sales = <?php echo json_encode($sales_arr); ?>;

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
              'This Month'  : [moment().startOf('month'), moment().endOf('month')],
              'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens:'left',
          },
          function(start, end, label)
          {
            startDate = start.format('YYYY/MM/DD  HH:mm:ss');
            endDate = end.format('YYYY/MM/DD  HH:mm:ss');
            console.log('A date range was chosen: ' + startDate + ' to ' + endDate);
       
            // $.plot("#placeholder", [d], {
            // xaxis: {
            // mode: "time",
            // min: (new Date(startDate)).getTime(),
            // max: (new Date(endDate)).getTime(),
            // timeBase: "milliseconds",
            // autoScale: "none"
            // }
            // });
            // init_graph(d,startDate,endDate,1);
            
                $('#placeholder_loader').append($('#loader').html());
                $('#placeholder').addClass('blur'); 
                
                $.ajax({
                type: "POST",
                url: '{{url("/admin/visitlog")}}',
                data: { "_token": "{{csrf_token()}}", startDate: startDate,endDate:endDate},
                success: function (data) {
                var obj = JSON.parse(data);
                
                console.log(obj);
                
                $.plot("#placeholder", [obj], {
                xaxis: {
                mode: "time",
                min: (new Date(startDate)).getTime(),
                max: (new Date(endDate)).getTime(),
                timeBase: "milliseconds",
                autoScale: "none"
                }
                });
                init_graph(obj,startDate,endDate,1);
                $('#placeholder_loader .spinner1').remove();
                $('#placeholder').removeClass('blur'); 
                }
                });
          }
        );


        $('#valid_from_sale').daterangepicker
        (
          {
            locale: {
                      format: 'DD/MM/YYYY'
                    },
            startDate: moment().startOf('month'),
            ranges:
            {
              'Today'       : [moment(), moment()],
              'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'This Month'  : [moment().startOf('month'), moment().endOf('month')],
              'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens:'left',
          },
          function(start, end, label)
          {
            startDate = start.format('YYYY/MM/DD  HH:mm:ss');
            endDate = end.format('YYYY/MM/DD  HH:mm:ss');
            console.log('A date range was chosen: ' + startDate + ' to ' + endDate);

            // $.plot("#placeholder_sale", [sales], {
            // xaxis: {
            // mode: "time",
            // min: (new Date(startDate)).getTime(),
            // max: (new Date(endDate)).getTime(),
            // timeBase: "milliseconds",
            // autoScale: "none"
            // }
            // });
            // init_graph_sale(sales,startDate,endDate,1);
            
                $('#placeholder_loader_sale').append($('#loader').html());
                $('#placeholder_sale').addClass('blur'); 
                
                $.ajax({
                type: "POST",
                url: '{{url("/admin/salelog")}}',
                data: { "_token": "{{csrf_token()}}", startDate: startDate,endDate:endDate},
                success: function (data) {
                var obj = JSON.parse(data);
                
                console.log(obj);
                
                $.plot("#placeholder_sale", [obj], {
                xaxis: {
                mode: "time",
                min: (new Date(startDate)).getTime(),
                max: (new Date(endDate)).getTime(),
                timeBase: "milliseconds",
                autoScale: "none"
                }
                });
                init_graph_sale(obj,startDate,endDate,1);
                $('#placeholder_loader_sale .spinner1').remove();
                $('#placeholder_sale').removeClass('blur'); 
                }
                });
          }
        );
    // $.plot("#placeholder", [d], {
    //   xaxis: { mode: "time", timeBase: "milliseconds"}
    // });

    $("#whole").click(function () {
      $.plot("#placeholder", [d], {
        xaxis: { mode: "time", timeBase: "milliseconds" }
      });
    });

    init_graph(d);
    init_graph_sale(sales);
function init_graph(d,startDate='',endDate='',reset=2){


// Add the Flot version string to the footer

for (var i = 0; i < d.length; ++i) {
d[i][0] += 60 * 60 * 1000;
}

// helper for returning the weekends in a period

function weekendAreas(axes) {

var markings = [],
d = new Date(axes.xaxis.min);

// go to the first Saturday

d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
d.setUTCSeconds(0);
d.setUTCMinutes(0);
d.setUTCHours(0);

var i = d.getTime();

// when we don't set yaxis, the rectangle automatically
// extends to infinity upwards and downwards

do {
markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
i += 7 * 24 * 60 * 60 * 1000;
} while (i < axes.xaxis.max);

return markings;
}

var options = {
series: {
lines: {
show: true,
lineWidth: 2
},
shadowSize: 0
},
xaxis: {
mode: "time",
min: (new Date(startDate)).getTime(),
max: (new Date(endDate)).getTime(),
timeBase: "milliseconds",
tickLength: 5,
gridLines: false,
timeBase: "milliseconds",
autoScale: "none"
},
yaxis: {
	axisLabel: "Visitors",
	axisLabelPadding: 3,
},
colors: ['#5b7fff'], 
selection: {
mode: "x"
},

grid: {
hoverable: true,
clickable: false,
borderColor: '#EDEDED',
borderWidth: 1,
labelMargin: 15,
backgroundColor: '#FFF',
markings: weekendAreas
},
};
var reset_options = {
series: {
lines: {
show: true,
lineWidth: 2
},
shadowSize: 0
},
xaxis: {
mode: "time",
min: (new Date(startDate)).getTime(),
max: (new Date(endDate)).getTime(),
timeBase: "milliseconds",
tickLength: 5,
gridLines: false,
timeBase: "milliseconds",

},
yaxis: {
	axisLabel: "Visitors",
	axisLabelPadding: 3,
},
colors: ['#5b7fff'], 
selection: {
mode: "x"
},

grid: {
hoverable: true,
clickable: false,
borderColor: '#EDEDED',
borderWidth: 1,
labelMargin: 15,
backgroundColor: '#FFF',
markings: weekendAreas
},
};
if(reset ==1){
var plot = $.plot("#placeholder", [d], options);
}else {
var plot = $.plot("#placeholder", [d], reset_options);
}


// var overview = $.plot("#overview", [d], {
// series: {
// lines: {
// show: true,
// lineWidth: 2,

// },
// shadowSize: 0
// },
// colors: ['#5b7fff'], 
// xaxis: {
// ticks: [],
// mode: "time"
// },
// yaxis: {
// ticks: [],
// min: 0,
// autoScaleMargin: 0.1
// },
// selection: {
// mode: "x"
// }
// });


window.setInterval(function () {
			plot.setData([
				{ data: d, label: "Count: "}
			]);
		}, 2000);

		$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");

		$("#placeholder").bind("plothover", function (event, pos, item) {

			if (!pos.x || !pos.y) {
				return;
			}

			if ($("#enablePosition:checked").length > 0) {
				var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
				$("#hoverdata").text(str);
			}

			// if ($("#enableTooltip:checked").length > 0) {
				if (item) {
					var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);

					$("#tooltip").html(item.series.label + y)
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);
				} else {
					$("#tooltip").hide();
				}
			// }
		});

		$("#placeholder").bind("plothovercleanup", function (event, pos, item) {
				$("#tooltip").hide();
		});

		$("#placeholder").bind("plotclick", function (event, pos, item) {
			if (item) {
				$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
				plot.highlight(item.series, item.datapoint);
			}
		});

// now connect the two

$("#placeholder").bind("plotselected", function (event, ranges) {

// do the zooming
$.each(plot.getXAxes(), function(_, axis) {
var opts = axis.options;
opts.min = ranges.xaxis.from;
opts.max = ranges.xaxis.to;
});
plot.setupGrid();
plot.draw();
plot.clearSelection();

// don't fire event on the overview to prevent eternal loop

// overview.setSelection(ranges, true);
});

// $("#overview").bind("plotselected", function (event, ranges) {
// plot.setSelection(ranges);
// });

// Add the Flot version string to the footer

$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
}


function init_graph_sale(d,startDate='',endDate='',reset=2){


// Add the Flot version string to the footer

for (var i = 0; i < d.length; ++i) {
d[i][0] += 60 * 60 * 1000;
}

// helper for returning the weekends in a period

function weekendAreas(axes) {

var markings = [],
d = new Date(axes.xaxis.min);

// go to the first Saturday

d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
d.setUTCSeconds(0);
d.setUTCMinutes(0);
d.setUTCHours(0);

var i = d.getTime();

// when we don't set yaxis, the rectangle automatically
// extends to infinity upwards and downwards

do {
markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
i += 7 * 24 * 60 * 60 * 1000;
} while (i < axes.xaxis.max);

return markings;
}

var flotLineOption_set = {
  series: {
    lines: {
      show: true,
      fill: 0.01,
      lineWidth: 2,
    },
    points: {
      show: true,
      radius: 4
    }
  },
  colors: ['#5b7fff'], 
  grid: {
    borderColor: '#eee',
    borderWidth: 1,
    hoverable: true,
    backgroundColor: '#fcfcfc',
    margin: {
      bottom: 50
    }
  },
  tooltip: true,
  tooltipOpts: {
    content: function(label, x, y) {
      var objDate = new Date(x);
      var yearValue = objDate.getFullYear();
      objDate = new Date(x).setMonth(objDate.getMonth() - 1);
      var monthName = new Date(objDate).toLocaleString("en-us", {
        month: "short"
      });
      return monthName + " " + yearValue + ' : ' + y;
    }
  },
  xaxis: {
   mode: "time",
min: (new Date(startDate)).getTime(),
max: (new Date(endDate)).getTime(),
timeBase: "milliseconds",
tickLength: 5,
gridLines: false,
autoScale: "none",

  },
  yaxis: {
    position: 'left',
    tickColor: '#eee',
    tickDecimals: 0,axisLabel: "Amount",
  },
  shadowSize: 0
};

var flotLineOption = {
  series: {
    lines: {
      show: true,
      fill: 0.01,
      lineWidth: 2,
    },
    points: {
      show: true,
      radius: 4
    }
  },
  colors: ['#5b7fff'], 
  grid: {
    borderColor: '#eee',
    borderWidth: 1,
    hoverable: true,
    backgroundColor: '#fcfcfc',
    margin: {
      bottom: 50
    }
  },
  tooltip: true,
  tooltipOpts: {
    content: function(label, x, y) {
      var objDate = new Date(x);
      var yearValue = objDate.getFullYear();
      objDate = new Date(x).setMonth(objDate.getMonth() - 1);
      var monthName = new Date(objDate).toLocaleString("en-us", {
        month: "short"
      });
      return monthName + " " + yearValue + ' : ' + y;
    }
  },
  xaxis: {
   mode: "time",
min: (new Date(startDate)).getTime(),
max: (new Date(endDate)).getTime(),
timeBase: "milliseconds",
tickLength: 5,
gridLines: false,



  },
  yaxis: {
    position: 'left',
    tickColor: '#eee',
    tickDecimals: 0,
    axisLabel: "Amount",
  },
  shadowSize: 0
};



if(reset ==1){
var plot = $.plot("#placeholder_sale", [d], flotLineOption_set);
}else {
var plot = $.plot("#placeholder_sale", [d], flotLineOption);
}

  // var plot = $.plot($("#placeholder_sale"), [d],
  //   flotLineOption
  // );
  window.setInterval(function () {
			plot.setData([
				{ data: d, label: "SAR"}
			]);
		}, 2000);

		$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");

		$("#placeholder_sale").bind("plothover", function (event, pos, item) {

			if (!pos.x || !pos.y) {
				return;
			}

			if ($("#enablePosition:checked").length > 0) {
				var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
				$("#hoverdata").text(str);
			}

			// if ($("#enableTooltip:checked").length > 0) {
				if (item) {
					var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);

					$("#tooltip").html(item.series.label + y)
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);
				} else {
					$("#tooltip").hide();
				}
			// }
		});

		$("#placeholder_sale").bind("plothovercleanup", function (event, pos, item) {
				$("#tooltip").hide();
		});

		$("#placeholder_sale").bind("plotclick", function (event, pos, item) {
			if (item) {
				$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
				plot.highlight(item.series, item.datapoint);
			}
		});



// var overview = $.plot("#overview_sale", [d], {
// series: {
// lines: {
// show: true,
// lineWidth: 2,

// },
// shadowSize: 0
// },
// colors: ['#5b7fff'], 
// xaxis: {
// ticks: [],
// mode: "time"
// },
// yaxis: {
// ticks: [],
// min: 0,
// autoScaleMargin: 0.1
// },
// selection: {
// mode: "x"
// }
// });

// now connect the two

$("#placeholder_sale").bind("plotselected", function (event, ranges) {

// do the zooming
$.each(plot.getXAxes(), function(_, axis) {
var opts = axis.options;
opts.min = ranges.xaxis.from;
opts.max = ranges.xaxis.to;
});
plot.setupGrid();
plot.draw();
plot.clearSelection();

// don't fire event on the overview to prevent eternal loop

// overview.setSelection(ranges, true);
});

// $("#overview_sale").bind("plotselected", function (event, ranges) {
// plot.setSelection(ranges);
// });

// Add the Flot version string to the footer

$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
}
  });
            
  </script>
@endsection