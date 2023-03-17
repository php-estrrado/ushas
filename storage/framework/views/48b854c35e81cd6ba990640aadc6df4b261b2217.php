<div class="col-12 mb-4">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>  
    <?php echo e(Form::open(array('url' => "admin/product-stock/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true'))); ?>

        <div class="col-lg-12 col-md-12">
            <div class="col-12 fl">
                <div class="form-group">
                    <?php echo e(Form::label('name','Product Name',['class'=>''])); ?>

                    <?php echo e(Form::text('attr[name]','',['id'=>'name','class'=>'form-control','disabled'=>true])); ?>

                    <?php echo e(Form::hidden('stock[prd_id]','',['id'=>'prd_id'])); ?> <?php echo e(Form::hidden('stock[seller_id]','',['id'=>'seller_id'])); ?> 
                    <span class="error"></span>
                </div>
            </div>
            
            <div id="" class="col-12 fl">
                <div class="form-group">
                    <?php echo e(Form::label('rate','Price ('.getCurrency()->name.')',['class'=>''])); ?>

                    <?php echo e(Form::text('stock[rate]','',['id'=>'rate', 'class'=>'form-control','readonly'=>true])); ?>

                    <span class="error"></span>
                </div>
            </div><div id="" class="col-12 fl">
                <div class="form-group">
                    <?php echo e(Form::label('qty','Quantity',['class'=>''])); ?>

                    <?php echo e(Form::number('stock[qty]','',['id'=>'qty', 'class'=>'form-control numberonly','data-val'=>1,'required'=>true,'placeholder'=>'Quantity'])); ?>

                    <span id="qty_error" class="error"></span>
                </div>
            </div>
            <div class="col-12 fl">
                <div class="form-group">
                    <?php echo e(Form::label('amount','Amount',['class'=>''])); ?> <?php echo e(Form::hidden('stock[type]','add',['id'=>'add'])); ?>

                    <?php echo e(Form::text('amount',0,['id'=>'amount', 'class'=>'form-control','readonly'=>true])); ?>

                    <span class="error"></span>
                </div>
            </div>
            <div id="" class="col-12 fl">
                <div class="form-group">
                    <?php echo e(Form::label('desc','Description',['class'=>''])); ?> <?php echo e(Form::hidden('stock[created_by]',auth()->user()->id,['id'=>'created_by'])); ?>

                    <?php echo e(Form::textarea('stock[desc]','',['id'=>'desc', 'class'=>'form-control','placeholder'=>'Description','rows'=>2])); ?>

                    <span class="error"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo e(Form::hidden('cancelId','',['id'=>'cancelId'])); ?> 
            <?php echo e(Form::button('Close',['id'=>'cancel_btn','class'=>'btn btn-secondary btn-sm fr','data-dismiss'=>'modal'])); ?>

            <?php echo e(Form::submit('Add',['id'=>'ad_stk_btn','class'=>'btn btn-info btn-sm fr'])); ?>

        </div>
    <?php echo e(Form::close()); ?>

</div>

<?php /**PATH /home/qaushas/public_html/resources/views/admin/stock/stock_form.blade.php ENDPATH**/ ?>