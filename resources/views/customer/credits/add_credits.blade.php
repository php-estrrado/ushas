@extends('layouts.admin')
@section('css')
		<!-- INTERNAl Data table css -->
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('admin/assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('admin/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
						<!--Page header-->


						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0"> Manage Customer Credits</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="{{ url('/admin/customer/credits') }}"><i class="fe fe-grid mr-2 fs-14"></i>Credits</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Manage Customer Credits</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')
						
				<!-- Row-->

<?php 

if(isset($credits) && count($credits)>0)
{
    $user_id = $credits->user_id;
    $credit_limit =$credits->credit_limit;
    $credit_days =$credits->credit_days;
    $allow_purchase =$credits->allow_purchase;
    $per_purchase =$credits->per_purchase;
}else{
	$user_id = 0;
	$credit_limit = 0;
	$credit_days = 0;
	$allow_purchase = 0;
	$per_purchase = 0;
}
?>


						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Manage Credit</div>
									</div>
									<div class="card-body">

									{{ Form::open(array('url' => "admin/customer/credits-save", 'id' => 'manageCredits', 'name' => 'manageCredits', 'class' => '','files'=>'true')) }}
									{{Form::hidden('manage[add_new]',1,['id'=>'add_id'])}}
									<div id="" class="col-6 fl">
									<div class="form-group">
									{{Form::label('customer_id','Customer',['class'=>''])}}
									<select name="manage[user_id]" id="customer_id" class="form-control">
										
										@if(isset($customers) && count($customers)>0)

										@foreach($customers as $ck=>$name)
										<option value="{{$name->user_id}}">{{ $name->first_name." ".$name->middle_name." ".$name->last_name }}</option>
										@endforeach

										@endif
									</select>
									<span class="error"></span>
									</div>
									</div>
									<div class="clearfix"></div>
									<div id="" class="col-6 fl">
									<div class="form-group">
									{{Form::label('credit_limit','Credit Limit',['class'=>''])}}
									{{Form::number('manage[credit_limit]',$credit_limit,['id'=>'credit_limit', 'class'=>'form-control','min'=>1,'placeholder'=>'Credit Limit'])}}
									<span class="error"></span>
									</div>
									</div>
									<div id="" class="col-6 fl">
									<div class="form-group">
									{{Form::label('credit_days','Credit Days',['class'=>''])}}
									{{Form::number('manage[credit_days]',$credit_days,['id'=>'credit_days', 'class'=>'form-control','min'=>1,'placeholder'=>'Credit Days'])}}
									<span class="error"></span>
									</div>
									</div>
									<div id="" class="col-6 fl">
									<div class="form-group">
									{{Form::label('per_purchase','Max Credit Per Purchase',['class'=>''])}}
									{{Form::number('manage[per_purchase]',$per_purchase,['id'=>'per_purchase', 'class'=>'form-control','placeholder'=>'Max Credit Per Purchase'])}}
									<span class="error"></span>
									</div>
									</div>
									<div id="" class="col-6 fl">
									<div class="form-group">
									<div class="form-group">
									{{Form::label('c_yes','Allow cash purchase if limit reached',['class'=>''])}}
									<div class="col-12">
									<label class="custom-control custom-checkbox custom-control-sm ">
									<input type="checkbox" name="manage[allow_purchase]" class='custom-control-input' value="1" <?php if($allow_purchase ==1){ echo 'checked'; } ?> >
									<span class="custom-control-label custom-control-label-sm"> Yes </span>
									</label>

									</div><div class="clr"></div>
									</div>
									<span class="error"></span>
									</div>
									</div>
									{{Form::submit('Save',['id'=>'save_btn','class'=>'btn btn-info btn-md fr'])}}
								{{Form::close()}}
									</div>
									<div class="card-footer">
										<div class="row">
											<div class="col d-flex justify-content-end">
											<a href="{{route('customer.credits')}}"  class="mr-2 btn btn-secondary" >Back</a>			
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End row-->
				</div><!-- end app-content-->
            </div>
@endsection
@section('js')
<!-- INTERNAl Data tables -->
		<script src="{{URL::asset('admin/assets/js/datatable/tables/wallet_log-datatable.js')}}"></script>
	<script type="text/javascript">
    $(document).ready(function(){
        $('#customer_wallet').addClass("active");
          $('#a_c_w').addClass("active");
          $('#wallet').addClass("is-expanded");
            @if(Session::has('message'))
            @if(session('message')['type'] =="success")
            
            toastr.success("{{session('message')['text']}}"); 
            @else
            toastr.error("{{session('message')['text']}}"); 
            @endif
            @endif
            
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            toastr.error("{{$error}}"); 
            
            @endforeach
            @endif
    });
    </script>
<script type="text/javascript">



 $('body').on('submit','#manageCredits',function(e){ 
        $('#manageCredits .error').html('');
      
            e.preventDefault();    
            var formData = new FormData(this);
            $('#manageCredits #save_btn').attr('disabled',true); $('#manageCredits #save_btn').text('Validating...'); 
            $.ajax({
                type: "POST",
                url: '{{url("/admin/customer/credits-validate")}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data == 'success'){ 
                        $('#manageCredits #save_btn').text('Saving...'); $('#manageCredits #cancel_btn').trigger('click'); 
                        // document.register.submit(); 
                        submitForm(formData,'manageCredits','save_btn'); return false;
                         return false;
                    }else{
                        var errKey = ''; var n = 0;
                        $.each(data, function(key,value) { if(n == 0){ errKey = key; n++; }

                       
                        	 	 
                        	 	 if(key == "org_image") { $(".image.error").html(value); }else{
                        	 	 	$('#manageCredits #'+key).closest('div').find('.error').html(value);
                        	 	 }

                                 if(key =="tab") { $("."+value).click(); }
                        	 
                           
                        }); 
                        $('#manageCredits #'+errKey).focus();
                        $('#manageCredits #save_btn').attr('disabled',false); $('#manageCredits #save_btn').text('Save'); return false;
                    }
                    return false;
                }
            });


        
      return false; 
    });

   function submitForm(postValues,form,button){
   	
        $.ajax({
            type: "POST",
            url: '{{url("/admin/customer/credits-save")}}',
            data: postValues,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) { 
              $('#'+form+' #'+button).attr('disabled',false); $('#'+form+' #'+button).text('Save');
              var msg = 'Details updated successfully!'; 
              toastr.success(msg);  
              var redir_url = '{{url("/admin/customer/credits")}}';
              
              setTimeout(function(){ 
                     window.location.href  = redir_url; }, 1000);

            } 
        });
    }

</script>


@endsection