<?php

 if(isset($lang_id)) {
	$default_langs =DB::table('glo_lang_lk')->where('id', $lang_id)->first();
	$lang_name=	$default_langs->glo_lang_name;
	$lang_id=$lang_id;
	
}
else{
$default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
$lang_id=$default_lang->id;
$lang_name=$default_lang->glo_lang_name;
}

$category_name=DB::table('cms_content')->where('cnt_id', $category->cat_name_cid)->where('lang_id', $lang_id)->first();
$category_desc=DB::table('cms_content')->where('cnt_id', $category->cat_desc_cid)->where('lang_id', $lang_id)->first(); 
if($category_name){
$cat_name=$category_name->content;
}else{
$cat_name="";
}
if($category_desc){
$category_desc=$category_desc->content;
}else{
$category_desc="";
}

?>
<div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label"><?php echo e($lang_name); ?> Category Name <span class="text-red">*</span></label>
                                                                <input type="text" class="form-control <?php $__errorArgs = ['category_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Category" name="category_name" value="<?php echo e($cat_name); ?>">
                                                            <?php $__errorArgs = ['category_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label"><?php echo e($lang_name); ?> Category Description <span class="text-red">*</span></label>
                                                                 <textarea class="form-control <?php $__errorArgs = ['category_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Description"  name="category_description"><?php echo e($category_desc); ?></textarea>
                                                           <?php $__errorArgs = ['category_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong><?php echo e($message); ?></strong>
                                                                    </span>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div><?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/master/includes/content.blade.php ENDPATH**/ ?>