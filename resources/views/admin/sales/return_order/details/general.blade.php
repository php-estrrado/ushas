<div class="tab-pane active " id="tab1">
    <div class="card-header mb-4""><div class="card-title">General Information</div></div>
    <div class="col-lg-6 fl d-none">
        <div class="form-group">
            {{Form::label('seller_id','Seller',['class'=>''])}} <span class="text-red">*</span>
            {{Form::label('seller',$seller->fname, ['class'=>'form-control'])}} {{Form::hidden('seller_id',$seller->seller_id,['id'=>'seller_id'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl d-none">
        <div class="form-group">
            {{Form::label('lang_id','Language',['class'=>''])}} <span class="text-red">*</span>
            {{Form::select('lang_id',$languages,'',['id'=>'lang_id','class'=>'form-control'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="clr"></div>
    <div id="filter_div" class="col-lg-6 fl d-none">
        <div class="form-group">
            {{Form::label('opt_type','Choose Option',['class'=>''])}} <span class="text-red">*</span>
            <div class="col-12">
            
            <label class="custom-control custom-radio custom-control-md col-md-6 fl">
                    <input type="radio" class="custom-control-input cus_radio" name="prd_option" id="option2a" value="option2"  checked="">
                    <span class="custom-control-label custom-control-label-md">Select From Admin</span>
            </label>
            </div><div class="clr"></div>
        </div>
    </div><div class="clr"></div>
    <div id="admin_div" class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('admin_prd_id','Select Admin Product',['class'=>''])}} <span class="text-red">*</span>
            {{Form::select('admin_prd_id',[$product->id => $product->name],$product->id,['id'=>'admin_prd_id','class'=>'form-control'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('category_id','Category',['class'=>''])}} <span class="text-red">*</span>
            {{Form::select('prd[category_id]',[$product->category_id => $product->category->cat_name],$product->category->id,['id'=>'category_id','class'=>'form-control admin'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('sub_category_id','Sub Category',['class'=>''])}} <span class="text-red">*</span>
            {{Form::select('prd[sub_category_id]',[$product->subCategory->id => $product->subCategory->subcategory_name],$product->subCategory->id,['id'=>'sub_category_id','class'=>'form-control admin'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('brand_id','Brand',['class'=>''])}} 
            @if($product->brand_id != NULL)
            {{Form::select('prd[brand_id]',[$product->brand_id => $product->brand->name],$product->brand_id,['id'=>'brand_id','class'=>'form-control'])}}
            @else
            {{Form::select('prd[brand_id]',$brands,$product->brand_id,['id'=>'brand_id','class'=>'form-control admin', 'placeholder'=>'Select Brand'])}}
            @endif
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('short_desc','Short Description',['class'=>''])}} <span class="text-red">*</span>
            {{Form::textarea('prd[short_desc]',$product->short_desc,['id'=>'short_desc','class'=>'form-control','rows'=>2])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('is_active','Status',['class'=>''])}} 
            {{Form::select('prd[is_active]',[1=>'Active',0=>'Inactive'],1,['id'=>'is_active','class'=>'form-control'])}}
            <span class="error"></span>
        </div>
    </div><div class="clr"></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('desc','Description',['class'=>''])}}
            {{Form::textarea('prd[desc]',$product->desc,['id'=>'desc','class'=>'form-control content'])}}
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('content','Content',['class'=>''])}} 
            {{Form::textarea('prd[content]',$product->content,['id'=>'content','class'=>'form-control content'])}}
        </div>
    </div>
</div>
                        