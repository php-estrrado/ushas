@php $n_img = 1; $currency = getCurrency()->name; @endphp
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Sales Management</a></li>
            <li class="breadcrumb-item active list-li" aria-current="page"><a href="#">{{$title}}</a></li>
            <li class="breadcrumb-item view-li no-disp"><a id="bc_list" href="">Cancel Orders</a></li>
            <li class="breadcrumb-item active view-li no-disp" aria-current="page"><a href="#">Order Detail</a></li>
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

<div id="filters" class="">
    <div class="row" id="filterrow"><div class="plus-minus-toggle collapsed"><p>Filters</p></div></div>
    <div class="row no-disp mb-4 " id="filtersec">
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('start_date','Date From',['class'=>'text-white'])}}
                {{Form::date('start_date','',['id'=>'start_date','class'=>'form-control'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('end_date','Date To',['class'=>'text-white'])}}
                {{Form::date('end_date','',['id'=>'end_date','class'=>'form-control'])}}
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>
<div id="content_list">@include('sales.cancel_order.list.content')</div>
<div id="content_detail"></div>
<div id="modal_cnt" class="d-none">
    <div class="col-12 mb-4">
        <div class="modal-header">
            <h5 class="modal-titlee" id="exampleModalLongTitle">Comment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="col-12 mb-4 mt-4"> 
            {{Form::textarea('reply','',['id'=>'reply','class'=>'form-control','placeholder'=>'Enter cancel request comment','rows'=>5])}} <div id="reply_error" class="error"></div>
            {{Form::hidden('cancelId','',['id'=>'cancelId'])}} {{Form::button('Submit',['id'=>'reply_submit','class'=>'btn btn-info fr'])}}
        </div>
    </div>
</div>
@section('js') 
 <script src="{{asset('admin/assets/js/datatable/tables/cancel_order-datatable.js')}}"></script>

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
               url: '{{url("sales/cancel/order")}}/'+id,
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
               url: '{{url("sales/cancel/order")}}/'+id,
                success: function (data) {
                    $('.list-li').hide(); $('.view-li').fadeIn(700);  $('#filters').hide();
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
        
        $('body').on('click','#accept_btn',function(){ 
            var id          =   $(this).data('val');  
            var status      =   'accepted';
            var url         =   '{{url("sales/order/updateStatus")}}';
            var smsg        =   'Request '+status+' successfully!';
            var desc        =   'Cancellation accepted by Seller';
            swal({
                title: "Order cancel Confirmation",
                text: "Are you sure want to cancel this order?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) { 
                    updateStatus(id,'order_cancels',status,url,'sales.cancel_order','status',smsg,desc);
                }
            });
        });
        
        $('body').on('click','#reject_btn',function(){
            var id          =   $(this).data('val');  
            $('.bd-example-modal-sm .modal-content').html($('#modal_cnt').html()); $('.modal-content #cancelId').val(id);
        });
        
        $('body').on('click', '#reply_submit', function(){
           if($('.modal-content #reply').val() == ''){ $('.modal-content #reply_error').text('Comment field is required'); return false; }else{ $('.modal-content #reply_error').text(''); }
            var id          =   $('.modal-content #cancelId').val();  
            var status      =   'rejected';     
            var url         =   '{{url("sales/order/updateStatus")}}';
            var smsg        =   'Request '+status+' successfully!';
            var desc        =   'Cancellation rejected by Seller';
            updateStatus(id,'order_cancels',status,url,'sales.cancel_order','status',smsg,desc); return false;
        });
        
        $('body').on('change','#start_date',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("sales/cancel/orders")}}',
                data: {start_date: this.value, end_date: $('#end_date').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                    $('#content_list').fadeIn(700); $('#content_detail').hide(); $('.view-li').hide(); $('.list-li').fadeIn(500);  $('#filters').show(); return false;
                } 
            });
        });
        
        $('body').on('change','#end_date',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("sales/cancel/orders/$type")}}',
                data: {end_date: this.value, start_date: $('#start_date').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                    $('#content_list').fadeIn(700); $('#content_detail').hide(); $('.view-li').hide(); $('.list-li').fadeIn(500);  $('#filters').show(); return false;
                } 
            }); return false;
        });
        
        $('body').on('click','.actBtn',function(){
            var id          =   this.id.replace('acceptBtn-',''); 
            var id          =   id.replace('denyBtn-',''); 
            var status      =   $(this).data('val');
            var url         =   '{{url("sales/order/updateStatus")}}';
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

    function updateStatus(id,rowId,status,url,row,field,smsg,desc){ 
        $.ajax({
            type: "POST",
            url: url,
            data: {     
                "_token": "{{csrf_token()}}", id: id, value: status,field: field, field, page: row, reply: $('.modal-content #reply').val(),
                start_date: $('#start_date').val(), end_date: $('#end_date').val(), model: rowId,type: "{{$type}}",desc: desc
            },
            success: function (data) {
                if(field == 'is_deleted'){ 
                  $('#active_filter').trigger('change'); toastr.success(smsg);
                }else if(field == 'order_status' || field == 'status'){
                    $('#content_list').html(data); $('.bd-example-modal-sm').modal('hide'); toastr.success(smsg);
                    $('#content_list').fadeIn(700); $('#content_detail').hide(); $('.view-li').hide(); $('.list-li').fadeIn(500);  $('#filters').show(); return false;
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
