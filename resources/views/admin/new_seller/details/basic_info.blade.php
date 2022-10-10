<div class="tab-pane active " id="tab1">
    <div class="card-header mb-4""><div class="card-title">Basic Information</div></div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('business_name','Business Name',['class'=>''])}} <span class="text-red">*</span>
            {{Form::text('info[business_name]',$bName,['id'=>'business_name', 'class'=>'form-control','placeholder'=>'Business Name'])}}
            <span class="error"></span>
        </div>
    </div>
    <div id="filter_div" class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('store_name','Store Name',['class'=>''])}} <span class="text-red">*</span>
            {{Form::text('info[store_name]',$sName,['id'=>'store_name', 'class'=>'form-control','placeholder'=>'Store Name'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('email','Email Type',['class'=>''])}} <span class="text-red">*</span>
            {{Form::text('info[email]',$email,['id'=>'email', 'class'=>'form-control','placeholder'=>'Email'])}}
            <span class="error"></span>
        </div>
    </div>
    <div id="data_type_div" class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('phone','Phone',['class'=>''])}} <span class="text-red">*</span>
            {{Form::text('info[phone]',$phone,['id'=>'phone', 'class'=>'form-control','placeholder'=>'Phone'])}}
            <span class="error"></span>
        </div>
    </div>
    <div id="config_div" class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('licence','Licence',['class'=>''])}}
            {{Form::text('info[licence]',$licence,['id'=>'licence', 'class'=>'form-control','placeholder'=>'Licence'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('director_name','Director Name',['class'=>''])}} <span class="text-red">*</span>
            {{Form::text('info[director_name]',$name,['id'=>'director_name','class'=>'form-control','placeholder'=>'Director Name'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('ic_number','Director IC Number',['class'=>''])}}
            {{Form::text('info[ic_number]',$name,['id'=>'ic_number','class'=>'form-control','placeholder'=>'Director IC Number'])}}
            <span class="error"></span>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('incharge_name','Incharge Name',['class'=>''])}}
            {{Form::text('info[incharge_name]',$icName,['id'=>'incharge_name','class'=>'form-control','placeholder'=>'Incharge Name'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('incharge_phone','Incharge Phone',['class'=>''])}}
            {{Form::text('info[incharge_phone]',$icPhone,['id'=>'incharge_phone','class'=>'form-control','placeholder'=>'Incharge Phone'])}}
            <span class="error"></span>
        </div>
    </div> 
    <div class="col-lg-6 fl">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{Form::label('logo','Logo',['class'=>''])}}
                    {{Form::file('image[logo]',['id'=>'logo','class'=>'form-control'])}}
                    <span class="error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <img id="logoImg" src="{{$logoImg}}" alt="Logo" height="70" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 fl">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{Form::label('banner','Store Banner',['class'=>''])}}
                    {{Form::file('image[banner]',['id'=>'banner','class'=>'form-control'])}}
                    <span class="error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <img id="bannerImg" src="{{$bannerImg}}" alt="Banner" height="120" />
                </div>
            </div>
        </div>
    </div> 
     <div class="col-12 fl">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{Form::label('store_certificate','Certificate',['class'=>''])}}
                    {{Form::file('certificate',['id'=>'store_certificate','class'=>'form-control','accept'=>'.xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf'])}}
                    <span class="error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                   @if($cert_file !="") <a href="{{$cert_file}}" download> View </a>@endif 
                </div>
            </div>
        </div>
    </div> 
</div>
                        