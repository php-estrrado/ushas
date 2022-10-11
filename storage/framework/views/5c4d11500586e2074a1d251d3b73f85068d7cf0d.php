   
                                        <?php if($category_sort && count($category_sort) > 0): ?>
                                      <input type = "hidden" name="row_order" id="row_order" /> 
                                      <ul id="sortable-row">
									  
                    											<?php $__currentLoopData = $category_sort; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php  $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
                                                                       $category_name=DB::table('cms_content')->where('cnt_id', $row->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                       $category_desc=DB::table('cms_content')->where('cnt_id', $row->cat_desc_cid)->where('lang_id', $default_lang->id)->first(); ?>
                                            <li id=<?php echo e($row->category_id); ?>><?php echo e($category_name->content); ?></li>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php if(isset($category_not_in) && count($category_not_in) > 0): ?>
												<?php $__currentLoopData = $category_not_in; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<?php  $default_lang =DB::table('glo_lang_lk')->where('is_active', 1)->where('is_default', 1)->first();
                                                                       $cat_name=DB::table('cms_content')->where('cnt_id', $row1->cat_name_cid)->where('lang_id', $default_lang->id)->first();
                                                                       $cat_desc=DB::table('cms_content')->where('cnt_id', $row1->cat_desc_cid)->where('lang_id', $default_lang->id)->first(); ?>
												<li id=<?php echo e($row1->category_id); ?>><?php echo e($cat_name->content); ?></li>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
                                      </ul>
                                      <?php endif; ?>
                                   <?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/master/includes/businessCategoryList.blade.php ENDPATH**/ ?>