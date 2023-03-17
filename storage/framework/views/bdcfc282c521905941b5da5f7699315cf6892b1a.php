<?php 

if(isset($user_data))
{
    $user_id = $user_data->user_id;
    if($user_data->outstanding >0 ) { $outstanding = $user_data->outstanding; }else { $outstanding = 0;}
    $user_name = $user_data->user->first_name." ".$user_data->user->middle_name." ".$user_data->user->last_name;
}else{
       $user_id = 0;
    $outstanding = 0;
    $user_name = "";
}
?>

<div class="col-12 mb-4">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Offline Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>  
    <?php echo e(Form::open(array('url' => "admin/customer/credits-payment", 'id' => 'priceForm', 'name' => 'priceForm', 'class' => '','files'=>'true'))); ?>

        <div class="col-lg-12 col-md-12">
            <div class="col-12 fl mt-3">
                <div class="form-group">
                    <?php echo e(Form::label('name','Customer Name',['class'=>''])); ?>

                    <?php echo e(Form::text('attr[name]',$user_name,['id'=>'name','class'=>'form-control','disabled'=>true])); ?>

                    <?php echo e(Form::hidden('user_id',$user_id,['id'=>'credit_user_id'])); ?> 
                    <span class="error"></span>
                </div>
            </div>
            
            <div id="" class="col-12 fl">
                <div class="form-group">
                    <?php echo e(Form::label('price','Outstanding ('.getCurrency()->name.')',['class'=>''])); ?>

                    <?php echo e(Form::text('credits[price]',$outstanding,['id'=>'price', 'class'=>'form-control','placeholder'=>'Outstanding','disabled'=>true])); ?>

                    <span class="error"></span>
                </div>
            </div>

            <div id="" class="col-12 fl">
                <div class="form-group">
                    <?php echo e(Form::label('price','Amount ('.getCurrency()->name.')',['class'=>''])); ?>

                    <?php echo e(Form::text('amount','',['id'=>'price', 'class'=>'form-control','placeholder'=>'Amount','required'=>true])); ?>

                    <span class="error"></span>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <?php echo e(Form::hidden('cancelId','',['id'=>'cancelId'])); ?> 
            <?php echo e(Form::button('Close',['id'=>'cancel_btn','class'=>'btn btn-secondary btn-sm fr','data-dismiss'=>'modal'])); ?>

            <?php echo e(Form::submit('Pay',['id'=>'ad_stk_btn','class'=>'btn btn-info btn-sm fr'])); ?>

        </div>
    <?php echo e(Form::close()); ?>

</div>

<?php /**PATH /home/qaushas/public_html/resources/views/customer/credits/payment_form.blade.php ENDPATH**/ ?>