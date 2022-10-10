<div class="card-header mb-4"><div class="card-title">Fields</div></div>
    <?php //echo '<pre>'; print_r($field_values_ids); echo '</pre>'; ?>
    @if($fields && count($fields) > 0)
    
    @foreach($fields as $field)
   
    <div class="col-lg-6 fieldgroup">
        <div class="form-group">
            <h6>{{$field->name}}</h6> 
            
            @if($field->values && count($field->values) > 0)
            
                {{Form::hidden('field['.$field->id.'][value]',NULL)}}
                @foreach($field->values as $val)
                        <label for="attr_{{$field->id}}" class="custom-control custom-checkbox fieldslist show_{{$field->id}} mr-4 fl">
                            <input type="checkbox" class="custom-control-input ext-field" name="field[{{$field->id}}][valId][]" value="{{$val->id}}" id="attr_{{$field->id}}"   @if(isset($field_values_ids) && in_array($val->id,$field_values_ids)) checked="checked"  @endif />
                            <span class="custom-control-label">{{$val->name}}</span>
                        </label>
                    @endforeach
                @endif
            <span class="error"></span>
            <div class="clr"></div>
        </div>
    </div>
    @endforeach
    @endif
    <span class="error-field" style="color:red;display:none;">This field is required.</span>
                        