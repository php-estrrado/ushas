@php $currency = getCurrency()->name;  @endphp 
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                        <div class="tabs-menu mb-4">
                            <ul class="nav panel-tabs">
                                <li><a href="{{url('admin/sales/return/orders/request')}}" @if($type == 'request') class="active" @endif><span>Request</span></a></li>
                                <li><a href="{{url('admin/sales/return/orders/past')}}" @if($type == 'past') class="active" @endif><span>Past</span></a></li>
                           </ul> 
                        </div>
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="sales" class="sales table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport"></th>
                                            <th class="wd-15p">Order ID</th>
                                            <th class="wd-15p">Customer</th>
                                            <th class="wd-15p">Return Type</th>
                                            <th class="wd-15p">Requested On</th>
                                            <th class="wd-25p notexport">Total ({{$currency}})</th>
                                            <th class="wd-25p notexport">Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($orders && count($orders) > 0) @php $n = 0; @endphp
                                            @foreach($orders as $row) @php $n++; @endphp <?php // echo '<pre>'; print_r($row->address); echo '</pre>'; die; ?>
                                                @php 
                                                if($row->status == 'return_initiated')
                                                { $stat = 'primary'; $status = 'Return Initiated'; $disable = 'disabled'; }
                                                else if($row->status == 'return_accepted')
                                                { $stat = 'success'; $status = 'Return Accepted'; $disable = 'disabled'; }
                                                else if($row->status == 'return_rejected')
                                                { $stat = 'error'; $status = 'Return Rejected'; $disable = 'disabled'; }
                                                else if($row->status == 'shipment_initiated')
                                                { $stat = 'primary'; $status = 'Shipment Initiated'; $disable = ''; }
                                                else if($row->status == 'shipment_rejected')
                                                { $stat = 'error'; $status = 'Shipment Rejected'; $disable = '';}
                                                else if($row->status == 'refund_initiated')
                                                { $stat = 'primary'; $status = 'Refund Initiated'; $disable = '';}
                                                else if($row->status == 'refund_completed')
                                                { $stat = 'success'; $status = 'Refund Completed'; $disable = '';}
                                                else if($row->status == 'order_initiated'){
													$stat = 'success'; $status = 'Order Intiated'; $disable = '';}
												else
                                                { $stat = 'default'; $status = 'default'; $disable = ''; }
                                                @endphp
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></td>
                                                    <td><a id="dtlBtn-{{$row->id}}" class="font-weight-bold viewDtl">@if($row->order){{$row->order->order_id}}@endif</a></td> 
                                                    <td>@if($row->order) {{$row->order->address->name}} @endif</td>
                                                    <td>@if($row->order) Return & {{ ucfirst($row->return_type) }} @endif</td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td>{{$row->amount}}</td>
                                                    <td><span class="badge badge-{{$stat}} mt-2">{{ucfirst($status)}}</span></td>
                                                    <td class="text-center">
                                                        <button id="editBtn-{{$row->id}}" class="mr-2 btn btn-success btn-sm editBtn" {{$disable}}>View Shipment</button>
                                                    </td> 
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

 <script src="{{asset('admin/assets/js/datatable/tables/cancel_order-datatable.js')}}"></script>
 