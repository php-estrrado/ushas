
<div id="content_list"><?php echo $__env->make('admin.customer.request.content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
<div id="content_detail"></div>
<?php $__env->startSection('js'); ?> 
 <script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/customer-request-datatable.js')); ?>"></script>
<script src="<?php echo e(URL::asset('admin/assets/js/jquery.validate.min.js')); ?>"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
        setTimeout(function(){ 
            $('body #seller .switch').each(function(){
          var srch = $(this).data('search');
            $(this).closest("td").attr("data-search",srch);
        }); }, 1000);
        $('#adminForm #can_submit').val(0); 
        <?php if(Session::has('success')): ?> toastr.success("<?php echo e(Session::get('success')); ?>"); 
        <?php elseif(Session::has('error')): ?> toastr.error("<?php echo e(Session::get('error')); ?>");  <?php endif; ?>
        $("body").on('change','#adminForm #logo',function(){ readURL(this); });
        $("body").on('change','#adminForm #banner',function(){ readURL(this); });
        $('body').on('click','#cancel_btn',function(){ $('#content_list').fadeIn(700); $('#content_detail').hide();  });
        $('body').on('click','#bc_list',function(){ $('#content_list').fadeIn(700); $('#content_detail').hide(); return false;  });
        $('body').on('click','#addNew',function(){ 
            $.ajax({
                type: "GET",
                data: {active: $('#active_filter').val(), viewType: 'ajax' },
                url: '<?php echo e(url("admin/seller/0")); ?>',
                success: function (data) {
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                  //  $('#content_detail #country_id').trigger("chosen:updated");
                } 
            }); return false;
        });
        
         });
    $('body').on('click','.backtoview',function(){ 
           // alert('hai');
            
                    $('#content_detail').hide(); 
                    $('#content_list').show(); 
                
            
        });


        $('body').on('click','.viewDtl',function(){ 
           // alert('hai');
            var id      =   this.id.replace('viewDtl-','');
            $.ajax({
                type: "GET",
                url: '<?php echo e(url("admin/customer/request/view")); ?>/'+id,
                success: function (data) {
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                } 
            });
        });
        
        $('body').on('click','.rejectusr',function(){  
            var id          =   this.id.replace('rejectusr-',''); 
            var status      =   2;
            var url         =   '<?php echo e(url("admin/customer/request/updateStatus")); ?>';
            var smsg        =   'Rejected customer successfully!';
            swal({
                title: "Reject Confirmation",
                text: "Are you sure you want to reject this customer?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) { 
                    updateStatus(id,'',status,url,'is_approved',smsg);
                }
            });
        });

        $('body').on('click','.approveuser',function(){  
            var id          =   this.id.replace('approveuser-',''); 
            var status      =   1;
            var url         =   '<?php echo e(url("admin/customer/request/updateStatus")); ?>';
            var smsg        =   'Approved customer successfully!';
            swal({
                title: "Approval Confirmation",
                text: "Are you sure you want to approve this customer?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) { 
                    updateStatus(id,'',status,url,'is_approved',smsg);
                }
            });
        });

        function updateStatus(id,rowId,status,url,field,smsg){
        $.ajax({
            type: "POST",
            url: url,
            data: { "_token": "<?php echo e(csrf_token()); ?>", id: id, value: status,field: field, field},
            success: function (data) {
             toastr.success(smsg);
              location.reload();  
            }
        });
    }
       
</script>
<?php $__env->stopSection(); ?>
<?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/customer/request/list.blade.php ENDPATH**/ ?>