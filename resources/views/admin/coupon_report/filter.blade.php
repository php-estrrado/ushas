
<style> .no-border{ border: none } .invoice i.fa{ color: #ff0000; font-size: 10px; } </style>
@php 
$n_img = 1; $currency = getCurrency()->name;
@endphp
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Reports</a></li>
            <li class="breadcrumb-item active list-li" aria-current="page"><a href="#">{{$title}}</a></li>
            <!-- <li class="breadcrumb-item view-li no-disp"><a id="bc_list" href="">Order List</a></li> -->
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
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('seller','Sellers',['class'=>'text-white'])}}
                {{Form::select('seller',$sellers,$seller,['id'=>'seller','class'=>'form-control','placeholder'=>'All Sellers'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                <label class="text-white">Product</label>
                <select class="form-control" placeholder="All products" id="product">
                    <option value="">All</option>
                    @foreach($product as $pro)
                    <option value="{{$pro->id}}">{{$pro->get_content($pro->name_cnt_id)}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        
        <div class="clr"></div>
    </div>
</div>
<div id="content_list">@include('admin.coupon_report.list')</div>
<div id="content_detail"></div>
@section('js') 
 <script src="{{asset('admin/assets/js/datatable/tables/coupons-datatable.js')}}"></script>

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
       
        
        
        
        $('body').on('change','#start_date',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            
            $.ajax({
                type: "POST",
                url: '{{url("admin/report/coupon-report")}}',
                data: {start_date: this.value, end_date: $('#end_date').val(),seller: $('#seller').val(),product: $('#product').val(),viewType: 'ajax'},
                success: function (data) {
                    
                    $('#content_list').html(data); 
                } 
            });
        });
        
        $('body').on('change','#end_date',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/report/coupon-report")}}',
                data: {end_date: this.value, start_date: $('#start_date').val(),seller: $('#seller').val(),product: $('#product').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
        
        $('body').on('change','#seller',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/report/coupon-report")}}',
                data: {seller: this.value, start_date: $('#start_date').val(),end_date: $('#end_date').val(),product: $('#product').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
                } 
            });
        });
        
        $('body').on('change','#product',function(){  
            $('#table_body').append($('#loader').html()); $('#attribute').addClass('blur'); 
            $.ajax({
                type: "POST",
                url: '{{url("admin/report/coupon-report")}}',
                data: {product: this.value, start_date: $('#start_date').val(),end_date: $('#end_date').val(),seller: $('#seller').val(),viewType: 'ajax'},
                success: function (data) {
                    $('#content_list').html(data); 
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
