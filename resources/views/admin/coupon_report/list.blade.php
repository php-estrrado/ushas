@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div class="table-responsive">
                                    <table id="couponslist" class="couponslist table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p">#</th>
                                            <th class="wd-15p">Created by</th>
                                            <th class="wd-15p">Seller Name</th>
                                            <th class="wd-15p">Coupon name & code</th>
                                            <th class="wd-15p">Offer type</th>
                                            <th class="wd-15p">Total users</th>
                                            <th class="wd-15p">Purchase amount</th>
                                            <th class="wd-15p">Discount amount</th>
                                            
                                        </tr>
                                    </thead>
                                     @if($data && count($data) > 0) @php $n = 0; @endphp
                                            @foreach($data as $row) @php $n++; @endphp 
                                    <tbody>
                                      
                                    <tr>    
                                        <td class="align-middle select-checkbox"></td>
                                        <td>
                                            @if($row->user_type=="seller")
                                            {{$row->Store($row->seller_id)->store_name}}
                                            @else
                                            Admin
                                            @endif
                                        </td>
                                        <td>
                                            {{$row->Store($row->sale_seller)->store_name}}
                                        </td>
                                        <td>{{$row->getCpnContent($row->cpn_title_cid)}} & <b>{{$row->ofr_code}}</b></td>
                                        <td>{{$row->ofr_type}}</td>
                                        <td>{{$row->users}}</td>
                                        <td>{{$row->purchase}}</td>
                                        <td>{{$row->discount}}</td>
                                    </tr>
                                    </tbody>
                                    @endforeach
                                        @endif
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

 <script src="{{asset('admin/assets/js/datatable/tables/coupons-datatable.js')}}"></script>
 