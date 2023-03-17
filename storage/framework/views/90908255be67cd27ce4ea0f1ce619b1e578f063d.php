
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(URL::asset('admin/assets/css/toastr.min.css')); ?>" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="page">
			<div class="page-content">
				<div class="container">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-6">
							<div class="">
								<div class="text-white">
									<div class="card-body">
										<?php if(session('status')): ?>
					                        <div class="alert alert-success" role="alert">
					                            <?php echo e(session('status')); ?>

					                        </div>
					                    <?php endif; ?>
										<h2 class="display-4 mb-2 font-weight-bold error-text text-center"><strong>Forgot Password</strong></h2>
										<!-- <h4 class="text-white-80 mb-7 text-center">Forgot Password Page</h4> -->
									<form method="POST" action="<?php echo e(url('forgot/password')); ?>">
                        				<?php echo csrf_field(); ?>
										<div class="row">
											<div class="col-9 d-block mx-auto">
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-mail"></i>
														</div>
													</div>
													<input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>
												</div>
												<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong><?php echo e($message); ?></strong>
			                                    </span>
			                             	   <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
												<!-- <div class="form-group">
													<label class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" />
														<span class="custom-control-label"><a href="<?php echo e(url('/' . $page='terms')); ?>" class="text-white-80">Agree the terms and policy</a></span>
													</label>
												</div> -->
												<button type="submit" class="btn btn-secondary btn-block px-4" style="background-color:#fff; color:#f00 !important; padding:10px;"><i class="fe fe-send"></i> Send</button>
											</div>
										</div>
									</form>
										<div class="pt-4 text-center">
											<div class="font-weight-normal fs-16"><a class="btn-link font-weight-normal text-white-80" href="<?php echo e(url('admin/login')); ?>">Back to login page</a></div>
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
						<!--	<img src="<?php echo e(URL::asset('assets/images/png/login.png')); ?>" alt="img">-->
						<!--</div>-->
					</div>
				</div>
			</div>
        </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script src="<?php echo e(URL::asset('admin/assets/js/toastr.min.js')); ?>"></script>
<script type="text/javascript">
    $(document).ready(function(){ 
        <?php if(Session::has('success')): ?> toastr.success("<?php echo e(Session::get('success')); ?>"); 
        <?php elseif(Session::has('message')): ?> toastr.error("<?php echo e(Session::get('message')); ?>");  <?php endif; ?> 
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/qaushas/public_html/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>