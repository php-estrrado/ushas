@php $currency = getCurrency()->name;  @endphp 
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                        <div class="tabs-menu mb-4">
                            <ul class="nav panel-tabs">
                                <li><a href="{{url('sales/cancel/orders/request')}}" @if($type == 'request') class="active" @endif><span>Request</span></a></li>
                                <li><a href="{{url('sales/cancel/orders/past')}}" @if($type == 'past') class="active" @endif><span>Past</span></a></li>
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
                                                if($row->status == 'pending'){ $stat = 'primary'; }else if($row->status == 'accepted'){ $stat = 'success'; }else if($row->status == 'rejected'){ $stat = 'error'; }else{ $stat = 'default'; }
                                                @endphp
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></td>
                                                    <td><a id="dtlBtn-{{$row->id}}" class="font-weight-bold viewDtl">{{$row->order->order_id}}</a></td> 
                                                    <td>{{$row->order->address->name}}</td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td>{{$row->order->total}}</td>
                                                    <td><span class="badge badge-{{$stat}} mt-2">{{ucfirst($row->status)}}</span></td>
                                                    <td class="text-center">
                                                        <button id="editBtn-{{$row->id}}" class="mr-2 btn btn-success btn-sm editBtn"><i class="fa fa-eye mr-1"></i>View</button>
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
 