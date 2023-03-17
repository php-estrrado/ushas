<?php  //  echo '<pre>'; print_r($product); echo '</pre>';  die;
if($product){ 
    $prices         =   $product->prdPrice;         $prdAssAttrs    =   $product->assignedAttrs($product->id); // echo '<pre>'; print_r($prices); echo '</pre>';
    $id             =   $product->id;               $sellerId       =   $product->seller_id;        $prdType        =       $product->product_type;
    $catId          =   $product->category_id;      $subCatId       =   $product->sub_category_id;  $brandId        =       $product->brand_id;
    $commi          =   $product->commission;       $approved       =   $product->is_approved;      $apprDate       =       $product->approved_at;
    $status         =   $product->is_active;      if(isset($product->tax)){  $taxId          =   $product->tax->id; }else{ $taxId          =0; }           if(isset($prices)) { $price          =       $prices->price; $sPrice         =   $prices->sale_price; $stDate         =       $prices->sale_start_date; $edDate         =   $prices->sale_end_date;   }else { $price          =      0; $sPrice         =   0; $stDate         =       ""; $edDate         =  "";  } 
    $adminPrd       =   $product->admin_prd_id;             
       
    $prdName        =   getContent($product->name_cnt_id,$langId);      $sDesc              =       getContent($product->short_desc_cnt_id,$langId);
    $desc           =   getContent($product->desc_cnt_id,$langId);      $content            =       getContent($product->content_cnt_id,$langId);
     if(isset($product->spec_cnt_id)) { $specification            =       getContent($product->spec_cnt_id,$langId);  }else { $specification = '';   } 
    
    $featured         =   $product->is_featured; $daily_deals         =   $product->daily_deals; 
    if($adminPrd    >   0){ $sellCkd = false; $adminCkd = true; }else{  $sellCkd = true; $adminCkd  =   false; }
}else{ 
    $adminPrd = $id =   0; $commi = $prdType = $prdName = $catId = $subCatId = $brandId = $sDesc = $desc = $content = $price = $sPrice = $taxId = $stDate = $edDate = $specification = ''; 
    $status         =   1;  $featured   = $daily_deals      = 0; $sellerId = $seller->seller_id; $sellCkd = true; $adminCkd = false; $prdAssAttrs = []; $id = 0;
}
if($prdType == 2)   {   $conficLi = ''; }else{ $conficLi = 'no-disp'; } 

?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0"><?php echo e($title); ?></h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Product Management</a></li>
            <li class="breadcrumb-item"><a href="#" id="bc_list"><i class="fe fe-grid mr-2 fs-14"></i>Product List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
        </ol>
    </div>
</div>
<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body pb-2">
            <?php echo e(Form::open(array('url' => "my-products/product/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true'))); ?>

                 <?php echo e(Form::hidden('id',0,['id'=>'id'])); ?> 

                <div class="tabs-menu mb-4">
                    <ul class="nav panel-tabs">
                        <li><a href="#tab1" data-toggle="tab" id="nav_tab_1" class="active"><span>General Info.</span></a></li>
                        <!-- <li><a href="#tab2" data-toggle="tab" id="nav_tab_2"><span>Price & Tax</span></a></li> -->
                        <li><a href="#tab3" data-toggle="tab" id="nav_tab_3"><span>Media</span></a></li>
                        <!--<li><a href="#tab4" data-toggle="tab" id="nav_tab_4"><span>Attributes</span></a></li>-->
                        <!--
                        <li><a href="#tab5" data-toggle="tab" id="nav_tab_5"><span>Bank Details</span></a></li>-->
                        <li><a href="#tab6" data-toggle="tab" id="nav_tab_4"><span>Reviews</span></a></li>
                   </ul>
                </div>
                <div class="row panel-body tabs-menu-body">
                    <div class="tab-content col-12">
                        <?php echo $__env->make('admin.products.view.general', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <!-- <?php echo $__env->make('admin.products.view.price_tax', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> -->
                        <?php echo $__env->make('admin.products.view.image', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <!--<?php echo $__env->make('admin.products.view.attribute', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->
                         <?php echo $__env->make('admin.products.view.reviews', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-footer text-right">
                        <?php echo e(Form::hidden('can_submit',0,['id'=>'can_submit'])); ?>

                        <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                        <!--<button id="save_btn" type="submit" class="btn btn-primary">Save</button>-->
                    </div>
                </div>
           <?php echo e(Form::close()); ?>

        </div>
    </div>
</div>
<!-- INTERNAL WYSIWYG Editor js -->
<script src="<?php echo e(URL::asset('admin/assets/js/form-editor.js')); ?>"></script>
        

<?php /**PATH /home/qaushas/public_html/resources/views/admin/products/view.blade.php ENDPATH**/ ?>