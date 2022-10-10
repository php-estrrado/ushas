<div class="modal fade converted" role="dialog" tabindex="-1" id="attr_<?php echo $row; ?>">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Shipping</h5>
        <button type="button" class="close" data-dismiss="modal">
        <span aria-hidden="true">×</span>
        </button>
        </div>
        <div class="modal-body">
        <div class="tab-content col-12">
        <div class="card-header mb-4"><div class="card-title">Shipping</div></div>       
        <div class="clearfix"></div>   
        <div class="col-lg-4 fl">
        <div class="form-group">
        {{Form::label('mweight','Weight (kg)',['class'=>''])}}
        {{Form::number('weight'.$row_name,$atr_weight,['id'=>'mweight', 'class'=>'form-control','placeholder'=>'Weight','max'=>9999999999])}}
        <span class="error"></span>
        </div>
        </div>
        <div class="col-lg-8 fl">
        <div class="form-group">
        {{Form::label('mdimensions','Dimensions (cm)',['class'=>''])}}
        <div class="tab-content">
        <div class="col-lg-4 fl">
        <div class="form-group">

        {{Form::number('length'.$row_name,$atr_length,['id'=>'mlength', 'class'=>'form-control','placeholder'=>'Length','max'=>9999999999])}}
        <span class="error"></span>
        </div>
        </div>

        <div class="col-lg-4 fl">
        <div class="form-group">

        {{Form::number('width'.$row_name,$atr_width,['id'=>'mwidth', 'class'=>'form-control','placeholder'=>'Width','max'=>9999999999])}}
        <span class="error"></span>
        </div>
        </div>
        <div class="col-lg-4 fl">
        <div class="form-group">

        {{Form::number('height'.$row_name,$atr_height,['id'=>'mheight', 'class'=>'form-control','placeholder'=>'Height','max'=>9999999999])}}
        <span class="error"></span>
        </div>
        </div>
        </div>    
        </div>
        </div> 

        </div>
        <div class="py-1">
        <div class="row">
        <div class="col d-flex justify-content-end">
        <input type="button" class="btn btn-primary mr-6" data-dismiss="modal" value="Save">
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>