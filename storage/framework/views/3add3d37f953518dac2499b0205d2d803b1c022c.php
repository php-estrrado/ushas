<div class="card-header mb-4"><div class="card-title">Attributes</div></div>
    <?php // echo '<pre>'; print_r($attributes); echo '</pre>'; ?>
    <?php if($attributes && count($attributes) > 0): ?>
    <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($attr->required == 1){ $req = 'required'; }else{ $req = ''; } ?>
    <div class="col-lg-6 ">
        <div class="form-group">
            <h6><?php echo e($attr->name); ?></h6> 
            <?php if($attr->values && count($attr->values) > 0): ?>
                <?php echo e(Form::hidden('attr['.$attr->id.'][value]',NULL)); ?>

                <?php if($attr->type == 'dropdown'): ?>
                <select name="attr[<?php echo e($attr->id); ?>][valId]" id="attr_<?php echo e($attr->id); ?>" class="form-control <?php echo e($req); ?>">
                    <option value="">Select <?php echo e($attr->name); ?></option>
                    <?php $__currentLoopData = $attr->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val->id); ?>" <?php if(isset($prdAssAttrs[$attr->id]) && $prdAssAttrs[$attr->id]->attr_val_id == $val->id): ?> selected="selected" <?php endif; ?>><?php echo e($val->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                
                <?php elseif($attr->type == 'radio'): ?>
                    <?php $__currentLoopData = $attr->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label for="attr_<?php echo e($val->id); ?>" class="custom-control custom-radio mr-4 fl">
                            <input type="radio" class="custom-control-input <?php echo e($req); ?>" name="attr[<?php echo e($attr->id); ?>][valId]" value="<?php echo e($val->id); ?>" id="attr_<?php echo e($val->id); ?>" <?php if(isset($prdAssAttrs[$attr->id]) && $prdAssAttrs[$attr->id]->attr_val_id == $val->id): ?> checked="checked" <?php endif; ?> />
                            <span class="custom-control-label"><?php echo e($val->name); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    
                <?php elseif($attr->type == 'checkbox'): ?>
                    <?php $__currentLoopData = $attr->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label for="attr_<?php echo e($val->id); ?>" class="custom-control custom-checkbox mr-4 fl">
                            <input type="checkbox" class="custom-control-input <?php echo e($req); ?>" name="attr[<?php echo e($attr->id); ?>][valId]" value="<?php echo e($val->id); ?>" id="attr_<?php echo e($val->id); ?>" <?php if(isset($prdAssAttrs[$attr->id]) && $prdAssAttrs[$attr->id]->attr_val_id == $val->id): ?> checked="checked" <?php endif; ?> />
                            <span class="custom-control-label"><?php echo e($val->name); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php elseif($attr->type == 'text'): ?>
                <?php  if(isset($prdAssAttrs[$attr->id])){ $value = $prdAssAttrs[$attr->id]->attr_value; }else{ $value = ''; } ?>
                <?php echo e(Form::text('attr['.$attr->id.'][value]',$value,['id'=>'attr_'.$attr->id, 'class'=>'form-control '.$req,'placeholder'=>$attr->name])); ?>

            <?php elseif($attr->type == 'date'): ?>
                <?php  if(isset($prdAssAttrs[$attr->id])){ $value = $prdAssAttrs[$attr->id]->attr_value; }else{ $value = ''; } ?>
                <?php echo e(Form::date('attr['.$attr->id.'][value]',$value,['id'=>'attr_'.$attr->id, 'class'=>'form-control '.$req])); ?>

            <?php elseif($attr->type == 'textarea'): ?>
                <?php  if(isset($prdAssAttrs[$attr->id])){ $value = $prdAssAttrs[$attr->id]->attr_value; }else{ $value = ''; } ?>
                <?php echo e(Form::textarea('attr['.$attr->id.'][value]',$value,['id'=>'attr_'.$attr->id, 'class'=>'form-control '.$req,'placeholder'=>$attr->name])); ?>

            <?php endif; ?>
            <span class="error"></span>
            <div class="clr"></div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
                        <?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/products/details/attribute.blade.php ENDPATH**/ ?>