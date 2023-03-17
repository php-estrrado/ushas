<?php
$default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
 if(isset($lang_id)) {
$selected_lang =	 DB::table('glo_lang_lk')->where('id', $lang_id)->first();
$lang_name=$selected_lang->glo_lang_name;
	$getlang_id=$lang_id;
	$lang_id=$lang_id;
}
else{
$lang_name=$default_lang->glo_lang_name;
$lang_id=$default_lang->id;
}
$subcategory_name=DB::table('cms_content')->where('cnt_id', $subcategory->sub_name_cid)->where('lang_id', $lang_id)->first();
if($subcategory_name){
$subname=$subcategory_name->content;
}else{
	$subname='';
}
if($subcategory->desc_cid){
$subcategory_desc=DB::table('cms_content')->where('cnt_id', $subcategory->desc_cid)->where('lang_id', $lang_id)->first();
if($subcategory_desc){
$desc=$subcategory_desc->content;
}else{
	$desc='';
}
}else{$desc='';} 
if(isset($getlang_id)){ 

if($default_lang->id==$getlang_id){

?>
<div class="col-md-12">
    <div class="form-group">
        <label class="form-label"><?php echo e($lang_name); ?> Subcategory Name <span class="text-red">*</span></label>
        <?php echo e(Form::select('sub_category_name',$subcategories_list,$subcategory->sabcatlist_id,['id'=>'sub_category_name','class'=>'form-control','placeholder'=>'Select Subcategory'])); ?>

        <input type="hidden" name="id" id="curent_subid" value="<?php echo e($subcategory->parent); ?>" />
        <input type="hidden" name="id" id="curent_subid1" value="0" />
        <input type="hidden" name="lang_sub_category_name" id="lang_sub_category_name" value="<?php echo e($subname); ?>" />
        <?php $__errorArgs = ['sub_category_name'];
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
<?php } else{ 
?>
<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Subcategory Name (Default) <span class="text-red">*</span></label>
        <?php echo e(Form::select('sub_category_name',$subcategories_list,$subcategory->sabcatlist_id,['id'=>'sub_category_name','class'=>'form-control','placeholder'=>'Select Subcategory','disabled' => 'disabled'])); ?>

        <input type="hidden" name="sub_category_name" id="sub_category_name" value="<?php echo e($subcategory->sabcatlist_id); ?>" />
        <input type="hidden" name="id" id="curent_subid" value="<?php echo e($subcategory->parent); ?>" />
        <input type="hidden" name="id" id="curent_subid1" value="0" />
        <input type="hidden" name="lang_sub_category_name" id="lang_sub_category_name" value="<?php echo e($subname); ?>" />
		<?php $__errorArgs = ['sub_category_name'];
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
    <label class="form-label"><?php echo e($lang_name); ?> Subcategory Name <span class="text-red">*</span></label>
    <input type="text" class="form-control <?php $__errorArgs = ['sub_category_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Sub category" name="lang_sub_category_name" value="<?php echo e($subname); ?>">
         <?php $__errorArgs = ['sub_category_name'];
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
<?php } }else{ ?>
<div class="col-md-12">
    <div class="form-group">
        <label class="form-label"><?php echo e($lang_name); ?> Subcategory Name <span class="text-red">*</span></label>
        <?php echo e(Form::select('sub_category_name',$subcategories_list,$subcategory->sabcatlist_id,['id'=>'sub_category_name','class'=>'form-control','placeholder'=>'Select Subcategory'])); ?>

        <input type="hidden" name="id" id="curent_subid" value="<?php echo e($subcategory->parent); ?>" />
        <input type="hidden" name="id" id="curent_subid1" value="0" />
        <input type="hidden" name="lang_sub_category_name" id="lang_sub_category_name" value="<?php echo e($subname); ?>" />
		<?php $__errorArgs = ['sub_category_name'];
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
<?php } ?>

                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label"><?php echo e($lang_name); ?> Subcategory Description <span class="text-red"></span></label>
                                                                <input type="text" class="form-control" placeholder="Description" name="subcategory_description" value="<?php echo e($desc); ?>">
                                                            </div>
                                                        </div>
                                                        
                                                         <input type="hidden" value="<?php echo e($subcategory->is_active); ?>" name="status"><?php /**PATH /home/qaushas/public_html/resources/views/admin/master/includes/subcat_content.blade.php ENDPATH**/ ?>