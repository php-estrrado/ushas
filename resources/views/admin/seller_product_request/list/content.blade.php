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
                {{Form::label('sellers','Seller',['class'=>'text-white'])}}
                {{Form::select('sellers',$sellers,'',['id'=>'seller','class'=>'form-control mr-4 active_filters','placeholder'=>'All Sellers'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('category','Category',['class'=>'text-white'])}}
                {{Form::select('category',$categories,'',['id'=>'category','class'=>'form-control mr-4 active_filters','placeholder'=>'All Categories'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('subcategory','Sub Category',['class'=>'text-white'])}}
                {{Form::select('subcategory',$subCats,'',['id'=>'subCats','class'=>'form-control mr-4 active_filters','placeholder'=>'All Sub Categories'])}}
            </div>
        </div>
        <div class="col-3 fl">
            <div class="page-filters">
                {{Form::label('active','Approval Status',['class'=>'text-white'])}}
                {{Form::select('status',[10=>'Approved',0=>'Pending'],$active,['id'=>'status_filter','class'=>'form-control mr-4 active_filters','placeholder'=>'All Status'])}}
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
                                            <th class="wd-15p">Business Name</th>
                                            <th class="wd-15p">Seller</th>
                                            <th class="wd-15p">Category</th>
                                            <th class="wd-20p">Sub Category</th>
                                            <th class="wd-10p">Created On</th>
                                            <th class="wd-25p notexport">Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> 

                                    </tbody>

                                </table>
                                {{Form::hidden('baseurl',url('/'),['id'=>'baseurl'])}} {{ csrf_field() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    