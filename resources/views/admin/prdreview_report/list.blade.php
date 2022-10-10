@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="prdreview" class="prdreview table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p">#</th>
                                            <th class="wd-15p">Product Name</th>
                                            <th class="wd-15p">No.of reviews</th>
                                            <th class="wd-15p">No.of rating</th>
                                            <th class="wd-15p">Last review</th>
                                            <th class="wd-15p">Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if($data && count($data) > 0) @php $n = 0; @endphp
                                            @foreach($data as $row) @php $n++; @endphp 
                                    <tr>    
                                        <td class="align-middle select-checkbox"></td>
                                        <td>{{$row['product_name']}}</td>
                                        <td>{{$row['reviews']}}</td>
                                        <td>{{$row['rating']}}</td>
                                        <td><span style="display: block;width: 100px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">{{$row['latest']}}</span></td>
                                        @php $view=$row['view']; @endphp
                                        <td><button id="viewBtn-@php echo $view @endphp" class="mr-2 btn btn-success btn-sm viewBtn"><i class="fa fa-eye mr-1"></i>View</button></td>
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

 <script src="{{asset('admin/assets/js/datatable/tables/prdreviews-datatable.js')}}"></script>
 <script type="text/javascript">
     $(document).ready(function(){
        $('#filters').show(); 
     });
 </script>
 