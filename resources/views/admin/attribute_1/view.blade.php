 @php  
    $nameId     =   ($attr)? $attr->name_cnt_id : 0;
    if($attr->required  ==  1){ $required   = 'Yes';    }else{ $required    = 'No'; }
    if($attr->filter    ==  1){ $filter     = 'Yes';    }else{ $filter      = 'No'; }
    if($attr->configur  ==  1){ $configur   = 'Yes';    }else{ $configur    = 'No'; }
    if($attr->is_active ==  1){ $active     = '<span class="badge badge-success">Active</span>'; }else{ $active = '<span class="badge badge-error">Inactive</span>'; }
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
                    <div class="tabs-menu mb-4">
                        <ul class="nav panel-tabs">
                            <li><a href="#tab1" class="active" data-toggle="tab" id="nav_tab_1"><span>Attribute Details</span></a></li>
                            @php if($attr->type != 'text' && $attr->type != 'textarea'){ $filtDisp = ''; $inpDisp = 'display: none'; @endphp
                            <li><a href="#tab3" data-toggle="tab" id="nav_tab_3" style=""><span>Options</span></a></li>
                            @php }else{ $filtDisp = 'display: none'; $inpDisp = ''; } @endphp
                       </ul>
                    </div><?php // echo '<pre>'; print_r($attr); echo '</pre>'; die; ?>
                    <div class="row panel-body tabs-menu-body">
                        <div class="tab-content col-12">
                            <div class="tab-pane active " id="tab1">
                                <div class="card-header mb-4""><div class="card-title">Attribute Details</div></div>
                                <div class="col-lg-4 col-md-6 fl">
                                    <div class="form-group">
                                        <div class="text-muted">Attribute Name</div><div class="font-weight-bold">{{$attr->name}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 fl">
                                    <div class="form-group">
                                        <div class="text-muted">Attribute Type</div><div class="font-weight-bold">{{$iType->name}}</div>
                                    </div>
                                </div>
                                <div id="data_type_div" class="col-lg-4 col-md-6 fl">
                                    <div class="form-group">
                                        <div class="text-muted">Input Type</div><div class="font-weight-bold">{{$dType->name}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 fl">
                                    <div class="form-group">
                                        <div class="text-muted">Required</div><div class="font-weight-bold">{{$required}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 fl">
                                    <div class="form-group">
                                        <div class="text-muted">Filterable</div><div class="font-weight-bold">{{$filter}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 fl">
                                    <div class="form-group">
                                        <div class="text-muted">Configurable</div><div class="font-weight-bold">{{$configur}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 fl">
                                    <div class="form-group">
                                        <div class="text-muted">Status</div><div class="font-weight-bold">{!!$active!!}</div>
                                    </div>
                                </div>
                                <div class="clr"></div>
                                <div class="card-header mb-4"><div class="card-title">Attribute Titles</div></div>
                                @if($languages) @foreach($languages as $lang)
                                <div class="col-md-4 col-sm-6 fl">
                                    <div class="form-group">
                                        <div class="text-muted">{{$lang->glo_lang_name}}</div><div class="font-weight-bold">{{getContent($nameId,$lang->id)}}</div>
                                    </div>
                                </div>
                                @endforeach @endif
                            </div>
                            <div class="tab-pane" id="tab3">
                                <div class="card-header mb-4"><div class="card-title">Attribute Value Options</div></div>
                                <div id="attr-val-content" class="col-12"> 
                                    @if($attrVals && count($attrVals) > 0)
                                    @foreach($attrVals as $val)
                                    <div class="col-12 mb-2 fl">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 mb-1">
                                                <div class="text-muted">Admin</div><div class="font-weight-bold">{{$val->name}}</div>
                                            </div>
                                            @if($languages) @foreach($languages as $lang)
                                            <div class="col-lg-3 col-md-6 mb-1">
                                                <div class="text-muted">{{$lang->glo_lang_name}}</div><div class="font-weight-bold">{{getContent($val->name_cnt_id,$lang->id)}}</div>
                                            </div>
                                            @endforeach @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div><div class="clr"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                    <div class="card-footer text-right">
                        <button id="cancel_btn" type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>
