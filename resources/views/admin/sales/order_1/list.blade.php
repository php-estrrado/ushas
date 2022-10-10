@php 
$n_img = 1; $currency = getCurrency()->name; 
$pStatusList    =   ['pending'=>'Pending','processing'=>'Processing','success'=>'Success','cancelled'=>'Cancelled'];
@endphp
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Sales Management</a></li>
            <li class="breadcrumb-item active list-li" aria-current="page"><a href="#">{{$title}}</a></li>
            <li class="breadcrumb-item view-li no-disp"><a id="bc_list" href="">Order List</a></li>
            <li class="breadcrumb-item active view-li no-disp" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
    <div class="page-rightheader d-none" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
        <label class="form-label mr-2" for="filterSel">Filter </label>
        {{Form::select('active',['Electronics'=>'Electronics','Grocery'=>'Grocery'],'',['id'=>'status_filter','class'=>'form-control mr-4','placeholder'=>'All Status'])}}
<!--        <div class="btn btn-list">
            <a id="addNew"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
        </div>-->
    </div>
</div>

<div id="filters">
    <div class="row" id="filterrow"><div class="plus-minus-toggle collapsed"><p>Filters</p></div></div>
    <div class="row no-disp mb-4 " id="filtersec">
        <div class="col-2 fl">
            <div class="page-filters">
                {{Form::label('start_date','Date From',['class'=>'text-white'])}}
                {{Form::date('start_date','',['id'=>'start_date','class'=>'form-control'])}}
            </div>
        </div>
        <div class="col-2 fl">
            <div class="page-filters">
                {{Form::label('end_date','Date To',['class'=>'text-white'])}}
                {{Form::date('end_date','',['id'=>'end_date','class'=>'form-control'])}}
            </div>
        </div>
        <div class="col-2 fl">
            <div class="page-filters">
                {{Form::label('seller','Sellers',['class'=>'text-white'])}}
                {{Form::select('seller',$sellers,$seller,['id'=>'seller','class'=>'form-control','placeholder'=>'All Sellers'])}}
            </div>
        </div>
        <div class="col-2 fl">
            <div class="page-filters">
                {{Form::label('p_status','Payment Status',['class'=>'text-white'])}}
                {{Form::select('p_status',$pStatusList,$p_status,['id'=>'p_status','class'=>'form-control','placeholder'=>'All Status'])}}
            </div>
        </div>
        <div class="col-2 fl">
            <div class="page-filters">
                {{Form::label('o_status','Order Status',['class'=>'text-white'])}}
                {{Form::select('o_status',$orderStatusList,$o_status,['id'=>'o_status','class'=>'form-control','placeholder'=>'All Status'])}}
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>
<div id="content_list">@include('admin.sales.order.list.content')</div>
<div id="content_detail"></div>
@section('js') 
 <script src="{{asset('admin/assets/js/datatable/tables/order-datatable.js')}}"></script>

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    $(document).ready(function(){ 
        $('#adminForm #can_submit').val(0); 
        @if(Session::has('success')) toastr.success("{{ Session::get('success')}}"); 
        @elseif(Session::has('error')) toastr.error("{{ Session::get('error')}}");  @endif
        $('body').on('click','#cancel_btn',function(){ 
            $('#content_list').fadeIn(700); $('#content_detail').hide(); $('.view-li').hide(); $('.list-li').fadeIn(500);  $('#filters').show();
        });
        $('body').on('click','#bc_list',function(){ 
            $('#content_list').fadeIn(700); $('#content_detail').hide(); $('.view-li').hide(); $('.list-li').fadeIn(500);  $('#filters').show(); return false;  
        });
        $('body').on('click','.bc_list2',function(){ $('body #dtlBtn-'+$(this).data('val')).trigger('click'); });
        
        $('body').on('click','.viewDtl',function(){ 
            var id      =   this.id.replace('dtlBtn-','');
            $.ajax({
                type: "GET",
               url: '{{url("admin/sales/order")}}/'+id+"/request",
                success: function (data) {
                    $('.list-li').hide(); $('.view-li').fadeIn(700);  $('#filters').hide();
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                } 
            });
        });
        
        $('body').on('click','.editBtn',function(){
            var id      =   this.id.replace('editBtn-','');  
            $.ajax({
                type: "GET",
               url: '{{url("admin/sales/order")}}/'+id+"/request",
                success: function (data) {
                    $('.list-li').hide(); $('.view-li').fadeIn(700); $('#filters').hide();
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                } 
            }); return false;
        });
        $('body').on('click','#content_detail #add_btn',function(){
            var id      =   $(this).data('val');  
            var sId     =   '{{auth()->user()->id}}';
            $.ajax({
                type: "GET",
                url: '{{url("/admin/product")}}/'+id+'/'+sId+'/add',
                data: {active: $('#active_filter').val(), viewType: 'ajax' },
                success: function (data) {
                    $('#content_detail').html(data); $('#content_detail').fadeIn(700); $('#content_list').hide(); 
                  //  $('#content_detail #country_id').trigger("chosen:updated");
                } 
            }); return false;
        });
        
        $('body').on('submit','#adminForm',function(e){ 
            $('body #adminForm .error').html('');
            if($('#adminForm #option2').prop('checked') == true && $('#adminForm #admin_prd_id').val() == ''){
                $('#adminForm #admin_prd_id').closest('div').find('.error').html('Select Admin Product'); $('#adminForm #admin_prd_id').focus(); return false;
            }else{
                if($('#adminForm #can_submit').val() > 0){ return true; }
                else{ 
                    e.preventDefault();    
                    var formData = new FormData(this); 
                    $('#adminForm #save_btn').attr('disabled',true); $('#adminForm #save_btn').text('Validating...'); 
                    $.ajax({
                        type: "POST",
                        url: '{{url("/admin/product/validate")}}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if(data == 'success'){  var atrRes;
                                $('#adminForm .attr .required').each(function(){ 
                                    if(this.value == ''){ $(this).closest('div').find('.error').text('This field is required'); atrRes = false; }
                                    else{ $(this).closest('div').find('.error').text(''); }
                                });
                                if(atrRes  == false){ $('#adminForm #nav_tab_4').trigger('click');
                                    $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save'); return false;
                                }else{ 
                                    $('#adminForm #save_btn').text('Saving...'); 
                                 //    submitForm(formData); return false;
                                     $('#adminForm #can_submit').val(1); $('#adminForm').submit();
                                 } 
                            }else{
                                var errKey = ''; var n = 0;
                                $.each(data, function(key,value) { if(n == 0){ errKey = key; n++; }
                                    $('#adminForm #'+key).closest('div').find('.error').html(value);
                                    if(key == 'error' && value == 'prd'){ $('#adminForm #nav_tab_1').trigger('click'); }
                                    else if(key == 'error' && value == 'price'){ $('#adminForm #nav_tab_2').trigger('click'); }
                                }); 
                                $('#'+errKey).focus();
                                $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save'); return false;
                            }
                            return false;
                        }
                    });

                }
            }
          return false; 
        });
        
        $('body').on('change','#start_date',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/sales/orders")}}',
                data: {start_date: this.value, end_date: $('#end_date').val(),seller: $('#seller').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
        
        $('body').on('change','#end_date',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/sales/orders")}}',
                data: {end_date: this.value, start_date: $('#start_date').val(),seller: $('#seller').val(),o_status: $('#o_status').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
        
        $('body').on('change','#seller',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/sales/orders")}}',
                data: {seller: this.value, start_date: $('#start_date').val(),end_date: $('#end_date').val(),o_status: $('#o_status').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
        
        $('body').on('change','#p_status',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/sales/orders")}}',
                data: {p_status: this.value, start_date: $('#start_date').val(),end_date: $('#end_date').val(),seller: $('#seller').val(),o_status: $('#o_status').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
        
        
        $('body').on('change','#o_status',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/sales/orders")}}',
                data: {o_status: this.value, start_date: $('#start_date').val(),end_date: $('#end_date').val(),seller: $('#seller').val(),p_status: $('#p_status').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
                
        $('body').on('click','.actBtn',function(){
            var id          =   this.id.replace('acceptBtn-',''); 
            var id          =   id.replace('denyBtn-',''); 
            var status      =   $(this).data('val');
            var url         =   '{{url("admin/sales/order/updateStatus")}}';
            var smsg        =   'Order '+status+' successfully!';
            swal({
                title: "Order status change Confirmation",
                text: "Are you sure want to update order status?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) { 
                    updateStatus(id,'',status,url,'sales.order_request','order_status',smsg);
                }
            });
        });
        
        $('body').on('click','.plus-minus-toggle', function() {
            $(this).toggleClass('collapsed');
            $('#filtersec').toggle('slow');
        });

    });

    function updateStatus(id,rowId,status,url,row,field,smsg){
        $.ajax({
            type: "POST",
            url: url,
            data: { "_token": "{{csrf_token()}}", id: id, value: status,field: field, field, page: row, start_date: $('#start_date').val(), end_date: $('#date_date').val(),type: 'request'},
            success: function (data) {
                if(field == 'is_deleted'){ 
                  $('#active_filter').trigger('change'); toastr.success(smsg);
                }else if(field == 'order_status'){
                    $('#content_list').html(data); toastr.success(smsg); return false;
                }else{ 
                    if($('#active_filter').val() != ''){ $('#active_filter').trigger('change'); }
                    if (data.type == 'warning' || data.type == 'error'){ toastr.error(smsg); }else{ toastr.success(smsg); }
                } 
            }
        });
    }
    function submitForm(postValues){
        $.ajax({
            type: "POST",
            url: '{{url("admin/seller/save")}}',
            data: postValues,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) { 
              $('#adminForm #save_btn').attr('disabled',false); $('#adminForm #save_btn').text('Save');
              if($('#adminForm #id').val() > 0){ var msg = 'Seller updated successfully!'; }else{ msg = 'Seller added successfully!'; }
              $('#content_list').fadeIn(700); $('#content_detail').hide();
              $('#active_filter').trigger('change'); toastr.success(msg);  return false;
            } 
        });
    }
    function getStateDropdown(cId,selected){ 
        $.ajax({
            type: "POST",
            url: '{{url("admin/getDropdown/states/")}}',
            data: {field: 'country_id', value:cId, label:'name',selected: selected, placeholder:'Select State','_token': '{{ csrf_token()}}'},
            success: function (data) {
                $('#userForm #state').html(data);
            }
        });
    }
    
    function readURL(input) { 
        if (input.files && input.files[0]) { 
            var reader = new FileReader();
            reader.onload = function (e) { $('#adminForm #'+input.id+'Img').attr('src', e.target.result); $('#adminForm #'+input.id+'Img').show(); }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
