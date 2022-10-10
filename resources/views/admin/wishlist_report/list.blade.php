@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="wishlist-table" class="wishlist-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p">#</th>
                                            <th class="wd-15p">Product Name</th>
                                            <th class="wd-15p">Total customers</th>
                                            <th class="wd-15p">Total purchases</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if($data && count($data) > 0) @php $n = 0; @endphp
                                            @foreach($data as $row) @php $n++; @endphp 
                                             @if($row->product)
                                    <tr>    
                                        <td class="align-middle select-checkbox"></td>
                                        <td>{{$row->product->get_content($row->product->name_cnt_id)}}</td>
                                        @php 
                                        $count_users =DB::table('usr_wishlists')->where('prd_id',$row->prd_id)->where('is_deleted',0)->count();
                                        @endphp
                                        <td>{{$count_users}}</td>
                                        @php 
                                        $count =DB::table('sales_order_items')->where('prd_id',$row->prd_id)->count();
                                        @endphp
                                        <td>{{$count}}</td>
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

 <script src="{{asset('admin/assets/js/datatable/tables/wishlist-datatable.js')}}"></script>
 