@extends('layouts.master4')
@section('css')
@endsection
@section('content')
<div class="page">
			<div class="page-single">
				<div class="container">
					<div class="row">
						<div class="col mx-auto">
							<div class="row justify-content-center">
								<div class="col-md-5">
									<div class="card">
										<div class="card-body">
											<div class="text-center mb-4 ">
												
											</div>
											<span class="m-4 d-none d-lg-block text-center">
												<span class="fs-20"><strong>Site Config</strong></span>
											</span>
											{{Form::open(['url' => "admin/unlock-password", 'id' => 'userForm', 'name' => 'userForm', 'class' => '','files'=>'true', 'novalidate'])}}
											<div class="form-group">
												<input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
												@error('password')
												<p style="color: red">{{ $message }}</p>
												@enderror
											</div>
												@error('error')
												<p style="color: red">{{ $message }}</p>
												@enderror
											<button  class="btn btn-primary btn-block"><i class="fe fe-lock"></i> Unlock</button>
										</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection
@section('js')
@endsection