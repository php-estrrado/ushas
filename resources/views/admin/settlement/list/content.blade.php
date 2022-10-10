<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>

<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div class="card-body">
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                <table id="attribute" class="settlement-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport">Select</th>
                                            <th class="wd-15p">Seller</th>
                                            <th class="wd-15p">Store</th>
                                            <th class="wd-15p">Earnings</th>
                                            <th class="wd-20p">Paid</th>
                                            <th class="wd-10p">Balance</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
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
                                                    <td><span class="d-none">{{$n}}</span></span></td>
                                                    <td>{{$row->seller->sellerInfo->fname}}</td>
                                                    <td>{{$row->seller->store->store_name}}</td>
                                                    <td>{{ getCurrency()->name }} {{$totEarn}}</td>
                                                    <td>{{ getCurrency()->name }} {{$row->paidSettlement($row->seller_id)}}</td>
                                                    <td>{{ getCurrency()->name }} {{($totEarn - $row->paidSettlement($row->seller_id))}}</td>
                                                    <td class="text-center">
                                                        <button id="settleBtn-{{$row->id}}" class="mr-2 btn btn-info btn-sm editForm" data-seller="{{$row->seller_id}}">Earnings</button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{URL::asset('admin/assets/js/datatable/tables/settlement-datatable.js')}}"></script>

