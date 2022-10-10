<!--<div class="page-header">-->
<!--    <div class="page-leftheader">-->
<!--        <h4 class="page-title mb-0">{{$title}}</h4>-->
<!--        <ol class="breadcrumb">-->
<!--            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>-->
<!--            @if($sellerId > 0)-->
<!--            <li class="breadcrumb-item"><a href="#" id="bc_list" data-seller="{{$sellerId}}"><i class="fe fe-grid mr-2 fs-14"></i>Seller Settlement</a></li>-->
<!--            @endif-->
<!--            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>-->
<!--        </ol>-->
<!--    </div>-->
<!--</div>-->

<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div class="card-body">
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                <table id="attribute" class="earning-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport">Select</th>
                                            <th class="wd-15p">Order ID</th>
                                            <th class="wd-15p">Seller</th>
                                            <th class="wd-15p">Date</th>
                                            <th class="wd-20p">Delivery Status</th>
                                            <th class="wd-10p">Sellers Price</th>
                                            <!--<th class="wd-10p">MJS Tax</th>-->
                                            <th class="wd-25p ">Earnings</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($earnings && count($earnings) > 0) @php $n = 0; @endphp
                                            @foreach($earnings as $row) @php $n++; @endphp
                                                @php 
                                                $total_earnings = $row->totalEarnings($row->seller_id,$row->id); 
                                                $seller_price   = $row->product_amount($row->seller_id,$row->id); 
                                                @endphp
                                                @if($row->seller)
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></span></td>
                                                    <td><a id="dtlBtn-{{$row->id}}" class="font-weight-bold">#{{$row->order_id}}</a></td> 
                                                    <td>{{$row->seller->store->store_name}}</td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td>{{$row->shipping_status}}</td>
                                                    <!--<td>{{ getCurrency()->name }} {{$row->g_total}}</td>-->
                                                    <td>{{ getCurrency()->name }} {{$seller_price['seller_amount']}}</td>
                                                    <td>{{ getCurrency()->name }} {{$total_earnings['earnings']}}</td>
                                                    
                                                    <td class="text-center">
                                                        <button id="payBtn-{{$row->id}}" class="mr-2 btn btn-success btn-sm payBtn" data-seller="{{$row->seller_id}}" data-toggle="modal" data-target=".bd-example-modal-sm">Make Payment</button>
                                                    </td>
                                                    @endif
                                            @endforeach
                                        @endif
                                    </tbody>

                                </table>
                                {{ csrf_field() }}

                            </div>
                        </div>
                        @if($sellerId > 0)
                        <div class="card-footer text-right">
                            <button id="back_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{URL::asset('admin/assets/js/datatable/tables/earning-datatable.js')}}"></script>

