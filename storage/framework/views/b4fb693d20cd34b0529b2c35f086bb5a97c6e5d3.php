<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0"><?php echo e($title); ?></h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Product Management</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
        </ol>
    </div>
    <!-- <div class="page-rightheader" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
        <div class="btn btn-list">
            <a id="addNew"   class="btn btn-primary addmodule" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fe fe-plus mr-1"></i> Add New</a>
            <a id="importproduct"   class="btn btn-success importproduct" data-toggle="modal" data-target=".importproduct_modal"><i class="fe fe-download mr-1"></i> Import</a>
        </div>
    </div> -->
</div>
<div id="filters" class="">
    <div class="row" id="filterrow"><div class="plus-minus-toggle collapsed"><p>Filters</p></div></div>
    <div class="row no-disp mb-4 " id="filtersec">
        <div class="col-3 fl">
            <div class="page-filters">
                <?php echo e(Form::label('active','Status',['class'=>'text-white'])); ?>

                <?php echo e(Form::select('active',[1=>'Active',0=>'Inactive'],$active,['id'=>'active_filter','class'=>'form-control mr-4 active_filters','placeholder'=>'All Status'])); ?>

            </div>
        </div>
        <!--<div class="col-3 fl">-->
        <!--    <div class="page-filters">-->
        <!--        <?php echo e(Form::label('sellers','Seller',['class'=>'text-white'])); ?>-->
        <!--        <?php echo e(Form::select('sellers',$sellers,$seller,['id'=>'seller','class'=>'form-control mr-4 active_filters','placeholder'=>'All Sellers'])); ?>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="col-3 fl">
            <div class="page-filters">
                <?php echo e(Form::label('category','Category',['class'=>'text-white'])); ?>

                <?php echo e(Form::select('category',$categories,$category,['id'=>'category','class'=>'form-control mr-4 active_filters','placeholder'=>'All Categories'])); ?>

            </div>
        </div>
        <div class="clr"></div>
    </div>
</div>
<div class="row flex-lg-nowrap">
    <div class="col-12">
        <div class="row flex-lg-nowrap">
            <div class="col-12 mb-3">
                <div class="e-panel card">
                    <div class="card-body">
                        <div id="table_body" class="card-body table-card-body">
                                <table id="product" class="product-table table table-striped table-bordered w-100 text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="wd-5p notexport"></th>
                                            <th class="wd-15p">Product Name</th>
                                            
                                            <th class="wd-10p">Category</th>
                                            <th class="wd-10p">Sub Category</th>
                                            <th class="wd-10p">Type</th>
                                            <th class="wd-10p">Price (<?php echo e(getCurrency()->name); ?>)</th>
                                            <th class="wd-10p">Created On</th>
                                            <th class="wd-10p notexport">Status</th>
                                            <th class="wd-25p text-center notexport action-btn">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php if($products && count($products) > 0): ?> <?php $n = 0; ?>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $n++; ?> <?php // echo 'ssdss<pre>'; print_r($row->prdType); echo '</pre>'; die; ?>
                                                <?php if($row->is_active == 1){ $active = "Active"; $checked = 'checked'; }else if ($row->is_active == 0){ $active = "Inactive"; $checked = ""; } ?>
                                                <tr class="dtrow" id="dtrow-<?php echo e($row->id); ?>">
                                                    <td><span class="d-none"><?php echo e($n); ?></span></td>
                                                    <td><a style="cursor: pointer;" class="pointer viewBtn" id="viewBtn-<?php echo e($row->id); ?>"><?php echo e(getContent($row->name_cnt_id,$langId)); ?></a></td>
                                                
                                                    <td><?php if($row->category): ?><?php echo e($row->category->cat_name); ?> <?php endif; ?></td>
                                                    <td><?php if($row->subCategory): ?><?php echo e($row->subCategory->subcategory_name); ?><?php endif; ?></td>
                                                    <td><?php echo e($row->prdType->type_name); ?></td>
                                                    <td><?php if($row->prdPrice): ?><?php echo e($row->prdPrice->price); ?> <?php endif; ?></td>
                                                    <td><?php echo e(date('d M Y',strtotime($row->created_at))); ?></td>
                                                    <td class="text-nowrap align-middle" data-search="<?php if($row->is_active==1): ?><?php echo e("Active"); ?><?php else: ?><?php echo e("Inactive"); ?><?php endif; ?>">
                                                        <div class="switch">
                                                            <input class="switch-input status-btn" id="status-<?php echo e($row->id); ?>" type="checkbox" <?php echo e($checked); ?> name="status">
                                                            <label class="switch-paddle" for="status-<?php echo e($row->id); ?>">
                                                                <span class="switch-active" aria-hidden="true">Active</span>
                                                                <span class="switch-inactive" aria-hidden="true">Inactive</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button id="specialOfr-<?php echo e($row->id); ?>" class="mr-2 btn btn-info btn-sm specialOfr"><i class="fa fa-edit mr-1"></i>Discount</button>
                                                        <button id="editBtn-<?php echo e($row->id); ?>" class="mr-2 btn btn-info btn-sm editBtn"><i class="fa fa-edit mr-1"></i><span>Edit</span></button>
                                                        <button id="delBtn-<?php echo e($row->id); ?>" class="mr-2 btn btn-secondary btn-sm delBtn"><i class="fe fe-trash-2 mr-1"></i>Delete</button>
                                                    </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>

                                </table>
                                <?php echo e(csrf_field()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- <script src="<?php echo e(asset('admin/assets/js/datatable/tables/admin_product-datatable.js')); ?>"></script> -->

    <?php /**PATH /home/qaushas/public_html/resources/views/admin/products/list/content.blade.php ENDPATH**/ ?>