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
										@if (session('status'))
					                        <div class="alert alert-success" role="alert">
					                            {{ session('status') }}
					                        </div>
					                    @endif
										<h2 class="display-4 mb-2 font-weight-bold error-text text-center"><strong>Forgot Password</strong></h2>
										<!-- <h4 class="text-white-80 mb-7 text-center">Forgot Password Page</h4> -->
									<form method="POST" action="{{ url('forgot/password') }}">
                        				@csrf
										<div class="row">
											<div class="col-9 d-block mx-auto">
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-mail"></i>
														</div>
													</div>
													<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
												</div>
												@error('email')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                             	   @enderror
												<!-- <div class="form-group">
													<label class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" />
														<span class="custom-control-label"><a href="{{url('/' . $page='terms')}}" class="text-white-80">Agree the terms and policy</a></span>
													</label>
												</div> -->
												<button type="submit" class="btn btn-secondary btn-block px-4" style="background-color:#fff; color:#f00 !important; padding:10px;"><i class="fe fe-send"></i> Send</button>
											</div>
										</div>
									</form>
										<div class="pt-4 text-center">
											<div class="font-weight-normal fs-16"><a class="btn-link font-weight-normal text-white-80" href="{{ url('admin/login')}}">Back to login page</a></div>
										</div>
									</div>
									<!--<div class="custom-btns text-center">-->
									<!--	<button class="btn btn-icon" type="button"><span class="btn-inner-icon"><i class="fa fa-facebook-f"></i></span></button>-->
									<!--	<button class="btn btn-icon" type="button"><span class="btn-inner-icon"><i class="fa fa-google"></i></span></button>-->
									<!--</div>-->
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
        @if(Session::has('success')) toastr.success("{{ Session::get('success')}}"); 
        @elseif(Session::has('message')) toastr.error("{{ Session::get('message')}}");  @endif 
    });
</script>
@endsection