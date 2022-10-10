@extends('layouts.admin')

@section('page-header')
<div class="page-header">
        <div class="page-leftheader">
                <h4 class="page-title mb-0">{{$title}}</h4>
                <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Products</a></li>

                        <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
                </ol>
        </div>
        <div class="page-rightheader">
                <div class="btn btn-list">
                        <!-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>
                        <a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> -->
                        <a href="{{ route('admin.product') }}"   class="btn btn-primary addmodule"><i class="fe fe-plus mr-1"></i> Add New</a>
                </div>
        </div>
</div>
<div id="filters" class="">
    <div class="row" id="filterrow"><div class="plus-minus-toggle collapsed"><p>Filters</p></div></div>
    <div class="row no-disp mb-4 " id="filtersec">
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('active','Status',['class'=>'text-white'])}}
                {{Form::select('active',[1=>'Active',0=>'Inactive'],$active,['id'=>'active_filter','class'=>'form-control mr-4 active_filters','placeholder'=>'All Status'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('category','Category',['class'=>'text-white'])}}
                {{Form::select('category',$categories,'',['id'=>'category','class'=>'form-control mr-4 active_filters','placeholder'=>'All Categories'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('subCats','Sub Category',['class'=>'text-white'])}}
                {{Form::select('subCats',$subCats,'',['id'=>'subCats','class'=>'form-control mr-4 active_filters','placeholder'=>'All Sub Categories'])}}
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>
@endsection
@section('content')
						<!-- Row -->
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                <table id="product" class="product-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="align-top border-bottom-0 wd-5">Select</th>
                                            <th class="border-bottom-0 w-20">Product</th>
                                            <th class="border-bottom-0 w-20">Category</th>
                                            <th class="border-bottom-0 w-20">Subcategory</th>
                                            <th class="border-bottom-0 w-15">Created On</th>
                                            <th class="border-bottom-0 w-30 notexport">Status</th>
                                            <th class="" style="display:none">Status</th>
                                            <th class="border-bottom-0 w-10 notexport">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
				</table>
                                {{Form::hidden('baseurl',url('/'),['id'=>'baseurl'])}} 
                                {{ csrf_field() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{URL::asset('admin/assets/js/datatable/tables/product-datatable.js')}}"></script>

<script type="text/javascript">
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } }); 
    
    $(document).ready(function(){ 
        $('.plus-minus-toggle').on('click', function(){ $(this).toggleClass('collapsed'); $('#filtersec').toggle('slow'); }); 
        $('#filtersec .active_filters').on('change',function(){ 
            var active  =   $('#filtersec #active_filter').val(); var subCats  =   $('#filtersec #subCats').val(); var cat  =   $('#filtersec #category').val();
            $(".product-table").DataTable().ajax.url("{{url('/admin/product/list')}}?active="+active+"&sub_cat="+subCats+"&category="+cat).load();
        });
            @if(Session::has('message'))
            @if(session('message')['type'] =="success")
            
            toastr.success("{{session('message')['text']}}"); 
            @else
            toastr.error("{{session('message')['text']}}"); 
            @endif
            @endif
            
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{$error}}"); 
            
            @endforeach
            @endif
    });
    </script>
		
<script type="text/javascript">

	// function delete_cat(cat_id){
    //    // alert(cat_id);
    //    $('#del_modal').show();
    //    $('#ok_button').click(function(){
    //     $.ajax({
    //         type: "POST",
    //         url: '{{url("/admin/product/delete-product/")}}',
    //         data: { "_token": "{{csrf_token()}}", cat_id: cat_id},
    //         success: function (data) {
    //             location.reload();

    //         }
    //     });
    // });
    // }

    function delete_cat(prd_id){

$('body').removeClass('timer-alert');
swal({
    title: "Delete Confirmation",
    text: "Are you sure you want to delete this product?",
    // type: "input",
    showCancelButton: true,
    closeOnConfirm: true,
    confirmButtonText: 'Yes'
},function(inputValue){



    if (inputValue == true) {
     $.ajax({
    type: "POST",
    url: '{{url("/admin/product/delete-product/")}}',
    data: { "_token": "{{csrf_token()}}", prd_id: prd_id},
    success: function (data) {
            location.reload();
    }
});

    }
});
}

    

    $(function() {
    $('body').on('change', '.status-btn', function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var prd_id = this.id.replace('status-','');

        $.ajax({
            type: "POST",
            url: '{{url("/admin/product/change-status-product")}}',
            data: { "_token": "{{csrf_token()}}", prd_id: prd_id,status: status},
            success: function (data) {
            $(".product-table").DataTable().ajax.reload();
                console.log(data.success)
            }
        });
        
        if(status ==1) {
              toastr.success("Product activated successfully.");   
            }else {
               toastr.success("Product deactivated successfully.");  
            }
    })
  })
</script>

@endsection
