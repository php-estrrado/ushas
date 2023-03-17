
<?php $__env->startSection('title', 'Stock List'); ?>
<?php $__env->startSection('page-header'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div id="list_form"  style="display:none;"> <?php echo $__env->make('admin.stock.list.content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
<div id="content_detail"><?php echo $__env->make('admin.stock.list.content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
<div id="dtl_form">  </div>
<div id="modal_stock" class="d-none"><?php echo $__env->make('admin.stock.stock_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
<div id="modal_price" class="d-none"><?php echo $__env->make('admin.stock.price_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
<?php $__env->startSection('js'); ?> 

    <script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/stock-datatable.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/stock_log-datatable.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/stock_price-datatable.js')); ?>"></script>
    
    <script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
        
        
        
        
        $('#addData').on('click',function(){
            $.ajax({
                type: "GET",
                url: '<?php echo e(url("admin/product-stock/0")); ?>',
                success: function (data) {
                    $('#dtl_form').html(data); $('#dtl_form').fadeIn(700); $('#list_form').hide(); 
                } 
            }); return false;
        });
        
        $('body').on('click', '#cancel_btn', function(){ $('#dtl_form').hide(); $('#list_form').fadeIn(700); });
        $('body').on('click', '.bc_list', function(){ $('#content_detail').hide(); $('#list_form').fadeIn(700); return false; });
        
        $('body').on('click','.editForm',function(){ 
            var id          =   this.id.replace('editForm-','');  $('#adminForm .error').text(''); 
            var prdId       =   $(this).data('product');
            var sellerId    =   $(this).data('seller');  // modal_cnt
            $('#basicModal').modal('show'); $('#basicModal .modal-content').html($('#modal_stock').html());
            $('#adminForm #prd_id').val(prdId);     $('#adminForm #seller_id').val(sellerId);
            $('#adminForm #name').val($('#prd_name_'+id).val());     $('#adminForm #seller').val($('#slr_name_'+id).val());
            $('#adminForm #rate').val($('#prd_price_'+id).val());    $('#adminForm #amount').val($('#prd_price_'+id).val());  
            return false;
//            $.ajax({
//                type: "POST",
//                data: {prdId: prdId,sellerId: sellerId},
//                url: '<?php echo e(url("admin/seller/product-stock/add")); ?>',
//                success: function (data) {
//                    $('#dtl_form').html(data); $('#dtl_form').fadeIn(700); $('#list_form').hide();
//                } 
//            });
        });
        
        $('body').on('click','.addPice',function(){ 
            var id          =   this.id.replace('addPice-','');  $('#priceForm .error').text(''); 
            var prdId       =   $(this).data('product');
            var sellerId    =   $(this).data('seller');  
            $('#basicModal').modal('show'); $('#basicModal .modal-content').html($('#modal_price').html());
            $('#priceForm #prd_id').val(prdId);     $('#priceForm #seller_id').val(sellerId);
            $('#priceForm #name').val($('#prd_name_'+id).val());     $('#priceForm #seller').val($('#slr_name_'+id).val());
            $('#priceForm #price').val($('#prd_price_'+id).val());     $('#priceForm #sale_price').val($('#sale_price_'+id).val());
            $('#priceForm #sale_start_date').val($('#sale_start_date_'+id).val()); $('#priceForm #sale_end_date').val($('#sale_end_date_'+id).val());
            return false;
        });
        
        $('body').on('change', '#priceForm #sale_price',function(){ 
            if(this.value > 0){  
                $('body #priceForm #sale_start_date').prop('required',true);
                $('body #priceForm #sale_end_date').prop('required',true); 
            }else{ $('body #priceForm #sale_start_date').prop('required',false); $('body #priceForm #sale_end_date').prop('required',false); }
        });
        
        $('body').on('click','.viewForm',function(){ 
            var id          =   this.id.replace('viewForm-','');
            $.ajax({
                type: "POST",
                data: {prdId: id},
                url: '<?php echo e(url("/admin/product/stock-log")); ?>',
                success: function (data) {
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#list_form').hide();
                } 
            });
            
        });
        $('body').on('submit','#adminForm',function(e){ 
            $('#adminForm .error').html(''); 
//            if($('#adminForm #can_submit').val() > 0){ return true; }
//            setTimeout(function(){ 
//                if($('#adminForm #qty').val() == '' || $('#adminForm #qty').val() == 0 ){ $('#adminForm #qty_error').text('Please enter amount'); return false; }
//                else{  $('#adminForm #can_submit').val(1); $('#adminForm').submit(); } return false;
//            }, 300); return false;
        });
        
        $("body").on("change", ".status-btn", function () {
            var id          =   this.id.replace('status-','');
            var bId         =   this.id;
            var sts         =   $(this).prop("checked");
            var url         =   '<?php echo e(url("admin/attribute/updateStatus")); ?>';
            var smsg        =   'Attrinute activated successfully!';
            if (sts == true){ var status = 1; }else if (sts == false){var status = 0; smsg = 'Attrinute deactivated successfully!'; }
            updateStatus(id,bId,status,url,'dtrow-','is_active',smsg);
        });
        $('body').on('click','.delBtn',function(){ //alert('sss');
            var id          =   this.id.replace('delBtn-',''); 
            var status      =   1;
            var url         =   '<?php echo e(url("admin/attribute/updateStatus")); ?>';
            var smsg        =   'Attrinute deleted successfully!';
            
            swal({
                title: "Delete Confirmation",
                text: "Are you sure you want to delete this Attribute?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) {
                    updateStatus(id,'',status,url,'admin','is_deleted',smsg);
                }
            });
        });
//        $('body').on('click','.delUser',function(){  
//            var id          =   $(this).data('id'); 
//            var bId         =   this.id;
//            var status      =   0;
//            var url         =   '<?php echo e(url("admin/attribute/updateStatus")); ?>';
//            var smsg        =   'Attrinute deleted successfully!';
//            updateStatus(id,'',status,url,'admin','is_deleted',smsg);
//        });
        
        var row      =   1; 
        $('body').on('change','#adminForm #type',function(){ 
            $('#adminForm #data_type').val(''); 
            if(this.value == 'text' || this.value == 'textarea'){ 
                $('#adminForm #data_type_div').show(); $('#adminForm #filter_div').hide(); $('#adminForm #filter').val(0); $('#adminForm #config_div').hide(); $('#adminForm #configur').val(0);
                $('#adminForm .panel-tabs #nav_tab_3').hide(); 
            }else{ 
                $('#adminForm #data_type_div').hide(); $('#adminForm #filter_div').show(); $('#adminForm #filter').val(0); $('#adminForm #config_div').show(); $('#adminForm #configur').val(0);
                $('#adminForm .panel-tabs #nav_tab_3').show(); 
            }
        });
        
        $('body').on('change','#adminForm #qty',function(){ 
            var qty      =   parseInt(this.value); 
            var rate    =   parseFloat($('#adminForm #rate').val()); // alert(qty+' -- '+rate); 
            $('#adminForm #amount').val((rate*qty));
        });
        <?php if(Session::has('success')): ?> toastr.success("<?php echo e(Session::get('success')); ?>"); <?php endif; ?>
        <?php if(Session::has('error')): ?> toastr.error("<?php echo e(Session::get('error')); ?>"); <?php endif; ?>
        
        $(document).ready(function(){  
            $('body').on('change','.number',function(){  
                var id  =   this.id; var val = this.value.replace(/[^0-9\.]/g,''); $(this).val(val); if($(this).val() == ''){ $(this).val(0); }
            });
            $('body').on('change','.numberonly',function(){  var dVal = $(this).data('val');
                var id  =   this.id; var val = this.value.replace(/[^0-9]/g,''); $(this).val(val); if($(this).val() == ''){ $(this).val(0); }
            });
        });
    });
    
//    function confirmDelete(id,row){
//        var cnf      =   confirm("Are you sure?!");
//        if(cnf){ 
//            $.ajax({
//                type: "POST",
//                url: "<?php echo e(url('attribute/value/delete')); ?>",
//                data: { "_token": "<?php echo e(csrf_token()); ?>", id: id},
//                success: function (data) { deleteValue(row); }
//            });
//        }else{ return false; }
//    }
    function deleteValue(id){ $('#adminForm #attr-val-row-'+id).remove();}
    
    function updateStatus(id,rowId,status,url,row,field,smsg){ // alert(id+' -- '+rowId+' -- '+status+' -- '+url+' -- '+row+' -- '+field+' -- '+smsg);
        $.ajax({
            type: "POST",
            url: url,
            data: {id: id, value: status,field: field, field, page: row},
            success: function (data) { // alert(data.type);
                if(field == 'is_deleted'){
                    $('#list_form').html(data); toastr.success(smsg);
                }else{ 
                    if (data.type == 'warning' || data.type == 'error'){ toastr.error(smsg); }else{ toastr.success(smsg); }
                } 
            }
        }); return false;
    }
    function submitForm(postValues){
        $.ajax({
            type: "POST",
            url: '<?php echo e(url("admin/attribute/save")); ?>',
            data: postValues,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) { 
              $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save');
              if($('#adminForm #id').val() > 0){ var msg = 'Atribute updated successfully!'; }else{ msg = 'Atribute added successfully!'; }
              $('#dtl_form').hide(); $('#list_form').fadeIn(700); $('#list_form').html(data); toastr.success(msg);
  return false;
            //  setTimeout(function(){ $('#allert_success').fadeOut(); }, 3000);
            } 
        }); return false;
    }
    function getStateDropdown(cId,selected){ 
        $.ajax({
            type: "POST",
            url: '<?php echo e(url("admin/getDropdown/states/")); ?>',
            data: {field: 'country_id', value:cId, label:'name',selected: selected, placeholder:'Select State','_token': '<?php echo e(csrf_token()); ?>'},
            success: function (data) {
                $('#adminForm #state').html(data);
            }
        });
    }
</script>


<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
       
        
        
        
        $('body').on('change','#seller',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '<?php echo e(url("admin/product-stocks-filter")); ?>',
                data: {seller: this.value, start_date: $('#start_date').val(),end_date: $('#end_date').val(),product: $('#product').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_detail').html(data); 
                } 
            });
        });


       
        
        
        
        $('body').on('click','.plus-minus-toggle', function() {
            $(this).toggleClass('collapsed');
            $('#filtersec').toggle('slow');
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/admin/stock/list.blade.php ENDPATH**/ ?>