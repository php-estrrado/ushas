<div class="tab-pane active " id="tab1">
    <div class="card-header mb-4"><div class="card-title">Basic Information</div></div>
    <!--<div class="col-lg-6 fl">-->
    <!--    <div class="form-group">-->
    <!--        {{Form::label('business_name','Business Name',['class'=>''])}} <span class="text-red">*</span>-->
    <!--        {{Form::text('info[business_name]',$bName,['id'=>'business_name', 'class'=>'form-control','placeholder'=>'Business Name'])}}-->
    <!--        <span class="error"></span>-->
    <!--    </div>-->
    <!--</div>-->
    <div id="filter_div" class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('store_name','Store Name',['class'=>''])}} <span class="text-red">*</span>
            {{Form::text('info[store_name]',$sName,['id'=>'store_name', 'class'=>'form-control','placeholder'=>'Store Name'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('email','Email Id',['class'=>''])}} <span class="text-red">*</span>
            {{Form::text('info[email]',$email,['id'=>'email', 'class'=>'form-control','placeholder'=>'Email'])}}
            <span class="error"></span>
        </div>
    </div>
    
    <!--<div id="config_div" class="col-lg-6 fl">-->
    <!--    <div class="form-group">-->
    <!--        {{Form::label('licence','Licence',['class'=>''])}}-->
    <!--        {{Form::text('info[licence]',$licence,['id'=>'licence', 'class'=>'form-control','placeholder'=>'Licence'])}}-->
    <!--        <span class="error"></span>-->
    <!--    </div>-->
    <!--</div>-->
    <div id="config_div" class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('first_name','First Name',['class'=>''])}}
            {{Form::text('info[first_name]',$first_name,['id'=>'first_name', 'class'=>'form-control','placeholder'=>'First Name'])}}
            <span class="error"></span>
        </div>
    </div>
    <div id="config_div" class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('last_name','Last Name',['class'=>''])}}
            {{Form::text('info[last_name]',$last_name,['id'=>'last_name', 'class'=>'form-control','placeholder'=>'Last Name'])}}
            <span class="error"></span>
        </div>
    </div>
    <div id="data_type_div" class="col-md-6 fl">
        <label for="phone">Phone </label>

        <div class="row">
        <div class="col-3 pr-0">
        <select id="isd_code" name="info[isd_code]" class="form-control p-1" >
        @if($c_code) @foreach($c_code as $row=>$isd) 
        @php if('+'.$isd == $isd_code){ $selected = 'selected'; }else{ $selected = ''; } @endphp

        <option value="{{ $isd }}" {{$selected}}>+{{$isd}}</option>
        @endforeach @endif
        </select>
        </div>
        <div class="col-9 pl-0">
        <input type="text" class="form-control" name="info[phone]" id="phone" placeholder="Phone" value="{{ $phone }}" >
        <span class="error"></span>
        </div>
        </div>
        <span class="error"></span>
        </div>
        <div class="col-lg-6 fl">
        <div class="form-group">
            {{Form::label('certificate_end_date','Trade license end date',['class'=>''])}} <span class="text-red"></span>
            {{Form::date('info[certificate_end_date]',$cert_end_date,['id'=>'certificate_end_date','class'=>'form-control','placeholder'=>'Trade license end date'])}}
            <span class="error"></span>
        </div>
    </div>
    <div class="clearfix"></div>
    <!--<div class="col-lg-6 fl">-->
    <!--    <div class="form-group">-->
    <!--        {{Form::label('director_name','Director Name',['class'=>''])}} <span class="text-red">*</span>-->
    <!--        {{Form::text('info[director_name]',$name,['id'=>'director_name','class'=>'form-control','placeholder'=>'Director Name'])}}-->
    <!--        <span class="error"></span>-->
    <!--    </div>-->
    <!--</div>-->
    <!--<div class="col-lg-6 fl">-->
    <!--    <div class="form-group">-->
    <!--        {{Form::label('ic_number','Director IC Number',['class'=>''])}}-->
    <!--        {{Form::number('info[ic_number]',$icNo,['id'=>'ic_number','class'=>'form-control','placeholder'=>'Director IC Number'])}}-->
    <!--        <span class="error"></span>-->
    <!--    </div>-->
    <!--</div> -->
    <!--<div class="col-lg-6 fl">-->
    <!--    <div class="form-group">-->
    <!--        {{Form::label('incharge_name','Incharge Name',['class'=>''])}}-->
    <!--        {{Form::text('info[incharge_name]',$icName,['id'=>'incharge_name','class'=>'form-control','placeholder'=>'Incharge Name'])}}-->
    <!--        <span class="error"></span>-->
    <!--    </div>-->
    <!--</div>-->
    <!--<div class="col-md-6 fl">-->
    <!--    <label for="phone">Incharge Phone </label>-->

    <!--    <div class="row">-->
    <!--    <div class="col-3 pr-0">-->
    <!--    <select id="isd_code" name="info[incharge_isd_code]" class="form-control p-1" >-->
    <!--    @if($c_code) @foreach($c_code as $row=>$isd) -->
    <!--    @php if($isd == $icPhoneISD){ $selected = 'selected'; }else{ $selected = ''; } @endphp-->

    <!--    <option value="{{ $isd }}" {{$selected}}>+{{$isd}}</option>-->
    <!--    @endforeach @endif-->
    <!--    </select>-->
    <!--    </div>-->
    <!--    <div class="col-9 pl-0">-->
    <!--    <input type="text" class="form-control" name="info[incharge_phone]" id="phone" placeholder="Phone" value="{{ $icPhone }}" required>-->
    <!--    <span class="error"></span>-->
    <!--    </div>-->
    <!--    </div>-->
    <!--    <span class="error"></span>-->
    <!--    </div>-->
    <!--<div class="col-lg-6 fl">-->
    <!--    <div class="row">-->
    <!--        <div class="col-md-6">-->
    <!--            <div class="form-group">-->
    <!--                {{Form::label('logo','Logo',['class'=>''])}}-->
    <!--                {{Form::file('image[logo]',['id'=>'logo','class'=>'form-control imgup','accept'=>'image/*'])}}-->
    <!--                <span class="error"></span>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="col-md-6">-->
    <!--            <div class="form-group">-->
    <!--             @if($id>0)   <img id="logoImg" src="{{$logoImg}}" alt="Logo" height="70" /> @endif-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!--<div class="col-12 fl">-->
    <!--    <div class="row">-->
    <!--        <div class="col-md-6">-->
    <!--            <div class="form-group">-->
    <!--                {{Form::label('banner','Store Banner',['class'=>''])}}-->
    <!--                {{Form::file('image[banner]',['id'=>'banner','class'=>'form-control imgup','accept'=>'image/*'])}}-->
    <!--                <span class="error"></span>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="col-md-6">-->
    <!--            <div class="form-group">-->
    <!--              @if($id>0)   <img id="bannerImg" src="{{$bannerImg}}" alt="Banner" height="120" />  @endif-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div> -->
    <div class="col-12 fl">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                <div class="col 8">
                <div class="form-group">
                    {{Form::label('agreement','Seller Agreement',['class'=>''])}}
                    {{Form::file('agreement',['id'=>'agreement','class'=>'form-control imgup','accept'=>'.xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf'])}}
                    <span class="error"></span>
                </div>
                </div>
                <div class="col 4">
                    <a href="{{$agreement_samples}}" download class=" d-block mt-6" target="_blank"> Download form </a>
                </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                   @if($agreement !="") <a href="{{$agreement}}" download class=" d-block mt-6" target="_blank"> View agreement </a>@endif
                </div>
            </div>
        </div>
    </div> 
    
    <div class="col-12 fl">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{Form::label('store_certificate','Trade license Certificate',['class'=>''])}}
                    {{Form::file('certificate',['id'=>'store_certificate','class'=>'form-control imgup','accept'=>'.xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf'])}}
                    <span class="error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                   @if($cert_file !="") <a href="{{$cert_file}}" download class=" d-block mt-6" target="_blank"> View Certificate </a>@endif 
                </div>
            </div>
        </div>
    </div>
</div>
                        