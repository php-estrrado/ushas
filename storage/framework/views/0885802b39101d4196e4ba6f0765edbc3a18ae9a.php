
<style> .no-border{ border: none } .invoice i.fa{ color: #ff0000; font-size: 10px; } </style>
<?php 
$n_img = 1; $currency = getCurrency()->name;
?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0"><?php echo e($title); ?></h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Currency</a></li>
            <li class="breadcrumb-item active list-li" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
            <!-- <li class="breadcrumb-item view-li no-disp"><a id="bc_list" href="">Order List</a></li> -->
            <li class="breadcrumb-item active view-li no-disp" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
        </ol>
    </div>
    <div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
							    <label class="form-label mr-2" for="filterSel">Filter </label>
							    						<select class="form-control mr-2" id="filterSel">
                                                        <option value="">All Status</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                        </select>
								<div class="btn btn-list">
									<!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
									<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
									
									<a href="<?php echo e(route('admin.newcurrency')); ?>"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
									
								</div>
							</div>
    
</div>


<div id="content_list"><?php echo $__env->make('admin.currency.list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
<div id="content_detail"></div>
<?php $__env->startSection('js'); ?> 
 <script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/currencies-datatable.js')); ?>"></script>
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
    </script>

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
        $('#adminForm #can_submit').val(0); 
        <?php if(Session::has('success')): ?> toastr.success("<?php echo e(Session::get('success')); ?>"); 
        <?php elseif(Session::has('error')): ?> toastr.error("<?php echo e(Session::get('error')); ?>"); toastr.warning('Hi! I am warning message.'); <?php endif; ?>
       
        
        
  //CHAT
        $('body').on('click','.viewBtn',function(){
            
            var id      =   this.id.replace('viewBtn-','');  
            var sellerId = 0;
            
            $.ajax({
                type: "GET",
               url: '<?php echo e(url("admin/chat/chat-message")); ?>/'+id+"/chat",
                success: function (data) {
                    $('#chatmodel').modal('show');
                    $('.chat_content').html(data);
                 
                } 
            }); return false;
        });



    });

    

   
  
    
    
    function readURL(input) { 
        if (input.files && input.files[0]) { 
            var reader = new FileReader();
            reader.onload = function (e) { $('#adminForm #'+input.id+'Img').attr('src', e.target.result); $('#adminForm #'+input.id+'Img').show(); }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php /**PATH /home/qaushas/public_html/resources/views/admin/currency/header.blade.php ENDPATH**/ ?>