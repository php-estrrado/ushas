@extends('layouts.master2')
@section('css')
<link rel="stylesheet" href="{{URL::asset('admin/assets/css/toastr.min.css')}}" />
@endsection
@section('content')
<div class="page">
    <div class="page-content">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-6">
                    <div class="">
                        <div class="text-white">
                            <div class="card-body">
                                <h2 class="display-4 mb-2 font-weight-bold error-text text-center"><strong>Reset Password</strong></h2>
                                <form method="POST" action="{{ url('update/password') }}" id="updatePass">
                                @csrf
                                <div class="row">
                                    <div class="col-9 d-block mx-auto">
                                        <div class="input-group mb-4">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fe fe-mail"></i></div>
                                            </div>
                                            <input id="email" type="email" class="form-control" name="email" placeholder="Email" title="Enter Email" required />
                                        </div>
                                        <div class="input-group mb-4">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-key"></i></div>
                                            </div>
                                            <input id="password" type="password" class="form-control" name="password" placeholder="Password" title="Enter Password" autocomplete="off" required />
                                            <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                        </div> 
                                        <div class="input-group mb-4">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-key"></i></div>
                                            </div>
                                            <input id="confirm_password" type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" title="Enter Confirm Password" required />
                                        <span toggle="#confirm_password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                            </div>
                                        <button type="submit" class="btn btn-secondary btn-block px-4" style="background-color:#fff; color:#f00 !important; padding:10px;"><i class="fe fe-send"></i> Send</button>
                                        <input type="hidden" id="usr_email" value="{{$reset->email}}" /><input type="hidden" id="token" name="token" value="{{$reset->token}}" />
                                    </div>
                                </div>
                            </form>
                            <div class="pt-4 text-center">
                                    <div class="font-weight-normal fs-1<6"><a class="btn-link font-weight-normal text-white-80" href="{{ url('login')}}">Back to login page</a></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <!--<div class="col-md-6 d-none d-md-flex align-items-center">-->
                    <!--	<img src="{{URL::asset('assets/images/png/login.png')}}" alt="img">-->
                    <!--</div>-->
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{URL::asset('admin/assets/js/toastr.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){ 
        $('#updatePass').on('submit',function(){
            var result  =   true;   var pass    =   $('#password').val();   var cPass   =    $('#confirm_password').val();
            if($('#email').val() != $('#usr_email').val()){ toastr.error("Entered incorrect Email"); result = false; }
            if(pass != cPass){ toastr.error("The password not match with comfirm password"); result = false; }
            if(pass.length < 6){ toastr.error("The password must be at least 6 characters"); result = false; }
            if(result == true){ return true; }else{ return false; } return false;
        });
    });
</script>
@endsection