
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
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Product Management</a></li>
									
									
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
						
									<a href="<?php echo e(url('admin/shocking-sales/create/')); ?>"  class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
								</div>
							</div>



						</div>
						


                        <!--End Page header-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
						<!-- Row -->
						<div class="row flex-lg-nowrap">
							<div class="col-12">

								
								<div class="row flex-lg-nowrap">
									<div class="col-12 mb-3">
										<div class="e-panel card">
											<div class="card-body">
												<div class="e-table">
													<div class="table-responsive table-lg mt-3">
														<table class="table table-bordered border-top text-nowrap shockingsales" id="shockingsales">
															<thead>
																<tr>

																	<th class="align-top border-bottom-0 wd-5 notexport">Select</th>
																	<th class="border-bottom-0 w-10">Caption</th>
																	
																	<th class="border-bottom-0 w-5">Start Date</th>
																	<th class="border-bottom-0 w-5">End Date</th>
																	
																	<th class="border-bottom-0 w-5">Status</th>
																						
																   <th class="border-bottom-0 w-10 notexport">Actions</th>
																</tr>
															</thead>

															<tbody>

																<?php if($products && count($products) > 0): ?>
                    											<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<tr>
																	<td class="align-middle select-checkbox" id="moduleid" data-value="<?php echo e($row['id']); ?>">
																		<label class="custom-control custom-checkbox">
																			
																			<!--<?php echo e($loop->iteration); ?>-->
																		</label>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																		<p><?php echo e($row['title']); ?></p>
																	</div>
																	</td>

																	<td class="align-middle" >
																		<div class="d-flex">
																			<p><?php echo e(date('Y-m-d H:i A', strtotime($row['start_time']))); ?></p>
																		</div>
																	</td>
																	<td class="align-middle" >
																		<div class="d-flex">
																			<p><?php echo e(date('Y-m-d H:i A', strtotime($row['end_time']))); ?></p>
																		</div>
																	</td>
																	
																	<td class="text-nowrap align-middle"  data-search="<?php if($row['is_active'] ==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?>">
																		
																	<div class="switch">
																	<input class="switch-input status-btn ser_status" data-selid="<?php echo e($row['id']); ?>"  id="status-<?php echo e($row['id']); ?>"  type="checkbox"  <?php if($row['is_active'] ==1): ?> <?php echo e("checked"); ?> <?php endif; ?> >
																	<label class="switch-paddle" for="status-<?php echo e($row['id']); ?>">
																	<span class="switch-active" aria-hidden="true">Active</span>
																	<span class="switch-inactive" aria-hidden="true">Inactive</span>
																	</label>
																	</div>                    
                  
																	</td>
																	
																	
																	 <td class="align-middle">
																		<div class="btn-group align-top">
																			<?php if(checkPermission('/admin/shocking-sales','edit') == true): ?>
																			<a href="<?php echo e(url('admin/shocking-sales/edit/')); ?>/<?php echo e($row['id']); ?>"   class="mr-2 btn btn-info btn-sm editmodule"><i class="fe fe-edit mr-1"></i> Edit</a>
																			<?php endif; ?>
																			<?php if(checkPermission('/admin/shocking-sales','delete') == true): ?>
																			<button  class="btn btn-secondary btn-sm deletemodule" onclick="deletecpn(<?php echo e($row['id']); ?>,<?php echo e($row['deletable']); ?>);" type="button"><i class="fe fe-trash-2  mr-1"></i>Delete</button>
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

		<script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/shockingsales-datatable.js')); ?>"></script>
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


function deletecpn(cpnid,$deletable){
$('body').removeClass('timer-alert');
if($deletable == 1) {

		swal({
		title: "Delete Confirmation",
		text: "Are you sure you want to delete this Shocking Sale?",
		// type: "input",
		showCancelButton: true,
		closeOnConfirm: true,
		confirmButtonText: 'Yes'
		},function(inputValue){
		if (inputValue == true) {
		$.ajax({
		type: "POST",
		url: '<?php echo e(url("/admin/shocking-sales/delete")); ?>',
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

}else {

	swal('Unable to delete!', 'Shocking sale is not expired yet.', 'error');

}
		
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
        url: '<?php echo e(url("/admin/shocking-sales/status")); ?>',
        data: { "_token": "<?php echo e(csrf_token()); ?>", id: selid,status:sestatus},
        success: function (data) {
        // alert(data);
        if(data ==1) {
        if(sestatus ==1) {
        	jQuery('#status-'+selid).closest("td").attr("data-search","Active");
           
                toastr.success("Shocking Sale activated successfully.");
             
            }else {
            	jQuery('#status-'+selid).closest("td").attr("data-search","Inactive");
                toastr.success("Shocking Sale deactivated successfully."); 

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


      $("#viewfilter").click(function(){
 $('#auctionslist tbody').append($('#loader').html()); $('#auctionslist').addClass('blur'); 
			var startdate = $("#startdate").val();
			var enddate = $("#enddate").val();




			$.ajax({
			type: "POST",
			url: '<?php echo e(url("/auctions/filter")); ?>',
			 data: { "_token": "<?php echo e(csrf_token()); ?>", startdate: startdate,enddate:enddate},
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
			$('#auctionslist').removeClass('blur');
			 $('#auctionslist tbody').remove($('#loader').html()); 

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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/shocking_sale/list.blade.php ENDPATH**/ ?>