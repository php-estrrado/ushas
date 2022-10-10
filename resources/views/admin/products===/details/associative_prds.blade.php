<div class="card-header mb-4"><div class="card-title">Attributes</div></div>
@if($assPrds && count($assPrds) > 0) @foreach($assPrds as $row) @php $cbVal = ''; @endphp
@php if(in_array($row->id,$assAssoPrdIds)){ $sltd = 'checked="checked"'; }else{ $sltd = ''; } @endphp
@if($attrs && count($attrs) > 0) @foreach($attrs as $atr)
    @php 
        if($cbVal == ''){ $cbVal = $atr->assAttr($row->id,$atr->id)->attr_val_id; }else{ $cbVal = $cbVal.'_'.$atr->assAttr($row->id,$atr->id)->attr_val_id; }
    @endphp
@endforeach @endif
<input type="checkbox" name="assosi[{{$row->id}}]" id="assosi_{{$row->id}}" data-val="{{$cbVal}}" value="1" class="cb d-none {{$cbVal}} " {{$sltd}} />
@endforeach @endif
    <div class="card-body"> 
        <div id="table_body" class="card-body table-card-body"> <?php // echo '<pre>'; print_r($attrs); echo '</pre>'; die; ?>
            <div>
                <table id="ass_product" class="ass_product-table table table-striped table-bordered w-100 text-nowrap">
                    <thead>
                        <tr>
                            <th class="wd-5p notexport">Select</th>
                            <th class="wd-25p">Product Name</th>
                            <th class="wd-7p">Price</th>
                            <th class="wd-7p">Stock</th>
                            @if($attrs && count($attrs) > 0) @foreach($attrs as $atr)
                            <th class="wd-10p">{{$atr->name}}</th>
                            @endforeach @endif
                        </tr>
                    </thead>
                    <tbody> 
                        @if($assPrds && count($assPrds) > 0) @php $n = 0; @endphp
                            @foreach($assPrds as $row) @php $n++; $dtVal = ''; @endphp <?php // echo 'ssdss<pre>'; print_r($row->prdType); echo '</pre>'; die; ?>
                                @php if(in_array($row->id,$assAssoPrdIds)){ $sltd = 'selected '; }else{ $sltd = ''; } @endphp
                                @if($attrs && count($attrs) > 0) @foreach($attrs as $atr)
                                    @php 
                                        if($dtVal == ''){ $dtVal = $atr->assAttr($row->id,$atr->id)->attr_val_id; }else{ $dtVal = $dtVal.'_'.$atr->assAttr($row->id,$atr->id)->attr_val_id; }
                                    @endphp
                                @endforeach @endif
                                <tr class="dtrow {{$dtVal}} {{$sltd}}" id="dtrow-{{$row->id}}" data-val="{{$dtVal}}">
                                    <td id="ck-{{$row->id }}" class="ck"><span class="d-none">{{$n}}</span></td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->prdPrice->price}}</td>
                                    <td>{{$row->prdStock($row->id)}}</td>
                                    @if($attrs && count($attrs) > 0) @foreach($attrs as $atr)
                                        <td class="wd-10p">{{ $atr->assAttr($row->id,$atr->id)->attrValue->name }}</td>
                                    @endforeach @endif
                                </tr>
                            @endforeach
                        @endif
                        
                    </tbody>

                </table>
                {{ csrf_field() }}
            </div>
        </div>
    </div>
 <script src="{{URL::asset('admin/assets/js/datatable/tables/ass_product-datatable.js')}}"></script>
                     