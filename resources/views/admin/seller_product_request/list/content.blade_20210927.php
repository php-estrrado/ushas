<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Products</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>

</div>
<div id="filters" class="">
    <div class="row" id="filterrow"><div class="plus-minus-toggle collapsed"><p>Filters</p></div></div>
    <div class="row no-disp mb-4 " id="filtersec">
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('active','Status',['class'=>'text-white'])}}
                {{Form::select('active',[1=>'Active',0=>'Inactive'],$active,['id'=>'active_filter','class'=>'form-control mr-4 active_filters','placeholder'=>'All Status'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('sellers','Seller',['class'=>'text-white'])}}
                {{Form::select('sellers',$sellers,$seller,['id'=>'seller','class'=>'form-control mr-4 active_filters','placeholder'=>'All Sellers'])}}
            </div>
        </div><div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('category','Category',['class'=>'text-white'])}}
                {{Form::select('category',$categories,$category,['id'=>'category','class'=>'form-control mr-4 active_filters','placeholder'=>'All Categories'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('subcategory','Sub Category',['class'=>'text-white'])}}
                {{Form::select('subcategory',$sub_cats,$subcategory,['id'=>'subcategory','class'=>'form-control mr-4 active_filters','placeholder'=>'All Sub Categories'])}}
            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div class="card-body">
                        <div id="table_body" class="card-body table-card-body">
                            <div>
                                <table id="product" class="product-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p notexport"></th>
                                            <th class="wd-15p">Product Name</th>
                                            <th class="wd-15p">Seller Name</th>
                                            <th class="wd-15p">Category</th>
                                            <th class="wd-20p">Sub Category</th>
                                            <th class="wd-15p">Type</th>
                                            <th class="wd-15p">Price ({{getCurrency()->name}})</th>
                                            <th class="wd-10p">Created On</th>
                                            <th class="wd-25p notexport">Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @if($products && count($products) > 0) @php $n = 0; @endphp
                                            @foreach($products as $row) @php $n++; @endphp <?php // echo 'ssdss<pre>'; print_r($row->prdType); echo '</pre>'; die; ?>
                                                @php if($row->is_active == 1){ $active = "Active"; $checked = 'checked'; }else if ($row->is_active == 0){ $active = "Inactive"; $checked = ""; } @endphp
                                                <tr class="dtrow" id="dtrow-{{$row->id}}">
                                                    <td><span class="d-none">{{$n}}</span></td>
                                                    <td>{{$row->name}}</td>
                                                    <td>{{$row->seller->fname}}</td>
                                                    <td>{{$row->category->cat_name}}</td>
                                                    <td>{{$row->subCategory->subcategory_name}}</td>
                                                    <td>{{$row->prdType->type_name}}</td>
                                                    <td>@if($row->prdPrice) {{$row->prdPrice->price}} @endif</td>
                                                    <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                                    <td>
                                                        <div class="switch">
                                                            <input class="switch-input status-btn" id="status-{{$row->id}}" type="checkbox" {{$checked}} name="status">
                                                            <label class="switch-paddle" for="status-{{$row->id}}">
                                                                <span class="switch-active" aria-hidden="true">Active</span>
                                                                <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button id="editBtn-{{$row->id}}" class="mr-2 btn btn-info btn-sm editBtn"><i class="fa fa-edit mr-1"></i><span>Edit</span></button>
                                                        <button id="approve-{{$row->id}}" class="mr-2 btn btn-success btn-sm approve"><i class="fa fa-check mr-1"></i>Approve</button>
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
 <script src="{{asset('admin/assets/js/datatable/tables/seller_product_request-datatable.js')}}"></script>

    