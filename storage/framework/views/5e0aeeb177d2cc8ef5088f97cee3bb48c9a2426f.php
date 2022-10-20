<?php 

// echo '<pre>'; dd($product); echo '</pre>';  die; 
// dd($product->name_cnt_id."-".$langId);
if($product){ 
    $prices         =   $product->prdPrice;         $prdAssAttrs    =   $product->assignedAttrs($product->id); // echo '<pre>'; print_r($prices); echo '</pre>';
    $id             =   $product->id;               $sellerId       =   $product->seller_id;        $prdType        =       $product->product_type;
    $catId          =   $product->category_id;      $subCatId       =   $product->sub_category_id;  $brandId        =       $product->brand_id;
    $occasion_id        =       $product->occasion_id; $tagId        =       $product->tag_id;
    $commi          =   $product->commission;       $approved       =   $product->is_approved;      $apprDate       =       $product->approved_at;
    $status         =   $product->is_active;   if(isset($stocks)){ $stock_val = $stocks; }else{ $stock_val = ''; }  $min_order = $product->min_order; $bulk_order =  $product->bulk_order;   if(isset($product->tax)){  $taxId          =   $product->tax->id; }else{ $taxId          =0; }           if(isset($prices)) { $price          =       $prices->price; $sPrice         =   $prices->sale_price; $stDate         =       $prices->sale_start_date; $edDate         =   $prices->sale_end_date;   }else { $price          =      0; $sPrice         =   0; $stDate         =       ""; $edDate         =  "";  } 
    $adminPrd       =   $product->admin_prd_id; $commission       =   $product->commission;  $commi_type       =   $product->commi_type;            
       
    $prdName        =   getContent($product->name_cnt_id,$langId);      $sDesc              =       getContent($product->short_desc_cnt_id,$langId);
    $desc           =   getContent($product->desc_cnt_id,$langId);      $content            =       getContent($product->content_cnt_id,$langId);
     if(isset($product->spec_cnt_id)) { $specification            =       getContent($product->spec_cnt_id,$langId);  }else { $specification = '';   } 
    
    $featured         =   $product->is_featured; $daily_deals         =   $product->daily_deals; $out_of_stock_selling = $product->out_of_stock_selling; $is_comingsoon = $product->is_comingsoon;
    if($adminPrd    >   0){ $sellCkd = false; $adminCkd = true; }else{  $sellCkd = true; $adminCkd  =   false; }
    if(isset($dimensions)){  $weight  =   $dimensions->weight; $length  =   $dimensions->length; $width  =   $dimensions->width; $height  =   $dimensions->height; }else{ 
        $weight  =   $length  =   $width  =   $height  =  ''; } 
}else{ 
    $weight  =   $length  =   $width  =   $height  =  ''; $commission = 0; $commi_type = '%'; $stock_val = $min_order = $bulk_order = 0;
    $adminPrd = $id =   0; $commi = $prdType = $prdName = $catId = $subCatId = $tagId = $brandId = $occasion_id = $sDesc = $desc = $content = $price = $sPrice = $taxId = $stDate = $edDate = $specification = ''; 
    $status         =   1;  $featured   = $daily_deals      = $out_of_stock_selling   = $is_comingsoon = 0; $sellerId = 0; $sellCkd = true; $adminCkd = false; $prdAssAttrs = []; $id = 0;
}
if($prdType == 2)   {   $conficLi = ''; }else{ $conficLi = 'no-disp'; } 
if(isset($variationHist) || (@$product->platform =="odoo" && $product->product_type ==2)){
    
  if( ($product->platform =="odoo" && $product->product_type ==2)){
      $variationHist = [];
      $attr_data = [];
      }else{
          $attr_data = json_decode($variationHist->attr_data);
      }  


$attr_data = $product->ConfigAssignedAttrs($product->id);
$attr_data_names = $product->ConfigAssignedAttrsNames($product->id);

$associate_prds = $product->ConfigAssignedAttrsIds($product->id,$attr_data);
//  $attr_1_cnt = count($attr_data[$attr_data_names[0]]);
 if(isset($attr_data_names[0])) { $attr_1_cnt = count($attr_data[$attr_data_names[0]]); }else{ $attr_1_cnt =0; }
 if(isset($attr_data_names[1])) { $attr_2_cnt = count($attr_data[$attr_data_names[1]]); }else{ $attr_2_cnt =0; }
// dd($associate_prds);
// dd($associate_prds);
// dd(($attr_data[$attr_data_names[0]]));
if($variationHist){
   $price_data = json_decode($variationHist->price_data);
$stock_data = json_decode($variationHist->stock_data);
$sku_data = json_decode($variationHist->sku_data);
$sale_price_data = json_decode($variationHist->sale_price_data);
$sale_from_data = json_decode($variationHist->sale_from_data);
$sale_to_data = json_decode($variationHist->sale_to_data);
$min_order_data = json_decode($variationHist->min_order_data);
$bulk_order_data = json_decode($variationHist->bulk_order_data);
$var_weight = json_decode($variationHist->weight);
$var_length = json_decode($variationHist->length);
$var_width = json_decode($variationHist->width);
$var_height = json_decode($variationHist->height);
$dynamic_prod_names = json_decode($variationHist->dynamic_prod_names);
$assoc_prds = json_decode($variationHist->assoc_prds); 
}else{
    $price_data = [];
$stock_data = [];
$sku_data = [];
$sale_price_data = [];
$sale_from_data = [];
$sale_to_data = [];
$min_order_data = [];
$bulk_order_data = [];
$var_weight = [];
$var_length = [];
$var_width = [];
$var_height = [];
$dynamic_prod_names = [];
$assoc_prds = [];
}

// dd($assoc_prds);
if(isset($attr_data_names)){ if(isset($attr_data_names[0])){ $attr_1_name =  getContent($attr_data_names[0],$langId); }else{ $attr_1_name = ""; }  }else{ $attr_1_name = ""; }
if(isset($attr_data)){ if(isset($attr_data_names[0])){ $attr_1_value = $attr_data[$attr_data_names[0]]; }else{ $attr_1_value = ""; } }else{ $attr_1_value = ""; }
if(isset($attr_data_names)){ if(isset($attr_data_names[1])){ $attr_2_name = getContent($attr_data_names[1],$langId);; }else { $attr_2_name =""; }  }else { $attr_2_name =""; }
if(isset($attr_data) && isset($attr_data_names[1])){ $attr_2_value = $attr_data[$attr_data_names[1]]; }else{ $attr_2_value =""; }
if(isset($attr_data->attr_1_img)){ $attr_1_img = $attr_data->attr_1_img; }else{ $attr_1_img = ""; }
if(isset($attr_data->attr_2_img)){ $attr_2_img = $attr_data->attr_2_img; }else{ $attr_2_img = ""; }
$attr_price = $price_data; $attr_price= json_decode(json_encode($attr_price), true);
$attr_stock= $stock_data; $attr_stock= json_decode(json_encode($attr_stock), true);
$attr_sku = $sku_data; $attr_sku= json_decode(json_encode($attr_sku), true);
$attr_sale_price = $sale_price_data; $attr_sale_price= json_decode(json_encode($attr_sale_price), true);
$attr_sale_from = $sale_from_data; $attr_sale_from= json_decode(json_encode($attr_sale_from), true);
$attr_sale_to = $sale_to_data; $attr_sale_to= json_decode(json_encode($attr_sale_to), true);
$attr_min_ord = $min_order_data; $attr_min_ord= json_decode(json_encode($attr_min_ord), true);
$attr_bulk_ord = $bulk_order_data; $attr_bulk_ord= json_decode(json_encode($attr_bulk_ord), true);
$assoc_prds_ids = $assoc_prds; $assoc_prds_ids= json_decode(json_encode($assoc_prds_ids), true);
$attr_weight= json_decode(json_encode($var_weight), true);
// dd($assoc_prds_ids);
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
$attr_sale_price= "";
$attr_sale_from="";
$attr_sale_to="";
$attr_min_ord="";
$attr_bulk_ord="";
}
?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0"><?php echo e($title); ?></h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Seller Management</a></li>
            <li class="breadcrumb-item"><a href="#" id="bc_list"><i class="fe fe-grid mr-2 fs-14"></i>Product List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
        </ol>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body pb-2">
            <?php echo e(Form::open(array('url' => "admin/product/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true'))); ?>

                 <?php echo e(Form::hidden('id',$id,['id'=>'id'])); ?> 

                <div class="tabs-menu mb-4">
                    <ul class="nav panel-tabs">
                        <li><a href="#tab1" data-toggle="tab" id="nav_tab_1" class="active"><span>General Info.</span></a></li>
                        <li><a href="#tab2" data-toggle="tab" id="nav_tab_2"><span>Product Info.</span></a></li>
                        <li><a href="#tab3" data-toggle="tab" id="nav_tab_3"><span>Media</span></a></li>
                        <!--<li><a href="#tab4" data-toggle="tab" id="nav_tab_4"><span>Attributes</span></a></li>-->
                        <!--<li><a href="#tab5" data-toggle="tab" id="nav_tab_5" class="<?php echo e($conficLi); ?>"><span>Associative Products</span></a></li>-->
                   </ul>
                </div>
                <div class="row panel-body tabs-menu-body">
                    <div class="tab-content col-12">
                        <div class="tab-pane active " id="tab1"><?php echo $__env->make('admin.products.details.general', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                        <div class="tab-pane" id="tab2"><?php echo $__env->make('admin.products.details.price_tax', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                        <div class="tab-pane" id="tab3"><?php echo $__env->make('admin.products.details.image', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                        <!--<div class="tab-pane attr" id="tab4"><?php echo $__env->make('admin.products.details.attribute', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>-->
                        <!--<div class="tab-pane asso" id="tab5"><?php if($prdType == 2 && $id > 0): ?> <?php echo $__env->make('admin.products.details.associative_prds', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <?php endif; ?></div>-->
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-footer text-right">
                        <?php echo e(Form::hidden('can_submit',0,['id'=>'can_submit'])); ?>

                        <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="save_btn" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
           <?php echo e(Form::close()); ?>

        </div>
    </div>
</div>
<!-- INTERNAL WYSIWYG Editor js -->
<script src="<?php echo e(URL::asset('admin/assets/js/form-editor.js')); ?>"></script>
		

<?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/products/details.blade.php ENDPATH**/ ?>