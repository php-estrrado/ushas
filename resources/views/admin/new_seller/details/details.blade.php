 @php    
    $row        =   0;
    $id         =   ($attr)? $attr->id : 0;
    $name       =   ($attr)? $attr->name : '';
    $nameId     =   ($attr)? $attr->name_cnt_id : 0;
    $type       =   ($attr)? $attr->type : '';
    $input      =   ($attr)? $attr->data_type : 'string';
    $required   =   ($attr)? $attr->required : '';
    $filter     =   ($attr)? $attr->filter : '';
    $configur   =   ($attr)? $attr->configur : '';
    $active     =   ($attr)? $attr->is_active : 1;
    if($id      >   0){ $disableType = true; }else{ $disableType = false; }
@endphp
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">{{$title}}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Masters</a></li>
            <li class="breadcrumb-item" aria-current="page"><a id="bc_list" href="">Attribute List</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$title}}</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body pb-2">
                {{ Form::open(array('url' => "admin/attribute/save", 'id' => 'adminForm', 'name' => 'adminForm', 'class' => '','files'=>'true')) }}
                    {{Form::hidden('name','name',['id'=>'name'])}}{{Form::hidden('arr_key','attr',['id'=>'arr_key'])}} {{Form::hidden('id',$id,['id'=>'id'])}} 

                    <div class="tabs-menu mb-4">
                        <ul class="nav panel-tabs">
                            <li><a href="#tab1" class="active" data-toggle="tab" id="nav_tab_1"><span>Attribute Details</span></a></li>
                            <li><a href="#tab2" data-toggle="tab" id="nav_tab_2"><span>Title Labels</span></a></li>@php $optDisp = '';
                            if($type != 'text' && $type != 'textarea'){ $filtDisp = ''; $inpDisp = 'display: none'; if($id == 0){ $optDisp = 'display: none'; } @endphp
                            <li><a href="#tab3" data-toggle="tab" id="nav_tab_3" style="{{$optDisp}}"><span>Options</span></a></li>
                            @php }else{ $filtDisp = 'display: none'; $inpDisp = ''; } @endphp
                       </ul>
                    </div>
                    <div class="row panel-body tabs-menu-body">
                        <div class="tab-content col-12">
                            <div class="tab-pane active " id="tab1">
                                <div class="card-header mb-4""><div class="card-title">Attribute Details</div></div>
                                <div class="col-lg-6 fl">
                                    <div class="form-group">
                                        {{Form::label('name','Attribute Name',['class'=>''])}}{{Form::hidden('attr_cnt_id',$nameId,['id'=>'attr_cnt_id'])}} 
                                        {{Form::text('attr[name]',$name,['id'=>'name','class'=>'form-control','placeholder'=>'Attribute Name'])}}
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 fl">
                                    <div class="form-group">
                                        {{Form::label('type','Attribute Type',['class'=>''])}}
                                        {{Form::select('attr[type]',$values,$type,['id'=>'type', 'class'=>'form-control','placeholder'=>'Select','disabled'=>$disableType])}}
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div id="data_type_div" class="col-lg-6 fl">
                                    <div class="form-group">
                                        {{Form::label('data_type','Input Type',['class'=>''])}}
                                        {{Form::select('attr[data_type]',$inputs,$input,['id'=>'data_type', 'class'=>'form-control','placeholder'=>'Select'])}}
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 fl">
                                    <div class="form-group">
                                        {{Form::label('required','Required',['class'=>''])}}
                                        {{Form::select('attr[required]',['1'=>'Yes','0'=>'No'],$required,['id'=>'required', 'class'=>'form-control','placeholder'=>'Select'])}}
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div id="filter_div" class="col-lg-6 fl">
                                    <div class="form-group">
                                        {{Form::label('filter','Filtrable',['class'=>''])}}
                                        {{Form::select('attr[filter]',['1'=>'Yes','0'=>'No'],$filter,['id'=>'filter', 'class'=>'form-control','placeholder'=>'Select'])}}
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div id="config_div" class="col-lg-6 fl">
                                    <div class="form-group">
                                        {{Form::label('configur','Configurable',['class'=>''])}}
                                        {{Form::select('attr[configur]',['1'=>'Yes','0'=>'No'],$configur,['id'=>'configur', 'class'=>'form-control','placeholder'=>'Select'])}}
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6 fl">
                                    <div class="form-group">
                                        {{Form::label('is_active','Status')}}
                                        {{Form::select('attr[is_active]',['1'=>'Active','0'=>'Inactive'],$active,['id'=>'is_active', 'class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="card-header mb-4"><div class="card-title">Attribute Titles</div></div>
                                @if($languages) @foreach($languages as $lang)
                                <div class="col-md-4 col-sm-6 fl">
                                    <div class="form-group">
                                        {{Form::label('name_cnt_id',$lang->glo_lang_name,['class'=>''])}}
                                        {{Form::text('attr_title['.$nameId.']['.$lang->id.']',getContent($nameId,$lang->id),['id'=>'cnt_'.$nameId.'_'.$lang->id,'class'=>'form-control tLabel'])}}
                                        <span class="error"></span>
                                    </div>
                                </div>
                                @endforeach @endif
                            </div>
                            <div class="tab-pane" id="tab3">
                                <div class="card-header mb-4"><div class="card-title">Attribute Value Options</div></div>
                                <div id="attr-val-content" class="col-12"> 
                                    @if($attrVals && count($attrVals) > 0)
                                    @foreach($attrVals as $val)
                                    
                                    
                                    <div class="col-11 mb-2 fl">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 mb-1">
                                                {{Form::hidden('value[id][]',$val->id,['id'=>'value_id_id'])}}
                                                {{Form::hidden('value[cnt][]',$val->name_cnt_id,['id'=>'cnt_id_id'])}}
                                                {{form::label('attr_val_id','Admin',['class'=>'m-0'])}}
                                                {{Form::text('value[val][]',$val->name,['id'=>'attr_val_id','class'=>'form-control vLabel required'])}}
                                                <span class="error"></span>
                                            </div>
                                            @if($languages) @foreach($languages as $lang)
                                            <div class="col-lg-3 col-md-6 mb-1">
                                                {{form::label('val_lang_'.$lang->id,$lang->glo_lang_name,['class'=>'m-0'])}}
                                                {{Form::text('value[lang]['.$lang->id.'][]',getContent($val->name_cnt_id,$lang->id),['id'=>'val_lang_'.$lang->id,'class'=>'form-control vLabel required'])}}
                                                <span class="error"></span>
                                            </div>
                                            @endforeach @endif
                                        </div>
                                    </div>
                                    <div class="col-1 pl-0 mb-2 fl">
                                        <label>&nbsp; &nbsp;</label><div class="clr"></div>
                                        <a id="del_val_id" class="del_val del"><i class="fa fa-trash"></I></a>
                                    </div>
                                    <div class="clr"></div>
                                    
                                    @php $row++; @endphp
                                    @endforeach
                                    @endif
                                </div><div class="clr"></div>
                    <div class="col-12 text-right">
                        <button id="add_val" class="mt-4 mb-4 btn btn-info btn-sm" type="button"><i class="fa fa-plus"></i>Add</button>
                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                    <div class="card-footer text-right">
                            {{Form::hidden('can_submit',0,['id'=>'can_submit'])}}{{Form::hidden('page','admin',['id'=>'admin'])}}
                            <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button id="save_btn" type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
               {{Form::close()}}
            </div>
        </div>
    </div>
</div>
<div id="adnl_rows" class="d-none">
    <div id="attr_val_row_id" class="row">
        <div class="col-11 mb-2 fl">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-1">
                    {{Form::hidden('value[id][]',0,['id'=>'value_id_id'])}}
                    {{Form::hidden('value[cnt][]',0,['id'=>'cnt_id_id'])}}
                    {{form::label('attr_val_id','Admin',['class'=>'m-0'])}}
                    {{Form::text('value[val][]','',['id'=>'attr_val_id','class'=>'form-control vLabel required'])}}
                    <span class="error"></span>
                </div>
                @if($languages) @foreach($languages as $lang)
                <div class="col-lg-3 col-md-6 mb-1">
                    {{form::label('val_lang_'.$lang->id,$lang->glo_lang_name,['class'=>'m-0'])}}
                    {{Form::text('value[lang]['.$lang->id.'][]','',['id'=>'val_lang_'.$lang->id,'class'=>'form-control vLabel required'])}}
                    <span class="error"></span>
                </div>
                @endforeach @endif
            </div>
        </div>
        <div class="col-1 pl-0 mb-2 fl">
            <label>&nbsp; &nbsp;</label><div class="clr"></div>
            <a id="del_val_id" class="del_val del"><i class="fa fa-trash"></I></a>
        </div>
        <div class="clr"></div>
    </div>
</div>