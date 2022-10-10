<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Product Management</a></li>
            <li class="breadcrumb-item"><a id="bc_list" href="">Admin Product List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="p-4">
                    <?php // echo '<pre>'; print_r($product); echo '</pre>'; die; ?>
                <div class="col-md-6 fl">
                    <div class="form-group">
                        <div class="text-muted">Product Name</div><div class="font-weight-bold">{{$product->name}}</div>
                    </div>
                </div>
                <div class="col-md-6 fl">
                    <div class="form-group">
                        <div class="text-muted">Category</div><div class="font-weight-bold">{{$product->category->cat_name}}</div>
                    </div>
                </div>
                <div class="col-md-6 fl">
                    <div class="form-group">
                        <div class="text-muted">Sub Category</div><div class="font-weight-bold">{{$product->subCategory->subcategory_name}}</div>
                    </div>
                </div>
                <div class="col-md-6 fl">
                    <div class="form-group">
                        <div class="text-muted">Brand</div><div class="font-weight-bold"> @if($product->brand_id != NULL) {{$product->brand->name}} @else -- @endif</div>
                    </div>
                </div>
                <div class="col-md-6 fl">
                    <div class="form-group">
                        <div class="text-muted">Short Description</div><div class="font-weight-bold">{{$product->short_desc}}</div>
                    </div>
                </div>
                <div class="col-md-6 fl">
                    <div class="form-group">
                        <div class="text-muted">Description</div><div class="font-weight-bold">{!! $product->desc !!}</div>
                    </div>
                </div>
                <div class="col-md-6 fl">
                    <div class="form-group">
                        <div class="text-muted">Content</div><div class="font-weight-bold">{!! $product->content !!}</div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="col-lg-12">
                    <div class="card-footer text-right">
                        <button id="cancel_btn" type="button" class="btn btn-secondary">Back</button>
                        @if(!$isAdded) <button id="add_btn" data-val="{{$product->id}}" type="button" class="btn btn-primary">Add To My Products</button> @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
