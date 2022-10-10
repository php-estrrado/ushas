<style> 
    .card-body.settle{ max-width: 750px; margin: 0 auto; }  
</style>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item"><a href="#" id="bc_list"><i class="fe fe-grid mr-2 fs-14"></i>Seller Earnings</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>

<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div class="card-body settle">
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                <table id="settlement" class="settlement-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport">Select</th>
                                            <th class="wd-15p">Date</th>
                                            <th class="wd-10p">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($settlements && count($settlements) > 0) @php $n = 0; @endphp
                                            @foreach($settlements as $row) @php $n++; @endphp
                                                @php 
                                                @endphp
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></span></td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td>{{ getCurrency()->name }} {{$row->amount}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                </table>
                                {{ csrf_field() }}

                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card-footer text-right">
                                <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{URL::asset('admin/assets/js/datatable/tables/settlement-datatable.js')}}"></script>

