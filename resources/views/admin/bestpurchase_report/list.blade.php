@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div class="table-responsive">
                                    <table id="bestpurchases" class="bestpurchases table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p">#</th>
                                            <th class="wd-15p">Product Name</th>
                                            <th class="wd-15p">Items sold</th>
                                            <!--<th class="wd-15p">Repeated purchase</th>-->
                                            <th class="wd-15p">Total reviews</th>
                                            <th class="wd-15p">Tot.avg rating</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if($data && count($data) > 0) @php $n = 0; @endphp
                                            @foreach($data as $row) @php $n++; @endphp 
                                    <tr>    
                                        <td class="align-middle select-checkbox"></td>
                                        <td>{{$row['product_name']}}</td>
                                        <td>{{$row['sold']}}</td>
                                        <!--<td>{{$row['cust_repeat']}}</td>-->
                                        <td>{{$row['tot_review']}}</td>
                                        <td>{{$row['tot_rating']}}</td>
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

 <script src="{{asset('admin/assets/js/datatable/tables/bestpurchase.js')}}"></script>
 