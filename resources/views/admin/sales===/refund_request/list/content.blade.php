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
                                            <th class="wd-15p">Request Date</th>
                                            <th class="wd-25p">Refund Amount ({{$currency}})</th>
                                            <th class="wd-25p">Source</th>
                                          <!--   <th class="wd-25p">Refund Amount ({{$currency}})</th> -->
                                        <!--     <th class="wd-15p">Pay Method</th> -->
                                            <th class="wd-15p">Payment Status</th>
                                            <th class="wd-15p">Order Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($refunds && count($refunds) > 0) @php $n = 0; @endphp
                                            @foreach($refunds as $row) @php $n++; @endphp <?php // echo '<pre>'; print_r($row->calcel); echo '</pre>'; die; ?>
                                                @php 
                                                if($row->payment_status == 'pending'){ $pstat = 'primary'; }else if($row->payment_status == 'processing'){ $pstat = 'info'; }else if($row->payment_status == 'success'){ $pstat = 'success'; }else{ $pstat = 'default'; }
                                                if($row->order_status == 'pending'){ $ostat = 'primary'; }else if($row->order_status == 'processing'){ $ostat = 'info'; }else if($row->order_status == 'canceled'){ $ostat = 'error'; }
                                                else if($row->order_status == 'accepted'){ $ostat = 'success'; }else{ $ostat = 'default'; }
                                                @endphp
                                                <?php if($row->returninfo->status == 'refund_initiated' || $row->returninfo->status == 'refund_completed')
                                                {
                                                ?>
                                                
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></td>
                                                    <td><a id="dtlBtn-{{$row->id}}" class="font-weight-bold viewDtl">{{$row->order_id}}</a></td> 
                                                    <td>{{$row->customerrr->name}}</td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td>{{$row->grand_total}}</td>
                                                    <td>{{ucfirst($row->source)}}</td>
                                                  <!--   <td>{{$row->source}}</td> -->
                                                  
                                                    <td><span class="badge badge-{{$pstat}} mt-2">{{ucfirst($row->payment_status)}}</span></td>
                                                    <?php if($row->source == 'return')
                                                    { ?>
                                                        <td><span class="badge badge-{{$ostat}} mt-2">{{ucfirst($row->returninfo->status)}}</span></td>
                                                    <?php }
                                                    else
                                                    { ?>
                                                        <td><span class="badge badge-{{$ostat}} mt-2">{{ucfirst($row->order_status)}}</span></td>
                                                    <?php } ?>
                                                    <td class="text-center">
                                                        <?php if($row->returninfo->status == 'refund_initiated')
                                                        {
                                                        ?>
                                                        <button id="acceptBtn-{{$row->id}}" class="mr-2 btn btn-success btn-sm acceptBtn" data-val="refunded"><i class="fa fa-check mr-1"></i>Accept</button>
                                                        <?php } ?>
                                                        <button id="editBtn-{{$row->id}}" class="mr-2 btn btn-success btn-sm editBtn"><i class="fa fa-eye mr-1"></i>View</button>
                                                    </td> 
                                                </tr>
                                               <?php } ?> 
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

 <script src="{{asset('admin/assets/js/datatable/tables/order_request-datatable.js')}}"></script>
