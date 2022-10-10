@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="salesadmin" class="salesadmin table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport"></th>
                                            <th class="wd-15p">Order ID</th>
                                            <th class="wd-15p">Customer</th>
                                            <th class="wd-15p">Order Date</th>
                                            <th class="wd-25p">Total ({{$currency}})</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($orders && count($orders) > 0) @php $n = 0; @endphp
                                            @foreach($orders as $row) @php $n++; @endphp <?php // echo '<pre>'; print_r($row->address); echo '</pre>'; die; ?>
                                                
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></td>
                                                    <td><a id="dtlBtn-{{$row->id}}" class="font-weight-bold viewDtl">@if($row->order($row->id)){{$row->order($row->id)->order_id}}@endif</a></td> 
                                                    <td>@if($row->customer($row->user_id)){{$row->customer($row->user_id)->first_name}}@endif</td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td>{{$row->grand_total}}</td>
                                                    </tr>
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

 <script src="{{asset('admin/assets/js/datatable/tables/order_admin-datatable.js')}}"></script>
 