<div class="tab-pane attr" id="tab4">
    <div class="card-header mb-4"><div class="card-title">Attributes</div></div>
    <?php // echo '<pre>'; print_r($attributes); echo '</pre>'; ?>
    @if($attributes && count($attributes) > 0)
    @foreach($attributes as $attr)
    @php if($attr->required == 1){ $req = 'required'; }else{ $req = ''; } @endphp
    <div class="col-lg-6 ">
        <div class="form-group">
            <h6>{{$attr->name}}</h6> 
            @if($attr->values && count($attr->values) > 0)
                {{Form::hidden('attr['.$attr->id.'][value]',NULL)}}
                @if($attr->type == 'dropdown')
                <select name="attr[{{$attr->id}}][valId]" id="attr_{{$attr->id}}" class="form-control {{$req}}">
                    <option value="">Select {{$attr->name}}</option>
                    @foreach($attr->values as $val)
                    <option value="{{$val->id}}">{{$val->name}}</option>
                    @endforeach
                </select>
                
                @elseif($attr->type == 'radio')
                    @foreach($attr->values as $val)
                        <label for="attr_{{$val->id}}" class="custom-control custom-radio mr-4 fl">
                            <input type="radio" class="custom-control-input {{$req}}" name="attr[{{$attr->id}}][valId]" value="{{$val->id}}" id="attr_{{$val->id}}" />
                            <span class="custom-control-label">{{$val->name}}</span>
                        </label>
                    @endforeach
                    
                    
                @elseif($attr->type == 'checkbox')
                    @foreach($attr->values as $val)
                        <label for="attr_{{$val->id}}" class="custom-control custom-checkbox mr-4 fl">
                            <input type="checkbox" class="custom-control-input {{$req}}" name="attr[{{$attr->id}}][valId]" value="{{$val->id}}" id="attr_{{$val->id}}" />
                            <span class="custom-control-label">{{$val->name}}</span>
                        </label>
                    @endforeach
                @endif
            @elseif($attr->type == 'text')
                {{Form::text('attr['.$attr->id.'][value]','',['id'=>'attr_'.$val->id, 'class'=>'form-control '.$req,'placeholder'=>$attr->name])}}
            @elseif($attr->type == 'date')
                {{Form::date('attr['.$attr->id.'][value]','',['id'=>'attr_'.$val->id, 'class'=>'form-control '.$req])}}
            @elseif($attr->type == 'textarea')
                {{Form::textarea('attr['.$attr->id.'][value]','',['id'=>'attr_'.$val->id, 'class'=>'form-control '.$req,'placeholder'=>$attr->name])}}
            @endif
            <span class="error"></span>
            <div class="clr"></div>
        </div>
    </div>
    @endforeach
    @endif
</div>
                        