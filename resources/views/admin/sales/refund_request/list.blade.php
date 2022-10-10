<style> .no-border{ border: none } .invoice i.fa{ color: #ff0000; font-size: 10px; } </style>
@php $n_img = 1; $currency = getCurrency()->name; @endphp
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Ecom Sales</a></li>
            <li class="breadcrumb-item active list-li" aria-current="page"><a href="#">Refund Request List</a></li>
            <li class="breadcrumb-item view-li no-disp"><a id="bc_list" href="">Refund Request List</a></li>
            <li class="breadcrumb-item active view-li no-disp" aria-current="page"><a href="#">Request Detail</a></li>
        </ol>
    </div>
</div>

<div id="filters">
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
<div id="content_list">@include('admin.sales.refund_request.list.content')</div>
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
            {{Form::text('title','',['id'=>'title','class'=>'form-control','placeholder'=>'EnterTitle'])}}<div id="title_error" class="error"></div>
            {{Form::textarea('reply','',['id'=>'reply','class'=>'form-control','placeholder'=>'Enter cancel request comment','rows'=>5])}} <div id="reply_error" class="error"></div>
            {{Form::hidden('cancelId','',['id'=>'cancelId'])}} {{Form::button('Submit',['id'=>'reply_submit','class'=>'btn btn-info fr'])}}
        </div>
    </div>
</div>
@section('js') 
 <script src="{{asset('admin/assets/js/datatable/tables/order_request-datatable.js')}}"></script>

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
               url: '{{url("admin/sales/refund")}}/'+id+"/request",
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
                url: '{{url("admin/sales/orders/request")}}',
                data: {start_date: this.value, end_date: $('#end_date').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
        
        $('body').on('change','#end_date',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/sales/orders/request")}}',
                data: {end_date: this.value, start_date: $('#start_date').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
        
        $('body').on('click','.acceptBtn',function(){
            var id          =   this.id.replace('acceptBtn-',''); 
            var status      =   $(this).data('val');
            var url         =   '{{url("admin/sales/order/refund/updateStatus")}}';
            var smsg        =   'Refund accepted successfully!';
            var desc        =   'Refund accepted by Admin'; 
            swal({
                title: "Refund Accept Confirmation",
                text: "Are you sure want to accept this refunt request?",
                // type: "input",
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: 'Yes'
            },function(inputValue){
                if (inputValue == true) {  
                    updateStatus(id,'refund',status,url,'admin.sales.refund_request','order_status',smsg,desc);
                }
            });
        });
        
       
        
        $('body').on('click', '#reply_submit', function(){ 
            var res = true;
           if($('.modal-content #title').val() == ''){ $('.modal-content #title_error').text('Title field is required'); res = false; }else{ $('.modal-content #title_error').text(''); }
           if($('.modal-content #reply').val() == ''){ $('.modal-content #reply_error').text('Comment field is required'); res = false; }else{ $('.modal-content #reply_error').text(''); }
           if(res == false) {   return false; }
            var id          =   $('.modal-content #cancelId').val();  
            var status      =   'cancel_initiated';     
            var url         =   '{{url("sales/order/updateStatus")}}';
            var smsg        =   'Order cancellatio initiated successfully!';
            var desc        =   'Order cancellation initiated by Seller';
            updateStatus(id,'sales_orders',status,url,'sales.order_request','order_status',smsg,desc); return false;
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
                    "_token": "{{csrf_token()}}", id: id, value: status,field: field, field, page: row, reply: $('.modal-content #reply').val(), model: rowId,
                     title: $('.modal-content #title').val(), start_date: $('#start_date').val(), end_date: $('#end_date').val(),type: 'ref_reqs', desc: desc
                },
            success: function (data) {
                if(field == 'is_deleted'){ 
                  $('#active_filter').trigger('change'); toastr.success(smsg);
                }else if(field == 'order_status'){
                    $('#content_list').html(data); $('.bd-example-modal-sm').modal('hide'); toastr.success(smsg); 
                    //statusEmail(id); 
                    return false;
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
    
    function statusEmail(id){
        $.ajax({
            type: "POST",
            url: "{{url('admin/send/order/status/email')}}",
            data: { "_token": "{{csrf_token()}}", id: id,type: 'request'},
            success: function (data) { }
        });
    }
    
    function getStateDropdown(cId,selected){ 
        $.ajax({
            type: "POST",
            url: '{{url("admin/getDropdown/states/")}}',
            data: {field: 'country_id', value:cId, label:'name',selected: selected, placeholder:'Select State','_token': '{{ csrf_token()}}'},
            success: function (data) {
                
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
