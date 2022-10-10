@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="sales" class="sales table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport"></th>
                                            <th class="wd-15p">Order ID</th>
                                          
                                            <th class="wd-15p">Customer</th>
                                            <th class="wd-15p">Order Date</th>
                                            <th class="wd-25p notexport">Total ({{$currency}})</th>
                                            <th class="wd-25p notexport">Tax ({{$currency}})</th>
                                            <th class="wd-25p notexport">Shipping ({{$currency}})</th>
                                            <th class="wd-15p">Delivery Status</th>
                                            <th class="wd-15p">Delivery Date</th>
                                            <th class="wd-15p">Payment Method</th>
                                            <th class="wd-15p">Payment Status</th>
                                            <th class="wd-15p">Order Status</th>
                                            <th class="wd-15p">Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($orders && count($orders) > 0) @php $n = 0; @endphp
                                            @foreach($orders as $row) @php $n++; @endphp <?php // echo '<pre>'; print_r($row->address); echo '</pre>'; die; ?>
                                                @if($row->customer)
                                                @php 
                                                if($row->payment_status == 'pending'){ $pstat = 'primary'; }else if($row->payment_status == 'processing'){ $pstat = 'info'; }else if($row->payment_status == 'success'){ $pstat = 'success'; }else{ $pstat = 'default'; }
                                                if($row->order_status == 'pending'){ $ostat = 'primary'; }else if($row->order_status == 'processing'){ $ostat = 'info'; }else if($row->order_status == 'canceled'){ $ostat = 'error'; }
                                                else if($row->order_status == 'accepted'){ $ostat = 'success'; }else{ $ostat = 'default'; }
                                                $cust_id = date('y',strtotime($row->customer->info->created_at)).date('m',strtotime($row->customer->info->created_at)).str_pad($row->customer->info->id, 6, "0", STR_PAD_LEFT); 
                                                @endphp
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></td>
                                                    <td><a id="dtlBtn-{{$row->id}}" class="font-weight-bold viewDtl">{{$row->order_id}}</a></td> 
                                                   
                                                    <td> {{$row->customer->info->first_name}} {{$row->customer->info->last_name}} <br> ({{"#".$cust_id}})</td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td>{{$row->total}}</td>
                                                    <td>{{$row->tax}}</td>
                                                    <td>{{$row->shipping_charge}}</td>
                                                    <td>@if($row->delivery_status =="") {{ "Pending" }} @else {{$row->delivery_status}} @endif</td>
                                                    <td>{{$row->delivery_date}}</td>
                                                    <td>
                                                        @foreach($row->payments as $pay)
                                                        <div class="pay">{{$pay->payment_type}}</div>
                                                        @endforeach
                                                    </td>
                                                    <td><span class="badge badge-{{$pstat}} mt-2">{{ucwords(str_replace('_',' ',$row->payment_status))}}</span></td>
                                                    <td><span class="badge badge-{{$ostat}} mt-2">{{ucwords(str_replace('_',' ',$row->order_status))}}</span></td>
                                                    <td> @if($row->calcel) {{ "Cancel: ".ucfirst($row->calcel->status) }} @endif</td>
                                                    <td class="text-center">
                                                        <button id="editBtn-{{$row->id}}" class="mr-2 btn btn-success btn-sm editBtn"><i class="fa fa-file mr-1"></i>Invoice</button>
                                                    </td> 
                                                    </tr>
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

 <script src="{{asset('admin/assets/js/datatable/tables/order-datatable.js')}}"></script>
 <style type="text/css">

.plabels {
	display: flex;
}
.plabels p {
	margin: 0;
}
</style>