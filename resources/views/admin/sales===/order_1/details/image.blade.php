@php $n_img = 0 @endphp
<div class="tab-pane" id="tab3">
    <div class="card-header mb-4"><div class="card-title">Product Images</div></div>
    <div id="prd_imgs">
        <div id="img_row_0" class="col-12 fl img_row">
            <div class="col-lg-6 fl">
                <div class="form-group">
                    {{Form::hidden('imgId[]',0,['id'=>'img_id_'.$n_img])}}
                    {{Form::file('image[]',['id'=>'image_'.$n_img,'class'=>'form-control img','placeholder'=>'Choose Image','accept'=>'image/*'])}}
                </div>
            </div>
        <div class="col-lg-5 col-8 fl">
                <div class="form-group">
                    <img src="" alt="Image" id="image_{{$n_img}}_img" class="no-disp" height="90" />
                </div>
            </div> @php $n_img++; @endphp
            <div class="clr"></div>
        </div>
            <div class="clr"></div>

    </div>
    <div class="clr"></div>
    <div class="col-12 text-right">
        <button id="add_more" class="mt-4 mb-4 btn btn-info btn-sm" type="button"><i class="fa fa-plus mr-1"></i>Add More</button>
    </div>
</div>


<div id="add_more_img" class="d-none">
    <div id="img_row_id" class="col-12 fl img_row">
        <div class="col-lg-6 fl">
            <div class="form-group">
                {{Form::file('image[]',['id'=>'image_file_id','class'=>'form-control img','placeholder'=>'Choose Image','accept'=>'image/*'])}}
            </div>
        </div>
        <div class="col-lg-5 col-8 fl">
            <div class="form-group">
                <img src="" alt="Image" id="image_disp_id" class="no-disp" />
            </div>
        </div>
        <div class="col-lg-1 col-2 pl-0 mb-2 fl">
            <div class="form-group">
                <label>&nbsp; &nbsp;</label><div class="clr"></div>
                <a id="del_img_id" class="del_img del"><i class="fa fa-trash"></I></a>
            </div>
        </div>@php $n_img++; @endphp
        <div class="clr"></div>
    </div>
</div>