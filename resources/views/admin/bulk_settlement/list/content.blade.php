<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>
<div class="row" id="filterrow">
<div class="plus-minus-toggle collapsed"><p>Additional Filters</p></div> 
</div>
<div class="row mb-3" id="filtersec" style="display:none;">
    <div class="row col-md-12">
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

<div class="col">
<div class="page-filters" >

<select class="form-control dropfill" id="state_id">
<option value="">All States</option>
@if($states && count($states) > 0)
@foreach($states as $kp)
<option value="{{ $kp->id }}">{{ $kp->state_name }}</option>
@endforeach
@endif

</select>


</div>                              
</div>
<div class="col">
<div class="page-filters" >

<select class="form-control dropfill" id="city_id">
<option value="">All Cities</option>


</select>


</div>                              
</div>
</div>
<div class="row  col-md-12 mt-4">

<div class="col-md-4">
<div class="page-filters">

<select class="form-control dropfill chosen-select" multiple name="cat_id[]" id="filterSell" data-placeholder="Select Category" >
<option value="">All Categories</option>
@if($categories && count($categories) > 0)
@foreach($categories as $kv)
<option value="{{ $kv->category_id }}">{{ $kv->cat_name }}</option>
@endforeach
@endif

</select>


</div>                              
</div>
<div class="col-md-4 col-md-offset-4">
<a  id="viewfilter"  class="mr-2 btn btn-info btn-sm pointer"><i class="fa fa-check-circle"></i> Apply</a>
</div>                              
</div>
</div> col-md-12

<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div class="card-body">
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                 {{ Form::open(array('url' => "admin/seller/bulk-settlements/save", 'id' => 'bulkForm', 'name' => 'bulkForm', 'class' => '','files'=>'true')) }}
                                <table id="attribute" class="settlement-table table table-striped table-bordered w-100 text-nowrap">
                                   
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport">Select</th>
                                            <th class="wd-15p">Seller</th>
                                            <th class="wd-15p">Store</th>
                                            <th class="wd-15p">Total Earnings</th>
                                            <th class="wd-20p">Paid</th>
                                            <th class="wd-10p">Balance</th>
                                           <!--  <th class="wd-25p text-center notexport action-btn">Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @if($settlements && count($settlements) > 0) @php $n = 0; @endphp
                                            @foreach($settlements as $row) @php $n++; @endphp
                                                @php 
                                                    $earnings = $row->totalSellerEarnings($row->seller_id); $totEarn = $earnings['earnings'];
                                                @endphp
                                                @if($row->seller)
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td class="process_selection"><input type="checkbox"  class="processitem" name="to_process[{{$row->seller_id}}]"  value="{{($totEarn - $row->paidSettlement($row->seller_id))}}" data-items="{{($totEarn - $row->paidSettlement($row->seller_id))}}" ></td>
                                                    <td>{{$row->seller->sellerInfo->fname}}</td>
                                                    <td>{{$row->seller->store->store_name}}</td>
                                                    <td>{{ getCurrency()->name }} {{$totEarn}}</td>
                                                    <td>{{ getCurrency()->name }} {{$row->paidSettlement($row->seller_id)}}</td>
                                                    <td>{{ getCurrency()->name }} {{($totEarn - $row->paidSettlement($row->seller_id))}}</td>
                                                   <!--  <td class="text-center">
                                                        <button id="settleBtn-{{$row->id}}" class="mr-2 btn btn-info btn-sm editForm" data-seller="{{$row->seller_id}}">Earnings</button>
                                                        <button id="payBtn-{{$row->id}}" class="mr-2 btn btn-success btn-sm payBtn" data-seller="{{$row->seller_id}}" data-toggle="modal" data-target=".bd-example-modal-sm">Make Payment</button>
                                                    </td> -->
                                            @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                            
                                </table>
                                {{ csrf_field() }}
                                {{Form::close()}}

                            </div>
      
<div class="row mt-4">
<div class="card">
<div class="card-header">
<h3 class="card-title">Total Settlement</h3>
</div>
<div class="col-md-6 col-md-offset-6">
<div class="expanel-body">
<div class="form-group">

<div class="input-group">
<input type="text" class="form-control" maxlength="6" minlength='6' required name="process_total" id="process_total" readonly placeholder="Amount to be paid...">
<span class="input-group-append">
<button class="btn btn-primary paynow"  type="button">Pay Now</button>
</span>
</div>


</div>
</div>
</div>
</div>
</div>
	



                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="display:none;">
<table id="hiddentable" style="display: none;">
<tbody>

</tbody>
</table>

</div>
<style type="text/css">
    table.dataTable tr.parent {
animation: none !important;
    }
    table.dataTable tr.selected p {
color: #fff;
    }

#viewfilter {
    display: block;
    margin: 5px;
    width: 90px;
    text-align: center;
}
.chosen-container-multi .chosen-choices {
    padding: 4px 10px;
    border: 1px solid #e3e4e9;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    border-radius: 5px;
}
.chosen-container.chosen-container-multi {
    min-width:300px;
}

</style>
<script src="{{URL::asset('admin/assets/js/datatable/tables/bulksettlement-datatable.js')}}"></script>
<script>
    pageSet();
</script>

