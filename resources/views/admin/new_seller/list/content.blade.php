<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
    <div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
        <label class="form-label mr-2" for="filterSel">Filter </label>
        {{Form::select('active',[0=>'Pending',2=>'Denied'],$active,['id'=>'active_filter','class'=>'form-control mr-4','placeholder'=>'All Status'])}}
        <div class="btn btn-list">
            
        </div>
    </div>
</div>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                        <div class="e-table">
                            <div class="table-responsive table-lg mt-3">
                                    <table id="attribute" class="seller-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport"></th>
                                            <th class="wd-15p">Business Name</th>
                                            <th class="wd-15p">Store Name</th>
                                            <th class="wd-15p">Email Id</th>
                                            <th class="wd-20p">Phone</th>
                                            <th class="wd-10p">Created On</th>
                                            <th class="wd-25p notexport">Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($sellers && count($sellers) > 0) @php $n = 0; @endphp
                                            @foreach($sellers as $row) @php $n++; @endphp
                                                @php if($row->is_approved == 0){ $status = "Pending"; $sclass = 'warning'; }else{ $status = "Denied"; $sclass = "error"; } @endphp
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></td>
                                                   <td>{{$row->store($row->seller_id)->business_name}}</td> 
                                                   <td>{{$row->store($row->seller_id)->store_name}}</td> 
                                                    <td>@if($row->sellerMst->teleEmail) {{$row->sellerMst->teleEmail->value}} @endif</td>
                                                    <td>@if($row->sellerMst->isd_code) {{ $row->sellerMst->isd_code }} @endif @if($row->sellerMst->telePhone) {{$row->sellerMst->telePhone->value}} @endif</td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td><span class="badge badge-{{$sclass}}">{{$status}}</span></td>
                                                    <td class="text-center">
                                                        
                                                        <button id="editBtn-{{$row->sellerMst->id}}" class="mr-2 btn btn-success btn-sm editBtn"><i class="fa fa-eye mr-1"></i>View</button>
                                                        <button id="delBtn-{{$row->sellerMst->id}}" class="mr-2 btn btn-secondary btn-sm delBtn"><i class="fa fa-trash mr-1"></i>Delete</button>
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

    <script src="{{asset('admin/assets/js/datatable/tables/new_seller-datatable.js')}}"></script>