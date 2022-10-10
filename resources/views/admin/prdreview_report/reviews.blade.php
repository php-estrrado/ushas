@php $currency = getCurrency()->name; @endphp
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">

                    <div id="data-content" class="card-body">
                       <div class="card"><div class="card-header"><h3 class="card-title">{{$product_name}}</h3></div></div>
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                    <table id="product_reviews" class="product_reviews table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p">#</th>
                                            <th class="wd-15p">Customer Name</th>
                                            <th class="wd-15p">Message</th>
                                            <th class="wd-15p">Rating</th>
                                            <th class="wd-15p">Date & time</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if($data && count($data) > 0) @php $n = 0; @endphp
                                            @foreach($data as $row) @php $n++; @endphp 
                                    <tr>    
                                        <td class="align-middle select-checkbox"></td>
                                        <td>{{$row->customerinfo($row->user_id)->first_name}}</td>
                                        <td>{{$row->comment}}</td>
                                        <td>{{$row->rating}}</td>
                                        <td>{{$row->created_at}}</td>
                                    </tr>
                                    
                                    @endforeach
                                        @endif
                                        </tbody>
                                </table>
                                {{ csrf_field() }}
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-12">
                    <div class="card-footer text-right">
                        <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                    </div>
                    </div> -->
                    <div class="col-lg-12">
                    <div class="card-footer text-right">
                        <a id="cancel_btn" type="button" class="btn btn-secondary" href="{{url('admin/report/product-review-report')}}">Back</a>
                    </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

 <script src="{{asset('admin/assets/js/datatable/tables/product-reviews-datatable.js')}}"></script>
 <script type="text/javascript">
     $(document).ready(function(){
        $('#filters').hide(); 

        // $('body').on('click','#cancel_btn',function(){
        //             $('#filters').show();
        //             $('#content_list').html(data); 
        //             $('#content_list').show(); 
        //             $('#content_detail').hide(); 
        //           //  $('#content_detail #country_id').trigger("chosen:updated");
        //         return false;
        // });
     });
 </script>
 