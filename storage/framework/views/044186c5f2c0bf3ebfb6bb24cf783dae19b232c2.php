<div class="tab-pane active " id="tab1">
    <div class="card-header mb-4""><div class="card-title">General Information</div></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            <label class="form-label view" for="fname">Seller: </label>
                    <p class="view_value"><?php echo e($seller->fname); ?> </p>
      
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">

            <label class="form-label view" for="fname">Language: </label>
            <?php
            $def_lang =DB::table('glo_lang_lk')->where('is_active', 1)->first();
            $content_table=DB::table('cms_content')->where('cnt_id', $product->name_cnt_id)->first();
            if($content_table){ 
            $lang_id = $content_table->lang_id;
            }
            ?>
            <p class="view_value"> <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang=>$lv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($lang_id==$lang): ?> <?php echo e($lv); ?> <?php endif; ?> 
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </p>
        </div>
    </div>
   <div class="clr"></div>
    <div id="seller_div" class="col-lg-6 fl">
        <div class="form-group">
           <label class="form-label view" for="fname">Product Name: </label>
                    <p class="view_value"><?php echo e($product->name); ?> </p>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            <label class="form-label view" for="fname">Category: </label>

            <p class="view_value">  <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat_id=>$cat_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($product->category_id==$cat_id){ echo $cat_name ;}?> 
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </p>
            
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            <label class="form-label view" for="fname">Sub Category: </label>

            <p class="view_value">  <?php $__currentLoopData = $sub_cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat_id=>$cat_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($product->sub_category_id==$cat_id){ echo $cat_name ;}?> 
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </p>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">

            <label class="form-label view" for="fname">Brand: </label>

            <p class="view_value">  <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bnd_id=>$bnd_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($product->brand_id==$bnd_id){ echo $bnd_name ;}?> 
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </p>
        </div>
    </div><div class="clr"></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
        
           <label class="form-label view" for="fname">Short Description: </label>
            <p class="view_value"><?php echo e(getContent($product->short_desc_cnt_id)); ?> </p>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">

             <?php  if($product->is_active == 1){ $active = "Active";  }else if ($product->is_active == 0){ $active = "Inactive";  } ?>
<label class="form-label view" >Status: </label>
            <p class="view_value"><?php echo e($active); ?> </p>
        </div>
    </div><div class="clr"></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            
             <label class="form-label view" for="fname">Long Description: </label>
            <p class="view_value"><?php echo getContent($product->desc_cnt_id,2); ?> </p>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
  
             <label class="form-label view" for="fname">Content: </label>
            <p class="view_value"><?php echo getContent($product->content_cnt_id); ?> </p>
        </div>
    </div>
</div>
                        <?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/products/view/general.blade.php ENDPATH**/ ?>