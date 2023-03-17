 <?php    
    $row        =   0;
    $id         =   ($field)? $field->id : 0;
    $name       =   ($field)? $field->name : '';
    $nameId     =   ($field)? $field->name_cnt_id : 0;
    $type       =   ($field)? $field->type : '';
    $input      =   ($field)? $field->data_type : 'string';
    $required   =   ($field)? $field->required : '';
    $filter_val     =   ($field)? $field->filter : '';
    $configur   =   ($field)? $field->configur : '';
    $variable_rate   =   ($field)? $field->variable_rate : '';
    $active     =   ($field)? $field->is_active : 1;
    if($id      >   0){ $disableType = true; }else{ $disableType = false; }
?>
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0"><?php echo e($title); ?></h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Masters</a></li>
            <li class="breadcrumb-item" aria-current="page"><a id="bc_list" href="">Field List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#"><?php echo e($title); ?></a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body pb-2">
                <?php echo e(Form::open(array('url' => "admin/field/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true'))); ?>

                    <?php echo e(Form::hidden('name','name',['id'=>'name'])); ?><?php echo e(Form::hidden('arr_key','field',['id'=>'arr_key'])); ?> <?php echo e(Form::hidden('id',$id,['id'=>'id'])); ?> 
                    <?php if($filters && count($filters) > 0): ?> <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e(Form::hidden('filter['.$k.']',$filter,['id'=>$k])); ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                    <div class="tabs-menu mb-4">
                        <ul class="nav panel-tabs">
                            <li><a href="#tab1" class="active" data-toggle="tab" id="nav_tab_1"><span>Field Details</span></a></li>
                            <li><a href="#tab2" data-toggle="tab" id="nav_tab_2"><span>Title Labels</span></a></li><?php $optDisp = '';
                            if($type != 'text' && $type != 'textarea'){ $filtDisp = ''; $inpDisp = 'display: none'; if($id == 0){ $optDisp = 'display: none'; } ?>
                            <li><a href="#tab3" data-toggle="tab" id="nav_tab_3" ><span>Field Values</span></a></li>
                            <?php }else{ $filtDisp = 'display: none'; $inpDisp = ''; } ?>
                       </ul>
                    </div>
                    <div class="row panel-body tabs-menu-body">
                        <div class="tab-content col-12">
                            <div class="tab-pane active " id="tab1">
                                <div class="card-header mb-4""><div class="card-title">Field Details</div></div>
                                <div class="col-lg-6 fl">
                                    <div class="form-group">
                                        <?php echo e(Form::label('name','Field Name',['class'=>''])); ?> <span class="text-red">*</span> <?php echo e(Form::hidden('attr_cnt_id',$nameId,['id'=>'attr_cnt_id'])); ?>

                                        <?php echo e(Form::text('field[name]',$name,['id'=>'name','class'=>'form-control','placeholder'=>'Field Name'])); ?>

                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 fl">
                                    <div class="form-group">
                                        <?php echo e(Form::label('type','Field Type',['class'=>''])); ?> <span class="text-red">*</span>
                                        <?php echo e(Form::select('field[type]',$values,$type,['id'=>'type', 'class'=>'form-control','placeholder'=>'Select','disabled'=>$disableType])); ?>

                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="clr"></div>
                                <!-- <div id="data_type_div" class="col-lg-6 fl">
                                    <div class="form-group">
                                        <?php echo e(Form::label('data_type','Input Type',['class'=>''])); ?> <span class="text-red">*</span>
                                        <?php echo e(Form::select('field[data_type]',$inputs,$input,['id'=>'data_type', 'class'=>'form-control','placeholder'=>'Select'])); ?>

                                        <span class="error"></span>
                                    </div>
                                </div> -->
                                <div class="col-lg-6 fl">
                                    <div class="form-group">
                                        <?php echo e(Form::label('required','Required',['class'=>''])); ?> <span class="text-red">*</span>
                                        <?php echo e(Form::select('field[required]',['1'=>'Yes','0'=>'No'],$required,['id'=>'required', 'class'=>'form-control','placeholder'=>'Select'])); ?>

                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div id="filter_div" class="col-lg-6 fl">
                                    <div class="form-group">
                                        
                                        <?php echo e(Form::label('filter','Filtrable',['class'=>''])); ?>

                                        <?php echo e(Form::select('field[filter]',['1'=>'Yes','0'=>'No'],$filter_val,['id'=>'filter', 'class'=>'form-control','placeholder'=>'Select'])); ?>

                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="clr"></div>
                              <!--   <div id="config_div" class="col-lg-6 fl">
                                    <div class="form-group">
                                        <?php echo e(Form::label('configur','Configurable',['class'=>''])); ?>

                                        <?php echo e(Form::select('field[configur]',['1'=>'Yes','0'=>'No'],$configur,['id'=>'configur', 'class'=>'form-control','placeholder'=>'Select'])); ?>

                                        <span class="error"></span>
                                    </div>
                                </div> -->
                                <!--<div id="" class="col-lg-6 fl">-->
                                <!--    <div class="form-group">-->
                                <!--        <?php echo e(Form::label('fixed_rate','Variable Rate',['class'=>''])); ?>-->
                                <!--        <?php echo e(Form::select('field[variable_rate]',['1'=>'Yes','0'=>'No'],$variable_rate,['id'=>'filter', 'class'=>'form-control','placeholder'=>'Select'])); ?>-->
                                <!--        <span class="error"></span>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="col-lg-6 fl">
                                    <div class="form-group">
                                        <?php echo e(Form::label('is_active','Status')); ?> <span class="text-red">*</span>
                                        <?php echo e(Form::select('field[is_active]',['1'=>'Active','0'=>'Inactive'],$active,['id'=>'is_active', 'class'=>'form-control'])); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="card-header mb-4"><div class="card-title">Attribute Titles</div></div>
                                <?php if($languages): ?> <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 col-sm-6 fl">
                                    <div class="form-group">
                                        <?php echo e(Form::label('name_cnt_id','Label in '.$lang->glo_lang_name,['class'=>''])); ?>

                                        <?php echo e(Form::text('field_title['.$nameId.']['.$lang->id.']',getContent($nameId,$lang->id),['id'=>'cnt_'.$nameId.'_'.$lang->id,'class'=>'form-control tLabel'])); ?>

                                        <span class="error"></span>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                            </div>
                            <div class="tab-pane" id="tab3">
                                <div class="card-header mb-4"><div class="card-title">Field Value Options</div></div>
                                <div id="attr-val-content" class="col-6"> 
                                    <?php if($fieldVals && count($fieldVals) > 0): ?>
                                    <?php $__currentLoopData = $fieldVals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($val->name !=""): ?>
                                    
                                    <div class="col-11 mb-2 fl" id=<?php echo "delfiecol-".$val->id;?>>
                                        <div class="row">
                                            <div class="col-lg-11 col-md-6 mb-1" >
                                                <?php echo e(Form::hidden('value[id][]',$val->id,['id'=>'value_id_id'])); ?>

                                                <?php echo e(Form::hidden('value[cnt][]',$val->name_cnt_id,['id'=>'cnt_id_id'])); ?>

                                                <?php echo e(form::label('field_val_id','Field Value',['class'=>'m-0'])); ?>

                                                <?php echo e(Form::text('value[val][]',$val->name,['id'=>'field_val_id','class'=>'form-control vLabel required'])); ?>

                                                <span class="error"></span>
                                                
                                            </div>
                                           
                                        </div>
                                        
                                    </div>
                                    <div class="col-1 pl-0 mb-2 fl" id=<?php echo "delfie-".$val->id;?>>
                                        <label>&nbsp; &nbsp;</label><div class="clr"></div>
                                        <a id="del_val_id" class="del_val del deletefield" data-id =<?php echo $val->id;?>><i class="fa fa-trash"></I></a>
                                    </div>
                                    <div class="clr"></div>
                                    
                                    <?php $row++; ?>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php else: ?>
                                    <div class="col-11 mb-2 fl">
                                        <div class="row">
                                            <div class="col-lg-11 col-md-6 mb-1">
                                                <?php echo e(Form::hidden('value[id][]',0,['id'=>'value_id_id'])); ?>

                                                <?php echo e(Form::hidden('value[cnt][]',0,['id'=>'cnt_id_id'])); ?>

                                                <?php echo e(form::label('field_val_id','Field Value',['class'=>'m-0'])); ?>

                                                <?php echo e(Form::text('value[val][]','',['id'=>'field_val_id','class'=>'form-control vLabel required'])); ?>

                                                <span class="error"></span>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="col-1 pl-0 mb-2 fl">
                                        
                                    </div>
                                    <div class="clr"></div>
                                    <?php endif; ?>
                                </div><div class="clr"></div>
                    <div class="col-6 text-right">
                        <button id="add_val" class="mt-4 mb-4 btn btn-info btn-sm" type="button"><i class="fa fa-plus"></i>Add</button>
                    </div>
                    <span id="field_val" class="field_val error" style="color:red;display:none;">This field is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                    <div class="card-footer text-right">
                            <?php echo e(Form::hidden('can_submit',0,['id'=>'can_submit'])); ?><?php echo e(Form::hidden('page','admin',['id'=>'admin'])); ?>

                            <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button id="save_btn" type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
               <?php echo e(Form::close()); ?>

            </div>
        </div>
    </div>
</div>
<div id="adnl_rows" class="d-none">
    <div id="attr_val_row_id" class="">
        <div class="col-11 mb-2 fl">
            <div class="row">
                <div class="col-lg-11 col-md-6 mb-1">
                    <?php echo e(Form::hidden('value[id][]',0,['id'=>'value_id_id'])); ?>

                    <?php echo e(Form::hidden('value[cnt][]',0,['id'=>'cnt_id_id'])); ?>

                    <?php echo e(form::label('field_val_id','Field Value',['class'=>'m-0'])); ?>

                    <?php echo e(Form::text('value[val][]','',['id'=>'field_val_id','class'=>'form-control vLabel required'])); ?>

                    <span class="error"></span>
                </div>
              
            </div>
        </div>
        <div class="col-1 pl-0 mb-2 fl">
            <label>&nbsp; &nbsp;</label><div class="clr"></div>
            <a id="del_val_id" class="del_val del"><i class="fa fa-trash"></I></a>
        </div>
        <div class="clr"></div>
    </div>
</div><?php /**PATH /home/qaushas/public_html/resources/views/admin/field/details.blade.php ENDPATH**/ ?>