<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Customer</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div id="data-content" class="card-body">
                       
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                            <table id="customer-request" class="customer-request table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport"></th>
                                            <th class="wd-15p">Customer ID</th>
                                            <th class="wd-15p">Customer Name</th>
                                            <th class="wd-15p">Email ID</th>
                                            <th class="wd-20p">Contact Number</th>
                                            <th class="wd-10p">Created On</th>
                                            <th class="wd-10p">Approval Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if($customer && count($customer) > 0) @php $n = 0; @endphp
                                            @foreach($customer as $row) @php $n++; @endphp 
                                    <tr>    
                                        <td class="align-middle select-checkbox"></td>
                                        <td>{{date('dmy',strtotime($row->created_at)).$row->id}}</td>
                                        <td>{{@$row->info->first_name}} {{@$row->info->middle_name}} {{@$row->info->last_name}}</td>
                                        <td>{{$row->custEmail($row->email)}}</td>
                                        <td>{{$row->custPhone($row->phone)}}</td>
                                        <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                        <td>@if($row->is_approved==0)<span class="badge badge-default">Pending</span>@endif
                                            @if($row->is_approved==2)
                                            <span class="badge badge-danger">Rejected</span>@endif</td>
                                        <td><button type="button" class="mr-2 btn btn-info btn-sm viewDtl" id="viewDtl-{{$row->id}}"><i class="fe fe-eye mr-1"></i>View</button>
                                        @if($row->is_approved==0)    
                                        <button type="button" class="mr-2 btn btn-success btn-sm approveuser" id="approveuser-{{$row->id}}"><i class="fe fe-check mr-1"></i>Approve</button>
                                        <button type="button" class="mr-2 btn btn-danger btn-sm rejectusr" id="rejectusr-{{$row->id}}"><i class="fa fa-ban mr-1"></i>Reject</button>
                                        @endif
                                    </td>
                                            
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



 