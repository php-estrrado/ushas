@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div class="table-responsive">
                                    <table id="visit-table" class="visit-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="">Date</th>
                                            <th class="">Product</th>
                                           
                                            <th class="">User views</th>
                                            <th class="">Visitors views</th>
                                            <th class="">Total views</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if($visit && count($visit) > 0) @php $n = 0; @endphp
                                            @foreach($visit as $row) @php $n++; @endphp 
                                            @if($row->product)
                                        <tr>
                                        <td class="align-middle select-checkbox"></td>
                                        <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                        <td>{{$row->product->get_content($row->product->name_cnt_id)}}</td>
                                       
                                        <td>{{$row->users}}</td>
                                        <td>{{$row->total - $row->users}}</td>
                                        <td>{{$row->total}}</td>
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

 <script src="{{asset('admin/assets/js/datatable/tables/visit-datatable.js')}}"></script>
 