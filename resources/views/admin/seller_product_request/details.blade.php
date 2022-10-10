<?php   // echo '<pre>'; print_r($product); echo '</pre>';  die;
if($product){ 
    $prices         =   $product->prdPrice;         $prdAssAttrs    =   $product->assignedAttrs($product->id); // echo '<pre>'; print_r($prices); echo '</pre>';
    $id             =   $product->id;               $sellerId       =   $product->seller_id;        $prdType        =       $product->product_type;
    $fixedprice         =   $product->fixed_price; 
    $catId          =   $product->category_id;      $subCatId       =   $product->sub_category_id;  $brandId        =       $product->brand_id; $tagId        =       $product->tag_id;
    $commi          =   $product->commission;       $approved       =   $product->is_approved;      $apprDate       =       $product->approved_at;
    $status         =   $product->is_active;      if(isset($product->tax)){  $taxId          =   $product->tax->id; }else{ $taxId          =0; }           if(isset($prices)) { $price          =       $prices->price; $sPrice         =   $prices->sale_price; $stDate         =       $prices->sale_start_date; $edDate         =   $prices->sale_end_date;   }else { $price          =      0; $sPrice         =   0; $stDate         =       ""; $edDate         =  "";  } 
    $adminPrd       =   $product->admin_prd_id;             
       $commission       =   $product->commission;  $commi_type       =   $product->commi_type;
    $prdName        =   getContent($product->name_cnt_id,$langId);      $sDesc              =       getContent($product->short_desc_cnt_id,$langId);
    $desc           =   getContent($product->desc_cnt_id,$langId);      $content            =       getContent($product->content_cnt_id,$langId);
     if(isset($product->spec_cnt_id)) { $specification            =       getContent($product->spec_cnt_id,$langId);  }else { $specification = '';   } 
    
    $featured         =   $product->is_featured; $daily_deals         =   $product->daily_deals; $out_of_stock_selling = $product->out_of_stock_selling;
    if($adminPrd    >   0){ $sellCkd = false; $adminCkd = true; }else{  $sellCkd = true; $adminCkd  =   false; }
    if(isset($dimensions)){  $weight  =   $dimensions->weight; $length  =   $dimensions->length; $width  =   $dimensions->width; $height  =   $dimensions->height; }else{ 
        $weight  =   $length  =   $width  =   $height  =  ''; } 
}else{ 
    $weight  =   $length  =   $width  =   $height  =  ''; $commission = 0; $commi_type = '%'; $fixedprice         =  0;
    $adminPrd = $id =   0; $commi = $prdType = $prdName = $catId = $subCatId = $tagId = $brandId = $sDesc = $desc = $content = $price = $sPrice = $taxId = $stDate = $edDate = $specification = ''; 
    $status         =   1;  $featured   = $daily_deals      = $out_of_stock_selling    = 0; $sellerId = $seller->seller_id; $sellCkd = true; $adminCkd = false; $prdAssAttrs = []; $id = 0;
}
$weight  =$product->weight;
if($prdType == 2)   {   $conficLi = ''; }else{ $conficLi = 'no-disp'; } 
if(isset($variationHist)){
$attr_data = json_decode($variationHist->attr_data);

$price_data = json_decode($variationHist->price_data);
$stock_data = json_decode($variationHist->stock_data);
$sku_data = json_decode($variationHist->sku_data);
$var_weight = json_decode($variationHist->weight);
$var_length = json_decode($variationHist->length);
$var_width = json_decode($variationHist->width);
$var_height = json_decode($variationHist->height);
$dynamic_prod_names = json_decode($variationHist->dynamic_prod_names);

if(isset($attr_data)){ $attr_1_name = $attr_data->attr_1; }else{ $attr_1_name = ""; }
if(isset($attr_data)){ $attr_1_value = $attr_data->attr_1_value; }else{ $attr_1_value = ""; }
if(isset($attr_data->attr_2)){ $attr_2_name = $attr_data->attr_2; }else { $attr_2_name =""; }
if(isset($attr_data->attr_2_value)){ $attr_2_value = $attr_data->attr_2_value; }else{ $attr_2_value =""; }
if(isset($attr_data->attr_1_img)){ $attr_1_img = $attr_data->attr_1_img; }else{ $attr_1_img = ""; }
if(isset($attr_data->attr_2_img)){ $attr_2_img = $attr_data->attr_2_img; }else{ $attr_2_img = ""; }
$attr_price = $price_data; $attr_price= json_decode(json_encode($attr_price), true);
$attr_stock= $stock_data; $attr_stock= json_decode(json_encode($attr_stock), true);
$attr_sku = $sku_data; $attr_sku= json_decode(json_encode($attr_sku), true);
$attr_weight= json_decode(json_encode($var_weight), true);
// dd($variationHist);
$attr_length= json_decode(json_encode($var_length), true);
$attr_width= json_decode(json_encode($var_width), true);
$attr_height = json_decode(json_encode($var_height), true);

$var_list = $dynamic_prod_names;

}else{
  $attr_1_name = "";
$attr_1_value = "";
$attr_2_name = "";
$attr_2_value = "";
$attr_price = "";
$attr_stock= "";
$attr_sku = "";
$attr_weight = "";
$attr_length = "";
$attr_width = "";
$attr_height = "";
$var_list = ""; 
$attr_1_img = "";
$attr_2_img = ""; 
}
?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item"><a href="#" id="bc_list"><i class="fe fe-grid mr-2 fs-14"></i>Product List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body pb-2">
            {{ Form::open(array('url' => "admin/seller/product/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true')) }}
                 {{Form::hidden('id',$id,['id'=>'id'])}} 

               <div class="tabs-menu mb-4">
                    <ul class="nav panel-tabs">
                        <li><a href="#tab1" data-toggle="tab" id="nav_tab_1" class="active"><span>General Info.</span></a></li>
                        
                        <li><a href="#tab3" data-toggle="tab" id="nav_tab_3"><span>Media</span></a></li>
                        <li><a href="#tab4" data-toggle="tab" id="nav_tab_4" style="display: none;"><span>Extra Fields</span></a></li>
                        <li><a href="#tab2" data-toggle="tab" id="nav_tab_2"><span>Product Info.</span></a></li>
                        <!--<li><a href="#tab4" data-toggle="tab" id="nav_tab_4"><span>Attributes</span></a></li>-->
                        <!--<li><a href="#tab5" data-toggle="tab" id="nav_tab_5" class="{{$conficLi}}"><span>Associative Products</span></a></li>-->
                   </ul>
                </div>
                <div class="row panel-body tabs-menu-body">
                    <div class="tab-content col-12">
                        <div class="tab-pane active " id="tab1">@include('admin.seller_product.details.general')</div>
                        <div class="tab-pane" id="tab2">@include('admin.seller_product.details.price_tax')</div>
                        <div class="tab-pane" id="tab3">@include('admin.seller_product.details.image')</div>
                        <div class="tab-pane attr" id="tab4">@include('admin.seller_product.details.attribute')</div>
                        <!--<div class="tab-pane attr" id="tab4">@include('admin.seller_product.details.attribute')</div>-->
                        <!--<div class="tab-pane asso" id="tab5">@if($prdType == 2 && $id > 0) @include('admin.seller_product.details.associative_prds') @endif</div>-->
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-footer text-right">
                        {{Form::hidden('can_submit',0,['id'=>'can_submit'])}}
                        <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="save_btn" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
           {{Form::close()}}
        </div>
    </div>
</div>
<!-- INTERNAL WYSIWYG Editor js -->
<script src="{{URL::asset('admin/assets/js/form-editor.js')}}"></script>
		

