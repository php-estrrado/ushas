<div class="card-header mb-4"><div class="card-title">Attributes</div></div>
<?php if($assPrds && count($assPrds) > 0): ?> <?php $__currentLoopData = $assPrds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $cbVal = ''; ?>
<?php if(in_array($row->id,$assAssoPrdIds)){ $sltd = 'checked="checked"'; }else{ $sltd = ''; } ?>
<?php if($attrs && count($attrs) > 0): ?> <?php $__currentLoopData = $attrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php 
        if($cbVal == ''){ $cbVal = $atr->assAttr($row->id,$atr->id)->attr_val_id; }else{ $cbVal = $cbVal.'_'.$atr->assAttr($row->id,$atr->id)->attr_val_id; }
    ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
<input type="checkbox" name="assosi[<?php echo e($row->id); ?>]" id="assosi_<?php echo e($row->id); ?>" data-val="<?php echo e($cbVal); ?>" value="1" class="cb d-none <?php echo e($cbVal); ?> " <?php echo e($sltd); ?> />
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
    <div class="card-body"> 
        <div id="table_body" class="card-body table-card-body"> <?php // echo '<pre>'; print_r($attrs); echo '</pre>'; die; ?>
            <div>
                <table id="ass_product" class="ass_product-table table table-striped table-bordered w-100 text-nowrap">
                    <thead>
                        <tr>
                            <th class="wd-5p notexport">Select</th>
                            <th class="wd-25p">Product Name</th>
                            <th class="wd-7p">Price</th>
                            <th class="wd-7p">Stock</th>
                            <?php if($attrs && count($attrs) > 0): ?> <?php $__currentLoopData = $attrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="wd-10p"><?php echo e($atr->name); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php if($assPrds && count($assPrds) > 0): ?> <?php $n = 0; ?>
                            <?php $__currentLoopData = $assPrds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++; $dtVal = ''; ?> <?php // echo 'ssdss<pre>'; print_r($row->prdType); echo '</pre>'; die; ?>
                                <?php if(in_array($row->id,$assAssoPrdIds)){ $sltd = 'selected '; }else{ $sltd = ''; } ?>
                                <?php if($attrs && count($attrs) > 0): ?> <?php $__currentLoopData = $attrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php 
                                        if($dtVal == ''){ $dtVal = $atr->assAttr($row->id,$atr->id)->attr_val_id; }else{ $dtVal = $dtVal.'_'.$atr->assAttr($row->id,$atr->id)->attr_val_id; }
                                    ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                                <tr class="dtrow <?php echo e($dtVal); ?> <?php echo e($sltd); ?>" id="dtrow-<?php echo e($row->id); ?>" data-val="<?php echo e($dtVal); ?>">
                                    <td id="ck-<?php echo e($row->id); ?>" class="ck"><span class="d-none"><?php echo e($n); ?></span></td>
                                    <td><?php echo e($row->name); ?></td>
                                    <td><?php echo e($row->prdPrice->price); ?></td>
                                    <td><?php echo e($row->prdStock($row->id)); ?></td>
                                    <?php if($attrs && count($attrs) > 0): ?> <?php $__currentLoopData = $attrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="wd-10p"><?php echo e($atr->assAttr($row->id,$atr->id)->attrValue->name); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        
                    </tbody>

                </table>
                <?php echo e(csrf_field()); ?>

            </div>
        </div>
    </div>
 <script src="<?php echo e(URL::asset('admin/assets/js/datatable/tables/ass_product-datatable.js')); ?>"></script>
                     <?php /**PATH /home/qaushas/public_html/resources/views/admin/products/details/associative_prds.blade.php ENDPATH**/ ?>