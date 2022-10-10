<div class="tab-pane attr" id="tab6">
    <div class="card-header mb-4"><div class="card-title">Reviews</div></div>
    <?php // echo '<pre>'; print_r($attributes); echo '</pre>'; ?>
<div class="row flex-lg-nowrap">   
<div class="col-md-4 col-md-offset-8">

<div class="page-filter mb-5"  style="display:flex; flex-direction: row; justify-content: center; align-items: center">

<label class="form-label" for="filterSel" style="margin-right: 8px;">Filter</label>
 <select class="form-control" id="filterSel" onchange="fromDt();" >
<option data-min="1" data-max="5" >All Rating</option>
<option data-min="1" data-max="2" >1-2 Rating</option>
<option data-min="1" data-max="3" >1-3 Rating</option>
<option data-min="1" data-max="4" >1-4 Rating</option>
<option data-min="1" data-max="5" >1-5 Rating</option>

</select>

</div>
</div>
</div>

<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                        <div class="e-table" id="divTableDataHolder">
                            <div class="table-responsive table-lg mt-3" >
                                    <table id="product_reviews" class="product_reviews product-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport"></th>
                                            <th class="wd-15p">Customer</th>
                                            <th class="wd-15p notexport">Rating</th>
                                            <th class="wd-15p ">Rating</th>

                                            <th class="wd-15p">Comment</th>
                                            <th class="wd-15p">Image</th>
                                            <th class="wd-20p">Created On</th>
                                            <th class="wd-25p notexport">Status</th>
                                            <!-- <th class="wd-25p text-center notexport action-btn">Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @if($reviews && count($reviews) > 0) @php $n = 0; @endphp
                                            @foreach($reviews as $row) @php $n++; @endphp <?php // echo 'ssdss<pre>'; print_r($row->prdType); echo '</pre>'; die; ?>
                                                @php if($row['is_active'] == 1){ $active = "Active"; $checked = 'checked'; }else if ($row['is_active'] == 0){ $active = "Inactive"; $checked = ""; }

                                                 @endphp
                                                <tr class="dtrow" id="dtrow-{{$row['id']}}">
                                                    <td><span class="d-none">{{$n}}</span></td>
                                                    <td>{{$row['user']}}</td>
                                                    <td data-search="{{$row['rating']}}">
                                                        <span class="stars" data-rating="{{$row['rating']}}" data-num-stars="5" ></span>
                                                    </td>
                                                    <td >{{$row['rating']}}/5</td>
                                                    <td >{{$row['comment']}}</td>
                                                    <td>
                                            @if($row['image']!='')
                                            @php $comment_img =config('app.storage_url').$row['image'];
                                                                        @endphp
                                            <img alt="Comment Image" class="rounded-circle border p-0" style="width:120px;height:130px;" src="{{ $comment_img  }}">
                                            @else
                                            <img alt="Comment Image" class="rounded-circle border p-0" src="{{ url('storage/app/public/product/default.jpg') }}">
                                            @endif


                                                 </td>
                                                    <td>{{date('d M Y',strtotime($row['created_at']))}}</td>
                                                    <td data-search="{{$active}}">
                                                        <div class="switch">
                                                            <input class="switch-input status-btn" id="status-{{$row['id']}}" type="checkbox" {{$checked}} name="status">
                                                            <label class="switch-paddle" for="status-{{$row['id']}}">
                                                                <span class="switch-active" aria-hidden="true">Active</span>
                                                                <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- <td class="text-center">
                                                       
                                                        <button id="delBtn-{{$row['id']}}" class="mr-2 btn btn-secondary btn-sm delBtn"><i class="fe fe-trash-2 mr-1"></i>Delete</button>
                                                    </td> -->
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
    
</div>
<script src="{{asset('admin/assets/js/datatable/tables/product-reviews-datatable.js')}}"></script> 
                     