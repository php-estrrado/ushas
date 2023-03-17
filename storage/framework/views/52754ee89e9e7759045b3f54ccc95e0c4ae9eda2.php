<div class="card-header mb-4"><div class="card-title">Product Info</div></div>
<div class="col-lg-6  col-lg-offset-6">
        <div class="form-group">
            <?php echo e(Form::label('prd_type','Product Type',['class'=>''])); ?> <span class="text-red">*</span>
            <?php echo e(Form::select('prd_type',$prdTypes,$prdType,['id'=>'prd_type','disabled'=>true,'class'=>'form-control'])); ?>

            <span class="error"></span>
        </div>
    </div>
<input type="hidden" name="variations_check" id="variations_check" value="0">
 <?php if($prdType !="") { if($prdType ==1) { $simple_prod = "display:block;"; $var_prod = "display:none;"; }else {  $simple_prod = "display:none;"; $var_prod = "display:block;"; } }else { $simple_prod = "display:block;"; $var_prod = "display:none;"; }  ?>

     <div class="row panel-body1 tabs-menu-body1 simple_prod" style="<?php echo $simple_prod; ?>">     
        <div class="tab-content col-12">
     <div class="card-header mb-4"><div class="card-title">Price & Tax</div></div>       
     <div class="clearfix"></div>   
    
     <div class="col-lg-6 fl">
        <div class="form-group">
            <?php echo e(Form::label('tax','Tax',['class'=>''])); ?>

            <?php echo e(Form::select('price[tax]',$taxes,$taxId,['id'=>'tax','disabled'=>true,'class'=>'form-control','placeholder'=>'Select Tax'])); ?>

            <span class="error"></span>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="tab-content col-12">
     <div class="card-header mb-4"><div class="card-title">Sale Options</div></div>       
     <div class="clearfix"></div>   
    <div class="col-lg-6 fl">
        <div class="form-group">
            <?php echo e(Form::label('min_order','Minimum Order Quantity',['class'=>''])); ?>

             <?php echo e(Form::number('prd[min_order]',$min_order,['id'=>'min_order', 'class'=>'form-control','placeholder'=>'Quantity','max'=>9999])); ?>

             
             <p>(Only minimum and above quantity can be added to cart.)</p>
            <span class="error"></span>
        </div>
    </div>
     <div class="col-lg-6 fl">
        <div class="form-group">
            <?php echo e(Form::label('bulk_order','Bulk Order Quantity',['class'=>''])); ?>

             <?php echo e(Form::number('prd[bulk_order]',$bulk_order,['id'=>'bulk_order', 'class'=>'form-control','placeholder'=>'Quantity','max'=>9999])); ?>

             
             <p>(Product can be purchased only in bulk quantities.)</p>
            <span class="error"></span>
        </div>
    </div>
    
    
</div>

<div class="clearfix"></div>
<div class="tab-content col-12">
     <div class="card-header mb-4"><div class="card-title">Shipping</div></div>       
     <div class="clearfix"></div>   
    <div class="col-lg-4 fl">
        <div class="form-group">
            <?php echo e(Form::label('weight','Weight (kg)',['class'=>''])); ?>

            <?php echo e(Form::number('dimension[weight]',$weight,['id'=>'weight', 'class'=>'form-control','disabled'=>true,'placeholder'=>'Weight','max'=>9999999999])); ?>

            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-8 fl">
        <div class="form-group">
            <?php echo e(Form::label('dimensions','Dimensions (cm)',['class'=>''])); ?>

            <div class="tab-content">
                <div class="col-lg-4 fl">
                <div class="form-group">
                
                <?php echo e(Form::number('dimension[length]',$length,['id'=>'length', 'class'=>'form-control','disabled'=>true,'placeholder'=>'Length','max'=>9999999999])); ?>

                <span class="error"></span>
                </div>
                </div>
                
                <div class="col-lg-4 fl">
                <div class="form-group">
                
                <?php echo e(Form::number('dimension[width]',$width,['id'=>'width', 'class'=>'form-control','disabled'=>true,'placeholder'=>'Width','max'=>9999999999])); ?>

                <span class="error"></span>
                </div>
                </div>
                <div class="col-lg-4 fl">
                <div class="form-group">
                
                <?php echo e(Form::number('dimension[height]',$height,['id'=>'height', 'class'=>'form-control','disabled'=>true,'placeholder'=>'Height','max'=>9999999999])); ?>

                <span class="error"></span>
                </div>
                </div>
            </div>    
     </div>
    </div> 
    
</div>
</div>
           

<?php /**PATH C:\wamp64\www\ushas-dev\resources\views/admin/products/details/price_tax.blade.php ENDPATH**/ ?>