@extends('layouts.master2')
@section('css')
@endsection
@section('content')
<div class="page">
			<div class="page-content">
				<div class="container">
					<div class="row">
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
									<form method="POST" action="{{ route('password.email') }}">
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
												<button type="button" class="btn btn-secondary btn-block px-4"><i class="fe fe-send"></i> Send</button>
											</div>
										</div>
									</form>
										<div class="pt-4 text-center">
											<div class="font-weight-normal fs-16"><a class="btn-link font-weight-normal text-white-80" href="{{ url('/seller/login')}}">Back to login page</a></div>
										</div>
									</div>
									<!--<div class="custom-btns text-center">-->
									<!--	<button class="btn btn-icon" type="button"><span class="btn-inner-icon"><i class="fa fa-facebook-f"></i></span></button>-->
									<!--	<button class="btn btn-icon" type="button"><span class="btn-inner-icon"><i class="fa fa-google"></i></span></button>-->
									<!--</div>-->
								</div>
							</div>
						</div>
						<div class="col-md-6 d-none d-md-flex align-items-center">
							<img src="{{URL::asset('assets/images/png/login.png')}}" alt="img">
						</div>
					</div>
				</div>
			</div>
        </div>
@endsection
@section('js')
@endsection