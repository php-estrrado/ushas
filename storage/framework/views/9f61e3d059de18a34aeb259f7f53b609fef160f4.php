
<?php $__env->startSection('css'); ?>
		<!-- INTERNAl Data table css -->
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>"  rel="stylesheet">
		<link href="<?php echo e(URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/css/combo-tree.css')); ?>" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css">
		<link href="<?php echo e(URL::asset('admin/assets/css/daterangepicker.css')); ?>" rel="stylesheet" />
		<link href="<?php echo e(URL::asset('admin/assets/css/jquery-ui.css')); ?>" rel="stylesheet" />
			

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
						<!--Page header-->

<style type="text/css">
	.filter-radio {
 display: table-cell;
    vertical-align: middle
	}
</style>
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"><?php echo e($title); ?></h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Ecom Benefits</a></li>
									
									<li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
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
						
									<a href="<?php echo e(url('admin/coupons/create/')); ?>"  class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
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
							<div class="col-3">

							<div class="page-filters">

							<div class="price_filter">
							<div id="mySlider"></div>
							<p>
							<label for="price" style="font-family:Verdana;">Price Range:</label>
							<span type="text" id="price" style="border:0; color:#fff; font-weight:bold;"></span>
							</p>
							<input type="hidden" id="startprice" value="<?php echo $minprice; ?>">
							<input type="hidden" id="endprice" value="<?php echo $maxprice; ?>">
							</div>
							</div>

							</div>
							<div class="col-5">
							<div class="page-filters" style="display: inline-flex;">

							<div class="price_filter radio" style="margin-right: 20px;">

							<input checked="checked" type="radio"  class="filter-radio"  name="typesel" id="amount" value="1"/>
							<label for="amount">Amount</label>
							<input type="radio"  class="filter-radio" name="typesel"  id="percentage" value="2"/>
							<label for="percentage">Percentage</label>
							</div>

							<a  id="viewfilter"  class="mr-2 btn btn-info btn-sm cursor"><i class="fa fa-check-circle"></i> Apply</a>

							</div>								
							</div>

							</div>


                        <!--End Page header-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

								<!--<?php if(Session::has('message')): ?>-->

								<!--<div class="alert alert-<?php echo e(session('message')['type']); ?>" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e(session('message')['text']); ?></div>-->
								<!--<?php endif; ?>-->
								<!--<?php if($errors->any()): ?>-->
								<!--<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>-->

								<!--<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><?php echo e($error); ?></div>-->
								<!--<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
								<!--<?php endif; ?>-->
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
																	<th class="border-bottom-0 w-15">Created On</th>
																	<th class="border-bottom-0 w-30 notexport">Status</th>
																	<th class="border-bottom-0 w-30 hide_column" style"display:none;">Status</th>
																	<th class="border-bottom-0 w-30 notexport">Log</th>						
																	<th class="border-bottom-0 w-10 notexport">Actions</th>
																</tr>
															</thead>

															<tbody>

																<?php if($coupons && count($coupons) > 0): ?>
                    											<?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<tr>
																	<td class="align-middle select-checkbox" id="moduleid" data-value="<?php echo e($row['id']); ?>">
																		<label class="custom-control custom-checkbox">
																			
																			<!--<?php echo e($loop->iteration); ?>-->
																		</label>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<?php	$cpn_title = Str::of($row['cpn_title'])->limit(20); ?>
																	<h6 class=" font-weight-bold"><?php echo e($cpn_title); ?> </h6>
																				
																			
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p><?php echo e($row['ofr_code']); ?></p>
																	</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p><?php echo e($row['ofr_value']); ?></p>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p><?php echo e(ucfirst($row['ofr_type'])); ?></p>
																		</div>
																	</td>
																	<td class="text-nowrap align-middle">
														<?php 
													$valid_till = "";
													if($row['validity_type'] == "days") 
													{
														$days = $row['valid_days'];
												$valid_till = date('Y-m-d', strtotime($row['created_at'] ."+$days days"));
													}
													
													else {
														$valid_till = $row['valid_to'];
													}
													
													?>		
																		<p><?php echo e($valid_till); ?></p>
																	</td>
																	<td class="text-nowrap align-middle"><p><?php echo e(date('d M Y',strtotime($row['created_at']))); ?></p></td>
																	<td class="text-nowrap align-middle"  data-search="<?php if($row['is_active'] ==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?>">
																		
																	<div class="switch">
																	<input class="switch-input status-btn ser_status" data-selid="<?php echo e($row['id']); ?>"  id="status-<?php echo e($row['id']); ?>"  type="checkbox"  <?php if($row['is_active'] ==1): ?> <?php echo e("checked"); ?> <?php endif; ?> >
																	<label class="switch-paddle" for="status-<?php echo e($row['id']); ?>">
																	<span class="switch-active" aria-hidden="true">Active</span>
																	<span class="switch-inactive" aria-hidden="true">Inactive</span>
																	</label>
																	</div>                    
                  
																	</td>
																	<td style"display:none;"><?php if($row['is_active'] ==1): ?>
																	<?php echo e("Active"); ?>

																	<?php else: ?>
																	<?php echo e("Inactive"); ?>

																	<?php endif; ?>
																	</td>
																	
																	
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			
																			<a href="<?php echo e(url('admin/coupons/log/')); ?>/<?php echo e($row['id']); ?>"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> View</a>
																			
																		</div>
																	</td>
																	<td class="align-middle">
																		<div class="btn-group align-top">
																			<?php if(checkPermission('admin/coupons','edit') == true): ?>
																			<a href="<?php echo e(url('admin/coupons/edit/')); ?>/<?php echo e($row['id']); ?>"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
																			<?php endif; ?>
																			<?php if(checkPermission('admin/coupons','delete') == true): ?>
																			<button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn(<?php echo e($row['id']); ?>);" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
																			<?php endif; ?>
																		</div>
																	</td>
																</tr>
																     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
																
																
																
																
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


</style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
		<!-- INTERNAl Data tables -->

		<script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/coupons-datatable.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/moment.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/daterangepicker.js')); ?>"></script>
	<!-- INTERNAL Popover js -->
		<script src="<?php echo e(URL::asset('admin/assets/js/popover.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/comboTreePlugin.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/jquery-ui.js')); ?>"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/plugins/sweet-alert/sweetalert.min.js')); ?>"></script>
		<script src="<?php echo e(URL::asset('admin/assets/js/sweet-alert.js')); ?>"></script>
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
             url: '<?php echo e(url("/admin/coupons/delete")); ?>',
            data: { "_token": "<?php echo e(csrf_token()); ?>", id: cpnid},
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
        url: '<?php echo e(url("/admin/coupons/status")); ?>',
        data: { "_token": "<?php echo e(csrf_token()); ?>", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
           
                toastr.success("Coupon activated successfully.");
             
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
                toastr.success("Coupon deactivated successfully."); 

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
            <?php if(Session::has('message')): ?>
            <?php if(session('message')['type'] =="success"): ?>
            
            toastr.success("<?php echo e(session('message')['text']); ?>"); 
            <?php else: ?>
            toastr.error("<?php echo e(session('message')['text']); ?>"); 
            <?php endif; ?>
            <?php endif; ?>
            
            <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            toastr.error("<?php echo e($error); ?>"); 
            
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
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
            $('#couponslist tbody').append($('#loader').html()); $('#couponslist').addClass('blur'); 
			var startdate = $("#startdate").val();
			var enddate = $("#enddate").val();
			var startprice = $("#startprice").val();
			var endprice = $("#endprice").val();
			var typesel = $('input[name="typesel"]:checked').val();



			$.ajax({
			type: "POST",
			url: '<?php echo e(url("/admin/coupons/filter")); ?>',
			 data: { "_token": "<?php echo e(csrf_token()); ?>", startdate: startdate,enddate:enddate,startprice:startprice,endprice:endprice,typesel:typesel},
			success: function (data) {
			// console.log(data);
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
				// console.log(htmlFiltered);
				table.clear().draw();
				$("#hiddentable tr").each(function(index, tr) { 
				console.log(index);
				console.log(tr);
				table.row.add($(tr)).columns.adjust().draw();
				});

				//   table.rows.add(data); // Add new data
				//   table.columns.adjust().draw();
			}
			$('#couponslist').removeClass('blur');
			 $('#couponslist tbody').remove($('#loader').html());
			
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/benefits/coupons/list.blade.php ENDPATH**/ ?>